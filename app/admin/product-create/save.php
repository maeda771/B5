<?php
// 商品情報をDBへ登録
$savedImage = null;
try {
    $pdo->beginTransaction();
    // 画像の保存は別ファイルで実行
    require __DIR__ . '/image-save.php';
    // 商品テーブルへ保存
    $insertProduct = $pdo->prepare('INSERT INTO products (product_name, category, product_price, description, image, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_DATE)');
    $insertProduct->execute([$productName, $category, (int) $price, $description, $imagePath]);
    $productId = (int) $pdo->lastInsertId();

    // 商品詳細へカラーとサイズの組み合わせごとに作成
    $insertDetail = $pdo->prepare('INSERT INTO product_details (product_id, color_id, size, stock_quantity) VALUES (?, ?, ?, 0)');
    foreach ($selectedColors as $colorId) {
        foreach ($selectedSizes as $size) {
            $insertDetail->execute([$productId, (int) $colorId, $size]);
        }
    }
    $pdo->commit();
    // 保存が完了すれば一覧画面へ遷移
    header('Location: products.php');
    exit;
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    if ($savedImage !== null && is_file($savedImage)) {
        unlink($savedImage);
    }
    error_log((string) $exception);
    $errors[] = '商品を登録できませんでした。もう一度お試しください。';
}
