<!-- U11 商品一覧画面 -->
<!-- 実装する機能：カテゴリ別商品一覧、並び替え、カテゴリ・価格での絞り込み、ページ移動、商品詳細への遷移、お気に入り登録・解除。U17相当の操作は検索画面と仕様を揃える。 -->
<?php
require_once __DIR__ . '/../../app/db.php';
require_once __DIR__ . '/../../app/products/search.php';

// 表示する共通部品を読み込む(並べ替え・絞り込み・ページ切り替え)
require_once __DIR__ . '/../../app/views/components/list/sort.php';
require_once __DIR__ . '/../../app/views/components/list/filter.php';
require_once __DIR__ . '/../../app/views/components/list/pagination.php';
// 選択条件引継ぎの共通処理を読み込む
require_once __DIR__ . '/../../app/views/components/list/helpers.php';

// 検索条件に合う商品を、1ページ4件(仮)で取得
$result = searchProducts($pdo, $_GET, [
    'sorts' => ['newest','oldest','recommended'],
    'perPages' => [4],
    'defaultPerPage' => 4,
    'priceFilter' =>true
]);

// この画面で使う並べ替え。
$sortOptions = [
    'newest' => '新しい順',
    'oldest' => '古い順',
    'recommended' => 'おすすめ順',
];
// カテゴリの絞り込み項目。
$categoryOptions = ['' => 'すべて'];
foreach (getProductCategories() as $item) {
    $categoryOptions[$item] = $item;
}
$filterGroups = [
    [
        'name' => 'category',
        'label' => 'カテゴリ',
        'type' => 'radio',
        'options' => $categoryOptions,
        'selected' => $result['category'],
        'default' => '',
    ],
];

// カテゴリの下に価格の絞り込みを追加する。
$filterGroups[] = [
    'name' => 'price',
    'label' => '価格',
    'type' => 'price',
    'min' => $result['minPrice'],
    'max' => $result['maxPrice'],
];

$products = $result['products'];
$totalCount = $result['totalCount'];
$page = $result['page'];
$totalPages = $result['totalPages'];

// ページ移動時に検索条件引継ぎ
$pageUrl = createListPageUrl('products.php', [
    'keyword' => $result['keyword'],
    'category' => $result['category'],
    'sort' => $result['sort'],
    'per_page' => $result['perPage'],
    'min_price' => $result['minPrice'],
    'max_price' => $result['maxPrice'],
]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/common-list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/common-list.js" defer></script>
    <title>商品一覧画面</title>
</head>
<body class="has-header has-footer">
    <?php 
    require __DIR__ . '/../../app/views/layouts/header.php';
    renderHeader('','back','index.php');
    ?>

    <main class="c-list-page">
        <!-- 並べ替え -->
        <form action="products.php" method="get" class="c-list-form" data-list-form>
            <!-- 検索文字と表示件数を引き継ぐ -->
            <input type="hidden" name="keyword"
                value="<?= htmlspecialchars($result['keyword'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="per_page" value="<?= $result['perPage'] ?>">
            <div class="c-list-controls">
                <?php
                renderListSort($sortOptions, $result['sort']);
                renderListFilter($filterGroups);
                ?>
    </div>
        </form>

        <p>全 <?= number_format($totalCount) ?> 件</p>

        <!-- 商品表示(仮) -->
        <?php foreach ($products as $product): ?>
        <div>
            <p>
                <?= htmlspecialchars(
                    $product['product_name'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

            <p>
                ¥<?= number_format($product['product_price']) ?>
            </p>
        </div>
        <?php endforeach; ?>

        <!-- ページ切り替え -->
        <?php
        renderListPagination($page, $totalPages, $pageUrl);
        ?>
    </main>
    

    <?php 
    // フッターの表示
    require_once __DIR__ . '/../../app/views/layouts/footer.php';
    renderFooter(''); 
    ?>
</body>
</html>
