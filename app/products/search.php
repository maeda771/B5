<?php
require_once __DIR__ . '/conditions.php';

// 管理者・利用者で共通の商品検索
function searchProducts(PDO $pdo, array $input, array $settings = []): array
{
    $criteria = normalizeProductConditions($input, $settings);
    $keyword = $criteria['keyword'];
    $category = $criteria['category'];
    $perPage = $criteria['perPage'];
    // SQLで利用する並び替えの条件
    $orderByOptions = [
        // 追加が新しい順
        'newest' => 'p.created_at DESC, p.product_id DESC',
        // 追加が古い順
        'oldest' => 'p.created_at ASC, p.product_id ASC',
        // おすすめ順(レビューの平均評価順未評価は最後、同点は新しい順)
        'recommended' => '(r.average_rating IS NULL) ASC, r.average_rating DESC, p.created_at DESC, p.product_id DESC',
        // 在庫が多い順
        'stock_desc' => 'total_stock DESC, p.product_id DESC',
        // 在庫が少ない順
        'stock_asc' => 'total_stock ASC, p.product_id DESC',
    ];
    // 削除済みの商品を、件数・検索結果・ページ数から除外
    $conditions = ['p.is_deleted = FALSE'];
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
        // 指定された価格範囲の商品だけ取得
    if ($criteria['minPrice'] !== null) {
        $conditions[] = 'p.product_price >= ?';
        $params[] = $criteria['minPrice'];
    }

    if ($criteria['maxPrice'] !== null) {
        $conditions[] = 'p.product_price <= ?';
        $params[] = $criteria['maxPrice'];
    }
    $where = ' WHERE ' . implode(' AND ', $conditions);
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM products p' . $where);
    $stmt->execute($params);
    $totalCount = (int) $stmt->fetchColumn();
    $totalPages = max(1, (int) ceil($totalCount / $perPage));
    $page = min($totalPages, $criteria['page']);
    $offset = ($page - 1) * $perPage;

    // 色・サイズ別の在庫を合計
    $sql = 'SELECT p.product_id, p.product_name, p.product_price,
                   p.category, p.description, p.image, p.created_at,
                   COALESCE(s.total_stock, 0) AS total_stock,
                   r.average_rating
            FROM products p
            LEFT JOIN (
                SELECT product_id, SUM(stock_quantity) AS total_stock
                FROM product_details GROUP BY product_id
            ) s ON p.product_id = s.product_id
            LEFT JOIN (
                SELECT product_id, AVG(rating) AS average_rating
                FROM reviews GROUP BY product_id
            ) r ON p.product_id = r.product_id'
        . $where . ' ORDER BY ' . $orderByOptions[$criteria['sort']]
        . ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return array_merge($criteria, [
        'page' => $page, 'totalCount' => $totalCount, 'totalPages' => $totalPages,
        'products' => $stmt->fetchAll(PDO::FETCH_ASSOC),
    ]);
}
