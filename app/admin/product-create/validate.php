<?php
//　商品登録のバリデーションチェック
// エラーがあればerrors[]にエラーメッセージを追加

// トークンが一致しないときのエラーメッセージ
if (!$tokenMatches) {
    $errors[] = empty($_POST)
        ? '送信データを受け取れませんでした。画像の容量を減らしてもう一度お試しください。'
        : '画面を開き直してから登録してください。';
}
if ($action !== 'create_product') {
    $errors[] = '商品登録フォームから送信してください。';
}

$productName = is_string($_POST['product_name'] ?? null) ? trim($_POST['product_name']) : '';
$category = is_string($_POST['category'] ?? null) ? $_POST['category'] : '';
$price = is_string($_POST['product_price'] ?? null) ? trim($_POST['product_price']) : '';
$description = is_string($_POST['description'] ?? null) ? trim($_POST['description']) : '';
$postedColors = is_array($_POST['colors'] ?? null) ? $_POST['colors'] : [];
$postedSizes = is_array($_POST['sizes'] ?? null) ? $_POST['sizes'] : [];
$selectedColors = array_values(array_unique(array_filter($postedColors, function ($id) {
    return is_string($id) && preg_match('/\A[1-9][0-9]*\z/', $id);
})));
$selectedSizes = array_values(array_unique(array_filter($postedSizes, function ($size) use ($sizes) {
    return is_string($size) && in_array($size, $sizes, true);
})));

if ($productName === '' || mb_strlen($productName) > 30) {
    $errors[] = '商品名は1〜30文字で入力してください。';
}
if (!in_array($category, $categories, true)) {
    $errors[] = 'カテゴリを選択してください。';
}
if (!ctype_digit($price) || (int) $price < 1) {
    $errors[] = '価格は1円以上の整数で入力してください';
}
if ($description === '' || mb_strlen($description) > 100) {
    $errors[] = '商品説明は1〜100文字で入力してください。';
}
$knownColorIds = array_map('strval', array_column($colors, 'color_id'));
if (!$selectedColors || array_diff($selectedColors, $knownColorIds) || count($selectedColors) !== count($postedColors)) {
    $errors[] = 'カラーを選択してください。';
}
if (!$selectedSizes || count($selectedSizes) !== count($postedSizes)) {
    $errors[] = 'サイズを選択してください。';
}
