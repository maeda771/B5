<?php
// 商品一覧画面で使用するデータ

// 検索・並び替え・絞り込み条件を受け取る。
$keyword = trim((string) ($_GET['keyword'] ?? ''));
$sort = (string) ($_GET['sort'] ?? 'newest');
$category = (string) ($_GET['category'] ?? '');

// 絞り込みのカテゴリ選択肢
$categories = [
    'トップス',
    'ボトムス',
    'アウター',
    'シューズ',
    'その他・小物',
];

// 並び替えの選択肢
$sortOptions = [
    'newest' => '新しい順',
    'oldest' => '古い順',
    'stock_desc' => '在庫数が多い順',
    'stock_asc' => '在庫数が少ない順',
];

// SQLで利用する並び替えの条件
$orderByOptions = [
    'newest' => 'p.created_at DESC, p.product_id DESC',
    'oldest' => 'p.created_at ASC, p.product_id ASC',
    'stock_desc' => 'total_stock DESC, p.product_id DESC',
    'stock_asc' => 'total_stock ASC, p.product_id DESC',
];

if (!isset($sortOptions[$sort])) {
    $sort = 'newest';
}

if (!in_array($category, $categories, true)) {
    $category = '';
}

// 表示件数
$requestedCount = filter_input(
    INPUT_GET,
    'per_page',
    FILTER_VALIDATE_INT
);

$perPage = in_array($requestedCount, [5, 8], true)
    ? $requestedCount
    : 8;

// 検索条件と、SQLに渡す値を準備
$conditions = [];
$params = [];

if ($keyword !== '') {
    $conditions[] =
        '(p.product_name LIKE ? OR CAST(p.product_id AS CHAR) LIKE ?)';

    $params[] = '%' . $keyword . '%';
    $params[] = '%' . $keyword . '%';
}

if ($category !== '') {
    $conditions[] = 'p.category = ?';
    $params[] = $category;
}

$where = $conditions
    ? ' WHERE ' . implode(' AND ', $conditions)
    : '';

// 条件に一致する商品の総件数を取得
$stmt = $pdo->prepare(
    'SELECT COUNT(*) FROM products p' . $where
);

$stmt->execute($params);
$totalCount = (int) $stmt->fetchColumn();

// 総ページ数・現在のページ・取得開始位置を計算
$totalPages = max(1, (int) ceil($totalCount / $perPage));

$page = max(1, (int) ($_GET['page'] ?? 1));
$page = min($page, $totalPages);

$offset = ($page - 1) * $perPage;

// 商品詳細の在庫を合計して、商品ごとの総在庫数を取得
$sql = '
    SELECT
        p.product_id,
        p.product_name,
        p.product_price,
        COALESCE(s.total_stock, 0) AS total_stock
    FROM products p
    LEFT JOIN (
        SELECT
            product_id,
            SUM(stock_quantity) AS total_stock
        FROM product_details
        GROUP BY product_id
    ) s ON p.product_id = s.product_id
' . $where . '
    ORDER BY ' . $orderByOptions[$sort] . '
    LIMIT ' . $perPage . ' OFFSET ' . $offset;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$products = $stmt->fetchAll();

// ページ移動時も検索条件と表示件数を引き継ぎ
$pageUrl = static function (int $targetPage) use (
    $keyword,
    $sort,
    $category,
    $perPage
): string {
    return 'products.php?' . http_build_query([
        'keyword' => $keyword,
        'sort' => $sort,
        'category' => $category,
        'per_page' => $perPage,
        'page' => $targetPage,
    ]);
};

// ページ番号は最大5個表示
$startPage = max(1, $page - 2);
$endPage = min($totalPages, $startPage + 4);
$startPage = max(1, $endPage - 4);