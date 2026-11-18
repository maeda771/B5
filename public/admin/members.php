<!-- A12 会員一覧画面 -->
<!-- 実装する機能：会員一覧、会員番号・氏名検索、登録日の並び替えと絞り込み、A13への遷移。 -->

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
    <title>会員一覧画面</title>
</head>
<body class="has-header has-footer">
    <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('会員一覧');
     ?>

     <main>

     
     </main>


    <?php 
    // フッターの表示
    require_once __DIR__ . '/../../app/views/layouts/admin-footer.php';
    renderFooter('members'); 
    ?>
</body>
</html>