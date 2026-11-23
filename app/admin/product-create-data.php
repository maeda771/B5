<?php
// 商品登録処理全体の管理
require __DIR__ . '/product-create/initialize.php';

// カラー追加処理へ
if ($isPost && $action === 'add_color') {
    require __DIR__ . '/product-create/color-add.php';
}

if ($isPost) {
    // バリデーションチェック
    require __DIR__ . '/product-create/validate.php';
    // 商品画像のチェック
    require __DIR__ . '/product-create/image-validate.php';

    // 入力に問題がない場合だけ、画像と商品を保存する。
    if (!$errors) {
        require __DIR__ . '/product-create/save.php';
    }
}
