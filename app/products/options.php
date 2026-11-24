<?php
// 商品を扱う画面で共通のカテゴリ。
function getProductCategories(): array
{
    return ['トップス', 'ボトムス', 'アウター', 'シューズ', 'その他・小物'];
}

// 各画面は、ここから必要な並び替えだけ選んで使う
function getProductSortOptions(): array
{
    return [
        'newest' => '新しい順', 'oldest' => '古い順',
        'recommended' => 'おすすめ順',
        'stock_desc' => '在庫数が多い順', 'stock_asc' => '在庫数が少ない順',
    ];
}
