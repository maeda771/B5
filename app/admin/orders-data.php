<?php
// 注文履歴用の検索条件・一覧表示の設定
return (static function (PDO $pdo): array {
    require_once __DIR__ . '/../views/components/list/helpers.php';

    // 検索欄に入力された値受け取り
    $keyword = is_string($_GET['keyword'] ?? null)
        ? trim($_GET['keyword'])
        : '';

    // 注文日の並び順受け取り
    $sort = is_string($_GET['sort'] ?? null)
        ? $_GET['sort']
        : 'newest';

    $sortOptions = [
        'newest' => '新しい順',
        'oldest' => '古い順',
    ];

    // 用意した選択肢以外なら、新しい順に戻す。
    if (!isset($sortOptions[$sort])) {
        $sort = 'newest';
    }

    // 絞り込みで選択された年月を受け取り
    $status = is_string($_GET['status'] ?? null)
        ? $_GET['status'] : '';
    $year = is_string($_GET['year'] ?? null)
        ? $_GET['year'] : '';
    $month = is_string($_GET['month'] ?? null)
        ? $_GET['month'] : '';

    $conditions = [];
    $params = [];

    // 注文番号・会員番号で検索
    if ($keyword !== '') {
        $conditions[] = '(
            CAST(orders.order_id AS CHAR) LIKE ?
            OR CAST(orders.member_id AS CHAR) LIKE ?
        )';
        $params[] = '%' . $keyword . '%';
        $params[] = '%' . $keyword . '%';
    }

    // 注文の状態で絞り込み
    if ($status !== '') {
        $conditions[] = 'orders.order_status = ?';
        $params[] = $status;
    }

    // 注文年で絞り込み
    if ($year !== '') {
        $conditions[] = 'YEAR(orders.order_datetime) = ?';
        $params[] = $year;
    }

    // 注文月で絞り込み
    if ($month !== '') {
        $conditions[] = 'MONTH(orders.order_datetime) = ?';
        $params[] = $month;
    }

    // 条件がある場合だけ、WHEREを付ける
    $where = $conditions
        ? ' WHERE ' . implode(' AND ', $conditions)
        : '';

    // 注文の総件数を取得
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM orders' . $where);
    $stmt->execute($params);
    $totalCount = (int) $stmt->fetchColumn();

    // 1ページの表示件数設定
    $requestedPerPage = filter_input(
        INPUT_GET,
        'per_page',
        FILTER_VALIDATE_INT
    );
    $perPage = in_array($requestedPerPage, [5, 8], true)
        ? $requestedPerPage
        : 8;

    // 総ページ数を計算
    $totalPages = max(1, (int) ceil($totalCount / $perPage));

    $requestedPage = filter_input(
        INPUT_GET,
        'page',
        FILTER_VALIDATE_INT
    );
    $page = min($totalPages, max(1, $requestedPage ?: 1));

    // 現在より前のページを飛ばす
    $offset = ($page - 1) * $perPage;

    // 注文日の並び順、同じ日時であれば注文番号順
    $orderByOptions = [
        'newest' => 'orders.order_datetime DESC, orders.order_id DESC',
        'oldest' => 'orders.order_datetime ASC, orders.order_id ASC',
    ];

    // データ取得
    $sql = '
        SELECT
            orders.order_id,
            orders.member_id,
            orders.order_datetime,
            orders.billing_amount,
            orders.order_status
        FROM orders'
        . $where
        . ' ORDER BY ' . $orderByOptions[$sort]
        . ' LIMIT ' . $perPage
        . ' OFFSET ' . $offset;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // 取得した注文一覧を配列に入れる。
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ページ移動時に、検索・絞り込み・並び順を引き継ぐ共通関数使用
    $pageUrl = createListPageUrl('orders.php', [
        'keyword' => $keyword,
        'sort' => $sort,
        'status' => $status,
        'year' => $year,
        'month' => $month,
        'per_page' => $perPage,
    ]);

    // 注文がある年を取得
    $stmt = $pdo->query('
        SELECT DISTINCT YEAR(orders.order_datetime)
        FROM orders
        ORDER BY YEAR(orders.order_datetime) DESC
    ');
    $orderYears = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 状態と注文年月を共通の絞り込み部品へ渡す
    $filterGroups = [
        [
            'name' => 'status',
            'label' => '状態',
            'type' => 'radio',
            'options' => [
                '' => 'すべて',
                '注文受付' => '注文受付',
                '発送準備中' => '発送準備中',
                '発送済み' => '発送済み',
            ],
            'selected' => $status,
            'default' => '',
        ],
        [
            'name' => 'order_date',
            'label' => '注文年月',
            'type' => 'year_month',
            'years' => $orderYears,
            'selected' => [
                'year' => $year,
                'month' => $month,
            ],
        ],
    ];

    // 注文一覧と総件数を返す
    return [
        'orders' => $orders,
        'totalCount' => $totalCount,
        'perPage' => $perPage,
        'page' => $page,
        'totalPages' => $totalPages,
        'pageUrl' => $pageUrl,
        // 注文一覧に表示する列。
        'columns' => [
            ['key' => 'order_id', 'label' => '注文番号'],
            ['key' => 'member_id', 'label' => '会員番号'],
            [
                'key' => 'order_datetime',
                'label' => '日時',
                'format' => static function ($value): string {
                    return date('Y/m/d', strtotime($value));
                },
            ],
            [
                'key' => 'billing_amount',
                'label' => '金額',
                'format' => 'yen',
            ],
            [
                'key' => 'order_status',
                'label' => '状態',
                'badge' => true,
            ],
        ],

        // 各注文の詳細画面へのリンク
        'table' => [
            'idKey' => 'order_id',
            'detail' => static function (array $row) use (
                $keyword, $sort, $status, $year, $month, $page, $perPage
            ): string {
                return 'order.php?' . http_build_query([
                    'order_id' => (int) $row['order_id'],
                    'return_keyword' => $keyword,
                    'return_sort' => $sort,
                    'return_status' => $status,
                    'return_year' => $year,
                    'return_month' => $month,
                    'return_page' => $page,
                    'return_per_page' => $perPage,
                ]);
            },
            'empty' => '条件に一致する注文はありません。',
        ],

        // 検索・並び替え・絞り込み
        'controls' => [
            'search' => [
                'value' => $keyword,
            ],
            'sort' => [
                'options' => $sortOptions,
                'selected' => $sort,
            ],
            'filters' => $filterGroups,
        ],
    ];
})($pdo);