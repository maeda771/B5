<?php
// 在庫変更画面のバリデーションチェック

// 同じ保存がすでに処理されていないか確認
$postedRequestId = $_POST['request_id'] ?? null;

if (
    !is_string($postedRequestId)
    || !hash_equals($requestId, $postedRequestId)
) {
    $errors[] = '保存済み、または古い画面です。在庫管理画面から開き直してください。';
}

$selectedColorId = is_string($_POST['color_id'] ?? null)
    ? $_POST['color_id'] : '';

$selectedSize = is_string($_POST['size'] ?? null)
    ? $_POST['size'] : '';

$quantity = is_string($_POST['quantity'] ?? null)
    ? trim($_POST['quantity']) : '';

$reason = is_string($_POST['reason'] ?? null)
    ? $_POST['reason'] : '';

$memo = is_string($_POST['memo'] ?? null)
    ? trim($_POST['memo']) : '';

// 選択した色・サイズに対応する商品詳細番号を探す
$productDetailsId = null;

foreach ($variants as $variant) {
    if (
        (string) $variant['color_id'] === $selectedColorId
        && $variant['size'] === $selectedSize
    ) {
        $productDetailsId = (int) $variant['product_details_id'];
        break;
    }
}

// 対象商品に存在しない組み合わせならエラー
if ($productDetailsId === null) {
    $errors[] = 'カラーとサイズを選択してください。';
}

// 数量は、プラス・マイナスを含む整数で受け取る
$quantityChange = filter_var($quantity, FILTER_VALIDATE_INT);

if ($quantityChange === false || $quantityChange === 0) {
    $errors[] = '数量は0以外の整数で入力してください。';
}

if (!in_array($reason, ['入荷', '調整'], true)) {
    $errors[] = '変更理由を選択してください。';
}

// 入荷で在庫を減らさない
if (
    $reason === '入荷'
    && $quantityChange !== false
    && $quantityChange < 0
) {
    $errors[] = '入荷の場合は数量を1以上にしてください。';
}

if (mb_strlen($memo) > 255) {
    $errors[] = 'メモは255文字以内で入力してください。';
}