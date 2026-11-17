<!-- A13 会員詳細画面 -->
<!-- 実装する機能：対象会員の情報と注文情報の表示、A12へ戻る操作。 -->

<?php
/*
ログインしていなければログイン画面へ

require_once __DIR__ . '/../../app/auth.php';

if (getAdminId() === false) {
    header('Location: /B5/public/admin/login.php');
    exit;
}
*/
?>

 <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>会員詳細画面</title>
</head>
<body>
    <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('会員詳細','back','members.php');
     ?>

     <main>

     
     </main>
</body>
</html>
