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

// 商品番号を指定し、登録済みの色・サイズ・在庫数を取得する。
function getProductVariants(PDO $pdo, int $productId): array
{
    $stmt = $pdo->prepare('
        SELECT
            product_details.product_details_id,
            product_details.color_id,
            colors.color_name,
            colors.color_code,
            product_details.size,
            product_details.stock_quantity
        FROM product_details
        INNER JOIN colors
            ON colors.color_id = product_details.color_id
        WHERE product_details.product_id = ?
        ORDER BY product_details.color_id
    ');

    $stmt->execute([$productId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 色ごとに在庫をまとめる
function groupProductVariantsByColor(array $variants): array
{
    $colors = [];
    foreach ($variants as $variant) {
        $colorId = $variant['color_id'];
        if (!isset($colors[$colorId])) {
            $colors[$colorId] = [
                'name' => $variant['color_name'],
                'code' => $variant['color_code'],
                'stocks' => [],
            ];
        }
        $colors[$colorId]['stocks'][$variant['size']]
            = $variant['stock_quantity'];
    }

    return $colors;
}

// 登録済みのサイズを、S・M・L・LL・Fの順で返す。
function getProductSizes(array $variants): array
{
    return array_values(array_intersect(
        ['S', 'M', 'L', 'LL', 'F'],
        array_column($variants, 'size')
    ));
}