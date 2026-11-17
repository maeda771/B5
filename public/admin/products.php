<!-- A02 商品一覧画面 -->
<!-- A03 商品削除確認画面 -->
<!-- 実装する機能：商品名・商品番号検索、追加日・在庫数の並び替え、カテゴリ絞り込み、ページ移動、商品選択、新規登録・詳細への遷移。A03は削除確認モーダル（キャンセル・削除確定）。参照されている商品の削除方針は要確認。 -->

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
    <title>商品一覧画面</title>
</head>
<body>
    <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('商品一覧');
     ?>

     <main>

     
     </main>


    <?php 
    // フッターの表示
    require_once __DIR__ . '/../../app/views/layouts/admin-footer.php';
    renderFooter('products'); 
    ?>
</body>
</html>