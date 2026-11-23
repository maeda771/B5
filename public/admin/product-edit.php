<?php
// A06 商品詳細・編集画面
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/db.php';
/* ログインしていなければログイン画面へ
if (getAdminId() === false) {
    header('Location: /B5/public/admin/login.php');
    exit;
}
*/
require __DIR__ . '/../../app/admin/product-edit-data.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細・編集画面</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/product-edit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/product-edit-image.js" defer></script>
    <script src="../js/product-edit.js" defer></script>
</head>
<body class="has-header product-edit-body">
    <?php
    require_once __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('商品詳細・編集', 'back', $returnUrl);
    ?>
    <main class="product-edit-page">
        <?php if ($product === null): ?>
            <p class="form-error">この商品は削除済み、または存在しません。</p>
            <a href="products.php" class="common-button">商品一覧へ戻る</a>
        <?php else: ?>
            <?php if ($errors): ?>
                <div class="form-error" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endforeach; ?>
                    <p>画像を変更する場合は、もう一度選択してください。</p>
                </div>
            <?php endif; ?>
            <?php require __DIR__ . '/../../app/views/components/admin/product-edit/form.php'; ?>
        <?php endif; ?>
    </main>
    <?php if ($product !== null): ?>
        <div class="product-edit-actions">
            <button type="submit" class="button product-edit-submit" form="product-edit-form">保存</button>
        </div>
    <?php endif; ?>
</body>
</html>
