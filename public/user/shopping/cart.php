<!-- U13 カート画面 -->
<!-- 実装する機能：ログイン中の会員のカート表示、購入対象選択、数量変更、削除、在庫確認。選択内容をU14へ渡す。 -->
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>カート画面</title>
</head>
<body class="has-header has-footer">
    <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../../app/views/layouts/header.php';
    renderHeader('カート');
     ?>

     <main>

     
     </main>


    <?php 
    // フッターの表示
    require_once __DIR__ . '/../../../app/views/layouts/footer.php';
    renderFooter('cart'); 
    ?>
</body>