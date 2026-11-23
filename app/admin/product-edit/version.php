<?php
// 商品情報の変更前の状態を保持
function productEditVersion(array $product): string
{
    return hash('sha256', json_encode([
        (string) $product['product_id'], $product['product_name'],
        $product['category'], (string) $product['product_price'],
        $product['description'], $product['image'], (int) $product['is_deleted'],
    ], JSON_UNESCAPED_UNICODE));
}
