<?php
// 注文詳細画面の状態変更を管理

if (!verifyCsrfToken($_POST['token'] ?? null)) {
    $errors[] = '画面を開き直してから変更してください。';
}
$status = is_string($_POST['status'] ?? null)
    ? $_POST['status']
    : '';

if (!in_array($status, ['注文受付', '発送準備中', '発送済み'], true)) {
    $errors[] = '状態を選択してください。';
}
if ($errors) {
    return;
}
try {
    // 注文状態を更新
    $stmt = $pdo->prepare('
        UPDATE orders
        SET order_status = ?
        WHERE order_id = ?
    ');
    $stmt->execute([$status, $orderId]);
} catch (PDOException $exception) {
    error_log((string) $exception);
    $errors[] = '状態を変更できませんでした。もう一度お試しください。';
    return;
}

// 状態変更後も、一覧の検索条件を引き継ぐ
$detailParams = ['order_id' => $orderId];

foreach ($returnParams as $key => $value) {
    $detailParams['return_' . $key] = $value;
}

header('Location: order.php?' . http_build_query($detailParams), true, 303);
exit;