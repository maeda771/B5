<!-- A10 注文履歴画面 -->
<!-- 実装する機能：注文一覧、注文番号・会員番号検索、注文日の並び替え、状態・注文年月での絞り込み、A11への遷移。 -->

<?php
require_once __DIR__ . '/../../app/auth.php';
/*
ログインしていなければログイン画面へ
if (getAdminId() === false) {
    header('Location: /B5/public/admin/login.php');
    exit;
}
*/
require_once __DIR__ . '/../../app/db.php';

// 使用する共通部品を読み込む。
// 検索欄
require_once __DIR__ . '/../../app/views/components/list/search.php';
// 並べ替え
require_once __DIR__ . '/../../app/views/components/list/sort.php';
// 絞り込み
require_once __DIR__ . '/../../app/views/components/list/filter.php';
// 一覧表示
require_once __DIR__ . '/../../app/views/components/list/table.php';
// ページ切り替え
require_once __DIR__ . '/../../app/views/components/list/pagination.php';

// 注文履歴一覧と総件数取得
$list = require __DIR__ . '/../../app/admin/orders-data.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/common-list.css">
    <script src="../js/common-list.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>注文履歴画面</title>
</head>
<body class="has-header has-footer">
    <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('注文履歴');
     ?>

     <main class="admin-products c-list-page" data-list-page
          data-per-page="<?= $list['perPage'] ?>"
          data-portrait-count="8" data-landscape-count="5">
        <!-- 共通部品：検索・並び替え・絞り込み -->
        <!-- 検索・並び替え・絞り込みをまとめて送信するフォーム -->
        <form action="orders.php"
            method="get"
            class="c-list-form"
            data-list-form
        >

            <!-- 縦長8件・横長5件の設定を引き継ぐ -->
            <input type="hidden" name="per_page" value="<?= $list['perPage'] ?>">

            <!-- 共通部品：検索欄 -->
            <?php
            renderListSearch(
                $list['controls']['search']['value'],
                '注文番号・会員番号で検索',
                'keyword',
                '注文番号・会員番号で検索'
            );
            ?>

            <div class="c-list-controls">
                <!-- 共通部品：並び替え -->
                <?php
                renderListSort(
                    $list['controls']['sort']['options'],
                    $list['controls']['sort']['selected']
                );
                ?>

                <!-- 共通部品：絞り込み -->
                <?php
                renderListFilter(
                    $list['controls']['filters']
                );
                ?>
            </div>

            <!-- Enterで検索した際の送信ボタン -->
            <button type="submit" class="c-list-visually-hidden">
                検索を実行
            </button>
        </form>

        <!-- 商品一覧固有：件数・新規登録ボタン -->
        <div class="products-toolbar">
            <p>全 <?= number_format($list['totalCount']) ?> 件</p>
        </div>

        <!-- 共通部品：商品一覧用の列とデータを渡す -->
        <?php renderListTable($list['columns'], $list['orders'], $list['table']); ?>
        
        <!-- 共通部品：ページ切り替え -->
        <?php renderListPagination($list['page'], $list['totalPages'], $list['pageUrl']); ?>
    </main>


    <?php 
    // フッターの表示
    require_once __DIR__ . '/../../app/views/layouts/admin-footer.php';
    renderFooter('orders'); 
    ?>
</body>
</html>