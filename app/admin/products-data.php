<?php
/* 商品専用の検索条件・SQL
 * products.phpで接続した$pdoを使用し、結果を配列で返す。
 */
return (static function (PDO $pdo): array {
    $keyword = isset($_GET['keyword']) && is_string($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $sort = isset($_GET['sort']) && is_string($_GET['sort']) ? $_GET['sort'] : 'newest';
    $category = isset($_GET['category']) && is_string($_GET['category']) ? $_GET['category'] : '';

    $categories = ['トップス', 'ボトムス', 'アウター', 'シューズ', 'その他・小物'];
    $sortOptions = ['newest' => '新しい順', 'oldest' => '古い順', 'stock_desc' => '在庫数が多い順', 'stock_asc' => '在庫数が少ない順'];
    $orderByOptions = [
        'newest' => 'p.created_at DESC, p.product_id DESC',
        'oldest' => 'p.created_at ASC, p.product_id ASC',
        'stock_desc' => 'total_stock DESC, p.product_id DESC',
        'stock_asc' => 'total_stock ASC, p.product_id DESC',
    ];
    if (!isset($sortOptions[$sort])) $sort = 'newest';
    if (!in_array($category, $categories, true)) $category = '';

    // 縦長8件・横長5件。初回の画面の向きはJSから送られる。
    $requested = filter_input(INPUT_GET, 'per_page', FILTER_VALIDATE_INT);
    $perPage = in_array($requested, [5, 8], true) ? $requested : 8;
    $conditions = [];
    $params = [];
    if ($keyword !== '') {
        $conditions[] = '(p.product_name LIKE ? OR CAST(p.product_id AS CHAR) LIKE ?)';
        $params[] = '%' . $keyword . '%';
        $params[] = '%' . $keyword . '%';
    }
    if ($category !== '') {
        $conditions[] = 'p.category = ?';
        $params[] = $category;
    }
    $where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM products p' . $where);
    $stmt->execute($params);
    $totalCount = (int) $stmt->fetchColumn();
    $totalPages = max(1, (int) ceil($totalCount / $perPage));
    $requestedPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $page = min($totalPages, max(1, $requestedPage ?: 1));
    $offset = ($page - 1) * $perPage;

    // 色・サイズ別の在庫を合計する。
    $sql = 'SELECT p.product_id, p.product_name, p.product_price,
                   COALESCE(s.total_stock, 0) AS total_stock
            FROM products p
            LEFT JOIN (
                SELECT product_id, SUM(stock_quantity) AS total_stock
                FROM product_details GROUP BY product_id
            ) s ON p.product_id = s.product_id'
        . $where . ' ORDER BY ' . $orderByOptions[$sort]
        . ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pageUrl = static function (int $target) use ($keyword, $sort, $category, $perPage): string {
        return 'products.php?' . http_build_query([
            'keyword' => $keyword, 'sort' => $sort, 'category' => $category,
            'per_page' => $perPage, 'page' => $target,
        ]);
    };
    $categoryOptions = ['' => 'すべて'];
    foreach ($categories as $item) $categoryOptions[$item] = $item;

    // 共通部品に渡す「設定」と「取得結果」。
    return [
        'perPage' => $perPage, 'totalCount' => $totalCount,
        'page' => $page, 'totalPages' => $totalPages, 'pageUrl' => $pageUrl,
        'products' => $products,
        'controls' => [
            'action' => 'products.php',
            'hidden' => ['per_page' => $perPage],
            'search' => ['value' => $keyword, 'placeholder' => '検索', 'label' => '商品名・商品番号で検索'],
            'sort' => ['options' => $sortOptions, 'selected' => $sort],
            'filters' => [[
                'name' => 'category', 'label' => 'カテゴリ', 'type' => 'radio',
                'options' => $categoryOptions, 'selected' => $category, 'default' => '',
            ]],
        ],
        'columns' => [
            ['key' => 'product_name', 'label' => '商品名', 'class' => 'products-col-name'],
            ['key' => 'product_id', 'label' => '商品番号', 'class' => 'products-col-id'],
            ['key' => 'product_price', 'label' => '価格', 'class' => 'products-col-price', 'format' => 'yen'],
            ['key' => 'total_stock', 'label' => '総在庫', 'class' => 'products-col-stock', 'format' => 'number'],
        ],
        'table' => [
            'idKey' => 'product_id', 'labelKey' => 'product_name',
            'checkbox' => 'product_ids',
            'detail' => static function (array $row): string {
                return 'product-edit.php?product_id=' . (int) $row['product_id'];
            },
            'empty' => '条件に一致する商品はありません。',
        ],
    ];
})($pdo);
