<!-- U16 検索画面 -->
<!-- U17 並び替え・しぼり込み画面 -->
<!-- 実装する機能：商品名検索、並び替え、カテゴリ・価格での絞り込み、商品一覧、詳細への遷移、お気に入り操作。U17はこの画面内のパネルとして実装する。 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>検索画面</title>
</head>
<body>


    <?php 
    // フッターの表示
    require_once __DIR__ . '/../../app/views/layouts/footer.php';
    renderFooter('search'); 
    ?>
</body>
