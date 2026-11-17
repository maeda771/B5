<!-- A10 注文履歴画面 -->
<!-- 実装する機能：注文一覧、注文番号・会員番号検索、注文日の並び替え、状態・注文年月での絞り込み、A11への遷移。 -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>注文履歴画面</title>
</head>
<body>
    <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('注文履歴');
     ?>

     <main>

     
     </main>


    <?php 
    // フッターの表示
    require_once __DIR__ . '/../../app/views/layouts/admin-footer.php';
    renderFooter('orders'); 
    ?>
</body>
</html>