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

// 一覧表
require_once __DIR__ . '/../../app/views/components/list/table.php';

// ページ切り替え
require_once __DIR__ . '/../../app/views/components/list/pagination.php';
// 商品固有のデータを準備する。共通部品へ渡す設定も含まれる。
$list = require __DIR__ . '/../../app/admin/products-data.php';
?>
<!-- A02 商品一覧画面 -->
<!-- A03 商品削除確認画面（削除処理は未実装） -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/common-list.css">
    <link rel="stylesheet" href="../css/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/common-list.js" defer></script>
    <title>商品一覧画面</title>
</head>
<body class="has-header has-footer">
    <?php
    require_once __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('商品一覧');
    ?>
    <main class="admin-products c-list-page" data-list-page
          data-per-page="<?= $list['perPage'] ?>"
          data-portrait-count="8" data-landscape-count="5">
        <!-- 共通部品：検索・並び替え・絞り込み -->
        <!-- 検索・並び替え・絞り込みをまとめて送信するフォーム -->
        <form action="products.php"
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
                '商品番号・商品名で検索',
                'keyword',
                '商品名・商品番号で検索'
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
            <a href="product-create.php" class="products-create">
                <i class="fas fa-plus"></i>
                新規登録
            </a>
        </div>

        <!-- 共通部品：商品一覧用の列とデータを渡す -->
        <?php renderListTable($list['columns'], $list['products'], $list['table']); ?>

        <!-- 商品一覧固有：削除ボタン。削除処理はまだ未実装 -->
        <div class="products-delete-area">
            <button type="button" class="products-delete"
                    title="削除処理は未実装です" disabled
            >
                <i class="far fa-trash-alt"></i>
            </button>
        </div>

        <!-- A03の削除確認モーダルを実装するときは、この画面に記述する -->

        <!-- 共通部品：ページ切り替え -->
        <?php renderListPagination($list['page'], $list['totalPages'], $list['pageUrl']); ?>
    </main>
    <?php
    require_once __DIR__ . '/../../app/views/layouts/admin-footer.php';
    renderFooter('products');
    ?>
</body>
</html>
