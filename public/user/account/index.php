<!-- U19 マイページ画面 -->
<!-- 実装する機能：ログイン中の会員向けにU20注文履歴・U23会員情報変更・U25お問い合わせへの案内。 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>マイページ画面</title>
</head>
<body>
        <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../../app/views/layouts/header.php';
    renderHeader('マイページ');
     ?>

     <main>

     
     </main>

    <?php 
    // フッターの表示
    require_once __DIR__ . '/../../../app/views/layouts/footer.php';
    renderFooter('mypage'); 
    ?>
</body>