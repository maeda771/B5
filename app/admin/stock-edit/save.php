<?php
try {
    // 在庫変更により、商品詳細の履歴更新と変動履歴のデータ追加を行う

    $pdo->beginTransaction();
    // 保存直前の在庫数を取得
    $stmt = $pdo->prepare('
        SELECT product_details.stock_quantity
        FROM product_details
        INNER JOIN products
            ON products.product_id = product_details.product_id
        WHERE product_details.product_details_id = ?
          AND product_details.product_id = ?
          AND products.is_deleted = FALSE
        FOR UPDATE
    ');
    $stmt->execute([$productDetailsId, $productId]);
    $stockBefore = $stmt->fetchColumn();
    if ($stockBefore === false) {
        throw new RuntimeException('対象の商品が見つかりません。');
    }

    $stockBefore = (int) $stockBefore;
    // 現在の在庫に、入力された増減数量を加える
    $stockAfter = $stockBefore + $quantityChange;
    if ($stockAfter < 0) {
        throw new RuntimeException('在庫が不足しています。数量を確認してください。');
    }

    // 対象の色・サイズの在庫を更新
    $stmt = $pdo->prepare('
        UPDATE product_details
        SET stock_quantity = ?
        WHERE product_details_id = ?
    ');
    $stmt->execute([$stockAfter, $productDetailsId]);

    // 変動履歴の作成
    $stmt = $pdo->prepare('
        INSERT INTO inventory_change_logs (
            product_details_id,
            change_type,
            quantity_before,
            quantity_after,
            quantity_change,
            memo,
            created_at
        )
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ');
    $stmt->execute([
        $productDetailsId,
        $reason,
        $stockBefore,
        $stockAfter,
        $quantityChange,
        $memo === '' ? null : $memo,
    ]);
    $pdo->commit();
    // 保存成功後は、確認番号を使えなくする
unset($_SESSION['stock_edit_requests'][$productId]);

    // 同じ商品の在庫管理画面へ戻る
    header('Location: stock.php?product_id=' . $productId, true, 303);
    exit;

} catch (Throwable $exception) {
    // 途中で失敗した場合は、在庫更新も取り消す。
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if ($exception instanceof PDOException) {
        error_log((string) $exception);
        $errors[] = '保存できませんでした。もう一度お試しください。';
    } else {
        $errors[] = $exception->getMessage();
    }
}