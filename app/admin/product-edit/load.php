<?php
// URLの商品番号から、編集する商品と総在庫数を取得
$errors = [];
$product = null;
require_once __DIR__ . '/../../products/options.php';
$categories = getProductCategories();
$productId = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);
if (!$productId || $productId < 1) {
    http_response_code(404);
    return;
}
$find = $pdo->prepare('SELECT * FROM products WHERE product_id = ? AND is_deleted = FALSE');
$find->execute([$productId]);
$product = $find->fetch(PDO::FETCH_ASSOC) ?: null;
if (!$product) {
    http_response_code(404);
    return;
}
$stock = $pdo->prepare('SELECT COALESCE(SUM(stock_quantity), 0)
    FROM product_details WHERE product_id = ?');
$stock->execute([$productId]);
$totalStock = (int) $stock->fetchColumn();
$productName = $product['product_name'];
$category = $product['category'];
$price = (string) $product['product_price'];
$description = $product['description'];
require_once __DIR__ . '/../../csrf.php';
$token = getCsrfToken();

require_once __DIR__ . '/version.php';
$version = productEditVersion($product);
