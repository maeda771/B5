<?php
require_once __DIR__ . '/options.php';

function normalizeProductConditions(array $input, array $settings = []): array
{
    $sortOptions = getProductSortOptions();
    $allowedSorts = $settings['sorts'] ?? array_keys($sortOptions);
    $allowedSorts = array_values(array_intersect($allowedSorts, array_keys($sortOptions)));
    if (!$allowedSorts) $allowedSorts = ['newest'];
    $sort = is_string($input['sort'] ?? null) ? $input['sort'] : '';
    if (!in_array($sort, $allowedSorts, true)) $sort = $allowedSorts[0];

    $keyword = is_string($input['keyword'] ?? null) ? trim($input['keyword']) : '';
    $category = is_string($input['category'] ?? null) ? $input['category'] : '';
    if (!in_array($category, getProductCategories(), true)) $category = '';

    // 1ページの件数は画面側で選ぶ
    $perPages = $settings['perPages'] ?? [5, 8];
    $perPages = array_values(array_filter($perPages, static function ($count) {
        return is_int($count) && $count >= 1 && $count <= 100;
    }));
    if (!$perPages) $perPages = [8];
    $defaultCount = $settings['defaultPerPage'] ?? 8;
    if (!in_array($defaultCount, $perPages, true)) $defaultCount = $perPages[0];
    $requested = is_scalar($input['per_page'] ?? null)
        ? filter_var($input['per_page'], FILTER_VALIDATE_INT) : false;
    $perPage = in_array($requested, $perPages, true) ? $requested : $defaultCount;
    $requestedPage = is_scalar($input['page'] ?? null)
        ? filter_var($input['page'], FILTER_VALIDATE_INT) : false;
        // 価格絞り込みを使う画面だけ、下限・上限を受け取る
    $minPrice = null;
    $maxPrice = null;

    // 価格の絞り込み
    if ($settings['priceFilter'] ?? false) {
        $prices = [];

        foreach (['min_price' => 0, 'max_price' => 100000] as $key => $default) {
            $value = $input[$key] ?? null;

            $number = is_scalar($value)
                ? filter_var($value, FILTER_VALIDATE_INT)
                : false;

            $prices[$key] = $number === false
                ? $default
                : max(0, min(100000, $number));
        }

        // 下限と上限が逆の場合は入れ替え
        $minPrice = min($prices['min_price'], $prices['max_price']);
        $maxPrice = max($prices['min_price'], $prices['max_price']);
    }
    return [
        'keyword' => $keyword, 'sort' => $sort, 'category' => $category,
        'perPage' => $perPage, 'page' => max(1, $requestedPage ?: 1),
        'minPrice' => $minPrice,'maxPrice' => $maxPrice,
    ];
}
