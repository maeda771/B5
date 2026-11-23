<?php
require_once __DIR__ . '/../../csrf.php';

// 商品編集の入力を受け取り、エラーを$errorsへ追加
if (!verifyCsrfToken($_POST['token'] ?? null)) {
    $errors[] = '画面を開き直してから保存してください。';
}
if (($_POST['action'] ?? '') !== 'update_product') {
    $errors[] = '商品編集フォームから送信してください。';
}
$productName = is_string($_POST['product_name'] ?? null) ? trim($_POST['product_name']) : '';
$category = is_string($_POST['category'] ?? null) ? $_POST['category'] : '';
$price = is_string($_POST['product_price'] ?? null) ? trim($_POST['product_price']) : '';
$description = is_string($_POST['description'] ?? null) ? trim($_POST['description']) : '';
$postedVersion = is_string($_POST['version'] ?? null) ? $_POST['version'] : '';
if (!hash_equals($version, $postedVersion)) {
    $errors[] = '別の操作で商品が変更されています。画面を開き直して確認してください。';
}
if ($productName === '' || mb_strlen($productName) > 30) {
    $errors[] = '商品名は1〜30文字で入力してください。';
}
if (!in_array($category, $categories, true)) {
    $errors[] = 'カテゴリを選択してください。';
}
if (!ctype_digit($price) || (int) $price < 1) {
    $errors[] = '価格は1円以上の整数で入力してください。';
}
if ($description === '' || mb_strlen($description) > 100) {
    $errors[] = '商品説明は1〜100文字で入力してください。';
}
