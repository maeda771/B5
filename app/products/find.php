<?php
// 商品番号から、商品情報と総在庫を取得する。
// 在庫管理に表示用
function findProductById(PDO $pdo, int $productId): ?array
{
    $sql = '
        SELECT
            p.*,
            (
                SELECT COALESCE(SUM(d.stock_quantity), 0)
                FROM product_details d
                WHERE d.product_id = p.product_id
            ) AS total_stock
        FROM products p
        WHERE p.product_id = ?
          AND p.is_deleted = FALSE
    ';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$productId]);

    // 商品が存在しない場合はnullを返す。
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}