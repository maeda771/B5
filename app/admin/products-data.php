<?php
/* 商品専用の検索条件・SQL
 * products.phpで接続した$pdoを使用し、結果を配列で返す。
 */
return (static function (PDO $pdo): array {
    require_once __DIR__ . '/../products/search.php';
    require_once __DIR__ . '/../views/components/list/helpers.php';
    $categories = getProductCategories();
    $sortKeys = ['newest', 'oldest', 'stock_desc', 'stock_asc'];
    $sortOptions = array_intersect_key(getProductSortOptions(), array_flip($sortKeys));

    // 縦長8件・横長5件。初回の画面の向きはJSから送られる。
    $result = searchProducts($pdo, $_GET, ['sorts' => $sortKeys]);
    $keyword = $result['keyword'];
    $sort = $result['sort'];
    $category = $result['category'];
    $perPage = $result['perPage'];
    $page = $result['page'];
    $totalCount = $result['totalCount'];
    $totalPages = $result['totalPages'];
    $products = $result['products'];

    // ページ移動時に引き継ぐ条件
    $pageUrl = createListPageUrl('products.php', [
        'keyword' => $keyword,
        'category' => $category,
        'sort' => $sort,
        'per_page' => $perPage,
    ]);
    $categoryOptions = ['' => 'すべて'];
    foreach ($categories as $item) $categoryOptions[$item] = $item;

    // 共通部品に渡す「設定」と「取得結果」。
    return [
        'perPage' => $perPage, 'totalCount' => $totalCount,
        'page' => $page, 'totalPages' => $totalPages, 'pageUrl' => $pageUrl,
        'products' => $products,
        'controls' => [
            'action' => 'products.php',
            'hidden' => ['per_page' => $perPage],
            'search' => ['value' => $keyword, 'placeholder' => '検索', 'label' => '商品名・商品番号で検索'],
            'sort' => ['options' => $sortOptions, 'selected' => $sort],
            'filters' => [[
                'name' => 'category', 'label' => 'カテゴリ', 'type' => 'radio',
                'options' => $categoryOptions, 'selected' => $category, 'default' => '',
            ]],
        ],
        'columns' => [
            ['key' => 'product_name', 'label' => '商品名', 'class' => 'products-col-name'],
            ['key' => 'product_id', 'label' => '商品番号', 'class' => 'products-col-id'],
            ['key' => 'product_price', 'label' => '価格', 'class' => 'products-col-price', 'format' => 'yen'],
            ['key' => 'total_stock', 'label' => '総在庫', 'class' => 'products-col-stock', 'format' => 'number'],
        ],
        'table' => [
            'idKey' => 'product_id', 'labelKey' => 'product_name',
            'checkbox' => 'product_ids',
            'detail' => static function (array $row) use (
                    $keyword, $sort, $category, $page, $perPage
            ): string {
                return 'product-edit.php?' . http_build_query([
                    'product_id' => (int) $row['product_id'],
                    'return_keyword' => $keyword,
                    'return_sort' => $sort,
                    'return_category' => $category,
                    'return_page' => $page,
                    'return_per_page' => $perPage,
                ]);
            },
            'empty' => '条件に一致する商品はありません。',
        ],
    ];
})($pdo);
