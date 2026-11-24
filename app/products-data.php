<?php
// 商品一覧画面で使用するデータ
// 以前の読み込み方でも使えるように、共通処理の結果を従来の変数へ渡す。
require_once __DIR__ . '/products/search.php';

// 並び替えの選択肢
$sortKeys = ['newest', 'oldest', 'stock_desc', 'stock_asc'];
$sortOptions = array_intersect_key(getProductSortOptions(), array_flip($sortKeys));
// 絞り込みのカテゴリ選択肢
$categories = getProductCategories();

// 検索・並び替え・絞り込み条件を受け取る。
// 検索条件と、SQLに渡す値を準備
// 条件に一致する商品の総件数を取得
// 総ページ数・現在のページ・取得開始位置を計算
// 商品詳細の在庫を合計して、商品ごとの総在庫数を取得
// 上記の処理は共通のsearchProductsにまとめている。
$result = searchProducts($pdo, $_GET, ['sorts' => $sortKeys]);
$keyword = $result['keyword'];
$sort = $result['sort'];
$category = $result['category'];
// 表示件数
$perPage = $result['perPage'];
$totalCount = $result['totalCount'];
$totalPages = $result['totalPages'];
$page = $result['page'];
$products = $result['products'];

// ページ移動時も検索条件と表示件数を引き継ぎ
$pageUrl = static function (int $targetPage) use (
    $keyword,
    $sort,
    $category,
    $perPage
): string {
    return 'products.php?' . http_build_query([
        'keyword' => $keyword,
        'sort' => $sort,
        'category' => $category,
        'per_page' => $perPage,
        'page' => $targetPage,
    ]);
};

// ページ番号は最大5個表示
$startPage = max(1, $page - 2);
$endPage = min($totalPages, $startPage + 4);
$startPage = max(1, $endPage - 4);
