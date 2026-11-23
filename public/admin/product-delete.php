<?php
//　削除する商品のチェック・削除の実行
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/db.php';
/* ログインしていなければログイン画面
if (getAdminId() === false) {
    header('Location: /B5/public/admin/login.php');
    exit;
}
*/
require __DIR__ . '/../../app/admin/product-delete/return-url.php';
try {
    require __DIR__ . '/../../app/admin/product-delete/validate.php';
    require __DIR__ . '/../../app/admin/product-delete/save.php';
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log((string) $exception);
    // DBへの更新に失敗した場合は、選択した商品の更新をまとめて取り消す
    $message = $exception instanceof PDOException
        ? '商品を削除できませんでした。もう一度お試しください。'
        : $exception->getMessage();
    $_SESSION['product_delete_result'] = ['type' => 'error', 'message' => $message];
}
header('Location: ' . $returnUrl, true, 303);
exit;
