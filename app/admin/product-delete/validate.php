<?php
require_once __DIR__ . '/../../csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('商品一覧の削除ボタンから操作してください。');
}
if (!verifyCsrfToken($_POST['token'] ?? null)) {
    throw new RuntimeException('画面を開き直してから削除してください。');
}
$postedIds = $_POST['product_ids'] ?? [];
if (!is_array($postedIds) || !$postedIds || count($postedIds) > 100) {
    throw new RuntimeException('削除する商品を選択してください。');
}
$productIds = [];
foreach ($postedIds as $id) {
    if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
        throw new RuntimeException('商品番号が正しくありません。');
    }
    $productIds[] = (int) $id;
}
$productIds = array_values(array_unique($productIds));
sort($productIds);
