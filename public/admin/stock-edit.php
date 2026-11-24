<!-- A08 在庫変更画面 -->
<!-- 実装する機能：色・サイズ、増減数量、変更理由、メモの入力。在庫更新と変更前後・増減数量を含む履歴登録を一つのトランザクションで行う。 -->
<?php
/*
ログインしていなければログイン画面へ

require_once __DIR__ . '/../../app/auth.php';

if (getAdminId() === false) {
    header('Location: /B5/public/admin/login.php');
    exit;
}
*/
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/db.php';

// URLから現在の商品番号取得
$productId = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);
if (!$productId || $productId < 1) {
    http_response_code(404);
    exit('商品が見つかりません。');
}

require_once __DIR__ . '/../../app/products/find.php';
require_once __DIR__ . '/../../app/views/components/product-summary.php';
$product = findProductById($pdo, $productId);
if ($product === null) {
    http_response_code(404);
    exit('商品が見つかりません。');
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/product-summary.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/common-list.js" defer></script>
    <script src="../js/product-delete.js" defer></script>
    <title>在庫変更画面</title>
</head>
<body class="has-header">
    <?php
    require_once __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('在庫変更','back','stock.php?product_id='.(int)$productId);
    ?>
    <main>
        <!-- 商品概要の呼び出し -->
        <?php renderProductSummary($product); ?>
    
    </main>
</body>
</html>

