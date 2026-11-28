<?php
// 会員一覧の検索・取得と、共通部品に渡す設定
return (static function (PDO $pdo): array {
    require_once __DIR__ . '/../views/components/list/helpers.php';

    // 検索文字を受け取る
    $keyword = is_string($_GET['keyword'] ?? null)
        ? trim($_GET['keyword'])
        : '';

    // 登録年月の選択値を受け取る
    $year = is_string($_GET['year'] ?? null)
        ? $_GET['year'] : '';
    $month = is_string($_GET['month'] ?? null)
        ? $_GET['month'] : '';

    // 登録日の並び順を受け取る
    $sort = is_string($_GET['sort'] ?? null)
        ? $_GET['sort']
        : 'newest';

    $sortOptions = [
        'newest' => '新しい順',
        'oldest' => '古い順',
    ];

    $orderByOptions = [
        'newest' => 'members.registration_date DESC, members.member_id DESC',
        'oldest' => 'members.registration_date ASC, members.member_id ASC',
    ];

    if (!isset($sortOptions[$sort])) {
        $sort = 'newest';
    }

    // 会員番号・氏名の検索条件を作る
    $conditions = [];
    $params = [];

    if ($keyword !== '') {
        $conditions[] = '(
            CAST(members.member_id AS CHAR) LIKE ?
            OR members.member_name LIKE ?
        )';
        $params[] = '%' . $keyword . '%';
        $params[] = '%' . $keyword . '%';
    }

    // 登録年で絞り込む。
    if ($year !== '') {
        $conditions[] = 'YEAR(members.registration_date) = ?';
        $params[] = $year;
    }

    // 登録月で絞り込む。
    if ($month !== '') {
        $conditions[] = 'MONTH(members.registration_date) = ?';
        $params[] = $month;
    }

    $where = $conditions
        ? ' WHERE ' . implode(' AND ', $conditions)
        : '';

    
    // 条件に合う会員の総件数を取得
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM members' . $where);
    $stmt->execute($params);
    $totalCount = (int) $stmt->fetchColumn();

    // 1ページの表示件数は、縦長8件・横長5件
    $requestedPerPage = filter_input(
        INPUT_GET,
        'per_page',
        FILTER_VALIDATE_INT
    );
    $perPage = in_array($requestedPerPage, [5, 8], true)
        ? $requestedPerPage
        : 8;

    // 総ページ数と、表示するページを計算
    $totalPages = max(1, (int) ceil($totalCount / $perPage));

    $requestedPage = filter_input(
        INPUT_GET,
        'page',
        FILTER_VALIDATE_INT
    );
    $page = min($totalPages, max(1, $requestedPage ?: 1));
    $offset = ($page - 1) * $perPage;

    // 今のページに表示する会員を取得
    $sql = '
        SELECT
            members.member_id,
            members.member_name,
            members.registration_date
        FROM members'
        . $where
        . ' ORDER BY ' . $orderByOptions[$sort]
        . ' LIMIT ' . $perPage
        . ' OFFSET ' . $offset;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ページを移動しても、検索文字と並び順を引き継ぐ
    $pageUrl = createListPageUrl('members.php', [
        'keyword' => $keyword,
        'sort' => $sort,
        'year' => $year,
        'month' => $month,
        'per_page' => $perPage,
    ]);

    // 会員登録がある年を取得
    $stmt = $pdo->query('
        SELECT DISTINCT YEAR(members.registration_date)
        FROM members
        ORDER BY YEAR(members.registration_date) DESC
    ');
    $registrationYears = $stmt->fetchAll(PDO::FETCH_COLUMN);
    // 登録年月の絞り込み表示
    $filterGroups = [
        [
            'name' => 'registration_date',
            'label' => '登録日',
            'type' => 'year_month',
            'years' => $registrationYears,
            'selected' => [
                'year' => $year,
                'month' => $month,
            ],
        ],
    ];

    // 会員一覧と、共通部品に渡す設定を返す
    return [
        'members' => $members,
        'totalCount' => $totalCount,
        'perPage' => $perPage,
        'page' => $page,
        'totalPages' => $totalPages,
        'pageUrl' => $pageUrl,

        // 検索・並べ替え・絞り込みの設定
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

        // 表に表示する列
        'columns' => [
            ['key' => 'member_id', 'label' => '会員番号'],
            ['key' => 'member_name', 'label' => '氏名'],
            [
                'key' => 'registration_date',
                'label' => '登録日',
                'format' => static function ($value): string {
                    return date('Y/m/d', strtotime($value));
                },
            ],
        ],

        // 詳細画面にも、一覧の検索条件とページ番号を渡す
        'table' => [
            'idKey' => 'member_id',
            'detail' => static function (array $row) use (
                $keyword, $sort, $year, $month, $page, $perPage
            ): string {
                return 'member.php?' . http_build_query([
                    'member_id' => (int) $row['member_id'],
                    'return_keyword' => $keyword,
                    'return_sort' => $sort,
                    'return_year' => $year,
                    'return_month' => $month,
                    'return_page' => $page,
                    'return_per_page' => $perPage,
                ]);
            },
            'empty' => '条件に一致する会員はありません。',
        ],
    ];
})($pdo);

