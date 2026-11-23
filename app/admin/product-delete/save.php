<?php
// 論理削除を行う
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$pdo->beginTransaction();
// 編集・別の削除と競合しないよう、対象の商品行をロックする。
$lock = $pdo->prepare("SELECT product_id FROM products
    WHERE product_id IN ($placeholders) AND is_deleted = FALSE
    ORDER BY product_id FOR UPDATE");
$lock->execute($productIds);
if (count($lock->fetchAll(PDO::FETCH_COLUMN)) !== count($productIds)) {
    throw new RuntimeException('削除済み、または見つからない商品があります。画面を開き直してください。');
}
$softDelete = $pdo->prepare("UPDATE products SET is_deleted = TRUE
    WHERE product_id IN ($placeholders) AND is_deleted = FALSE");
$softDelete->execute($productIds);
$pdo->commit();
$_SESSION['product_delete_result'] = [
    'type' => 'success',
    'message' => count($productIds) . '件の商品を削除しました。',
];
