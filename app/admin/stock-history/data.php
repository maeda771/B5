<?php
// 在庫変動履歴の取得と、絞り込み項目の準備

// フォームから送られた選択値を受け取り
$filterColor = is_string($_GET['color_id'] ?? null) ? $_GET['color_id'] : '';
$filterSize = is_string($_GET['size'] ?? null) ? $_GET['size'] : '';
$filterType = is_string($_GET['change_type'] ?? null) ? $_GET['change_type'] : '';
$filterYear = is_string($_GET['year'] ?? null) ? $_GET['year'] : '';
$filterMonth = is_string($_GET['month'] ?? null) ? $_GET['month'] : '';

// 対象の商品番号を検索条件へ
$conditions = ['product_details.product_id = ?'];
$params = [$productId];
// カラーの条件追加
if ($filterColor !== '') {
    $conditions[] = 'product_details.color_id = ?';
    $params[] = $filterColor;
}
// サイズの条件追加
if ($filterSize !== '') {
    $conditions[] = 'product_details.size = ?';
    $params[] = $filterSize;
}
// 変動区分の条件追加
if ($filterType !== '') {
    $conditions[] = 'inventory_change_logs.change_type = ?';
    $params[] = $filterType;
}
// 年の条件追加
if ($filterYear !== '') {
    $conditions[] = 'YEAR(inventory_change_logs.created_at) = ?';
    $params[] = $filterYear;
}
// 月の条件追加
if ($filterMonth !== '') {
    $conditions[] = 'MONTH(inventory_change_logs.created_at) = ?';
    $params[] = $filterMonth;
}
// 対象商品の履歴を取得
$stmt = $pdo->prepare('
    SELECT
        inventory_change_logs.history_id,
        inventory_change_logs.change_type,
        inventory_change_logs.quantity_before,
        inventory_change_logs.quantity_after,
        inventory_change_logs.quantity_change,
        inventory_change_logs.memo,
        inventory_change_logs.order_id,
        inventory_change_logs.created_at,
        product_details.color_id,
        product_details.size,
        colors.color_name,
        colors.color_code
    FROM inventory_change_logs
    INNER JOIN product_details
        ON product_details.product_details_id
            = inventory_change_logs.product_details_id
    INNER JOIN colors
        ON colors.color_id = product_details.color_id
    WHERE ' . implode(' AND ', $conditions) . '
    ORDER BY
        inventory_change_logs.created_at DESC,
        inventory_change_logs.history_id DESC
');
$stmt->execute($params);

// 取得した履歴を配列に入れる
$histories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 色・サイズの選択肢は、共通処理から作る
$variants = getProductVariants($pdo, $productId);
$stockColors = groupProductVariantsByColor($variants);
$stockSizes = getProductSizes($variants);

$colorOptions = ['' => 'すべて'];
foreach ($stockColors as $colorId => $color) {
    $colorOptions[$colorId] = $color['name'];
}
$sizeOptions = ['' => 'すべて'];
foreach ($stockSizes as $size) {
    $sizeOptions[$size] = $size;
}
// 履歴がある年を、選択肢として取得
$stmt = $pdo->prepare('
    SELECT DISTINCT YEAR(inventory_change_logs.created_at)
    FROM inventory_change_logs
    INNER JOIN product_details
        ON product_details.product_details_id
            = inventory_change_logs.product_details_id
    WHERE product_details.product_id = ?
    ORDER BY YEAR(inventory_change_logs.created_at) DESC
');
$stmt->execute([$productId]);
$historyYears = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 共通の絞り込み部品に渡す設定。
$filterGroups = [
    [
        'name' => 'color_id',
        'label' => 'カラー',
        'type' => 'radio',
        'options' => $colorOptions,
        'selected' => $filterColor,
        'default' => '',
    ],
    [
        'name' => 'size',
        'label' => 'サイズ',
        'type' => 'radio',
        'options' => $sizeOptions,
        'selected' => $filterSize,
        'default' => '',
    ],
    [
        'name' => 'change_type',
        'label' => '変動区分',
        'type' => 'radio',
        'options' => [
            '' => 'すべて',
            '入荷' => '入荷',
            '調整' => '在庫調整',
            '購入' => '商品購入',
        ],
        'selected' => $filterType,
        'default' => '',
    ],
    [
        'name' => 'changed_at',
        'label' => '変動日',
        'type' => 'year_month',
        'years' => $historyYears,
        'selected' => [
            'year' => $filterYear,
            'month' => $filterMonth,
        ],
    ],
];