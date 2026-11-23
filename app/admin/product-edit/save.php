<?php
// 商品テーブルだけを更新
$savedImage = null;
try {
    $pdo->beginTransaction();
    $lock = $pdo->prepare('SELECT * FROM products WHERE product_id = ? AND is_deleted = FALSE FOR UPDATE');
    $lock->execute([$productId]);
    $current = $lock->fetch(PDO::FETCH_ASSOC);
    if (!$current || !hash_equals(productEditVersion($current), $postedVersion)) {
        throw new RuntimeException('商品が変更または削除されました。画面を開き直してください。');
    }

    $imagePath = $current['image'];
    if ($imageExtension !== null) {
        require __DIR__ . '/../product-create/image-save.php';
    }
    $update = $pdo->prepare('UPDATE products SET product_name = ?, category = ?,
        product_price = ?, description = ?, image = ? WHERE product_id = ? AND is_deleted = FALSE');
    $update->execute([$productName, $category, (int) $price, $description, $imagePath, $productId]);
    $pdo->commit();
    // 保存後は一覧へ
    header('Location: ' . $returnUrl, true, 303);
    exit;
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    if ($savedImage !== null && is_file($savedImage)) unlink($savedImage);
    error_log((string) $exception);
    $errors[] = $exception instanceof PDOException
        ? '商品を保存できませんでした。入力内容を確認してください。'
        : $exception->getMessage();
}
