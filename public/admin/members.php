<!-- A12 会員一覧画面 -->
<!-- 実装する機能：会員一覧、会員番号・氏名検索、登録日の並び替えと絞り込み、A13への遷移。 -->

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
$list = require __DIR__ . '/../../app/admin/members-data.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/common-list.css">
    <script src="../js/common-list.js" defer></script>
    <title>会員一覧画面</title>
</head>
<body class="has-header has-footer">
    <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('会員一覧');
     ?>

     <main class="c-list-page" data-list-page
          data-per-page="<?= $list['perPage'] ?>"
          data-portrait-count="8" data-landscape-count="5">
        <!-- 共通部品：検索・並び替え・絞り込み -->
        <!-- 検索・並び替え・絞り込みをまとめて送信するフォーム -->
        <form action="members.php"
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
                '会員番号・氏名で検索',
                'keyword',
                '会員番号・氏名で検索'
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

        <!-- 共通部品：会員用の列とデータを渡す -->
        <?php renderListTable($list['columns'], $list['members'], $list['table']); ?>
        
        <!-- 共通部品：ページ切り替え -->
        <?php renderListPagination($list['page'], $list['totalPages'], $list['pageUrl']); ?>


     
     </main>


    <?php 
    // フッターの表示
    require_once __DIR__ . '/../../app/views/layouts/admin-footer.php';
    renderFooter('members'); 
    ?>
</body>
</html>