<?php
// カラー追加リクエストの入力確認
header('Content-Type: application/json; charset=UTF-8');
// カラー名取得
$colorName = is_string($_POST['color_name'] ?? null) ? trim($_POST['color_name']) : '';
// カラーコード取得
$colorCode = is_string($_POST['color_code'] ?? null)
    ? strtoupper(ltrim(trim($_POST['color_code']), '#')) : '';
$message = '';
if (!$tokenMatches) {
    $message = '画面を開き直してから追加してください。';
} elseif ($colorName === '' || mb_strlen($colorName) > 20) {
    $message = 'カラー名は1〜20文字で入力してください。';
} elseif (!preg_match('/\A[0-9A-F]{6}\z/', $colorCode)) {
    $message = 'カラーコードは # と6桁の英数字で入力してください。';
}
if ($message !== '') {
    http_response_code(422);
    echo json_encode(['error' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}
try {
    require __DIR__ . '/color-save.php';
    echo json_encode(['color' => $newColor], JSON_UNESCAPED_UNICODE);
} catch (Throwable $exception) {
    error_log((string) $exception);
    http_response_code(500);
    echo json_encode(['error' => 'カラーを追加できませんでした。もう一度お試しください。'], JSON_UNESCAPED_UNICODE);
}
exit;
