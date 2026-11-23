<?php
// 一覧へ戻るときの検索条件を受け取る
$returnParams = [];

foreach (['keyword', 'sort', 'category', 'page', 'per_page'] as $key) {
    $value = $_POST['return_' . $key]
        ?? $_GET['return_' . $key]
        ?? null;

    if (is_string($value)) {
        $returnParams[$key] = $value;
    }
}

$returnUrl = 'products.php';

if ($returnParams) {
    $returnUrl .= '?' . http_build_query($returnParams);
}

// 取得・入力確認・画像確認・更新の順番を管理する。
require __DIR__ . '/product-edit/load.php';
if ($product === null) return;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/product-edit/validate.php';
    // 登録画面と同じ画像チェックを再利用する。
    require __DIR__ . '/product-create/image-validate.php';
    if (!$errors) {
        require __DIR__ . '/product-edit/save.php';
    }
}
