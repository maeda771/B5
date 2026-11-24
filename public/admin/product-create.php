<?php
// A04 商品登録画面
// A05 カラー追加画面
require_once __DIR__ . '/../../app/auth.php';
/*
ログインしていなければログイン画面へ
if (getAdminId() === false) {
    header('Location: /B5/public/admin/login.php');
    exit;
}
*/
require_once __DIR__ . '/../../app/db.php';
require __DIR__ . '/../../app/admin/product-create-data.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/product-create.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/product-create-selection.js" defer></script>
<script src="../js/product-create.js" defer></script>
<script src="../js/product-create-image.js" defer></script>
<script src="../js/product-create-color.js" defer></script>
<script src="../js/color-label.js" defer></script>
<script src="../js/product-create-selection.js" defer></script>
    <title>商品登録画面</title>
</head>
<body class="has-header product-create-body">
    <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('商品登録','back','products.php');
     ?>
    <main class="product-create-page">
        <!-- エラーがあれば表示 -->
        <?php if ($errors): ?>
            <div class="form-error" role="alert">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
                <p>画像を選択していた場合は、もう一度選んでください。</p>
            </div>
        <?php endif; ?>

        <?php require __DIR__ . '/../../app/views/components/admin/product-create/form.php'; ?>
    </main>

    <!-- 登録ボタン -->
    <div class="product-create-actions">
        <button class="button product-create-submit" type="submit" form="product-form">登録</button>
    </div>

    <?php require __DIR__ . '/../../app/views/components/admin/product-create/color-modal.php'; ?>
</body>
</html>
