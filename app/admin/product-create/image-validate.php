<?php
// 商品画像のチェック
$image = $_FILES['image'] ?? null;
$imageExtension = null;
if ($image !== null && (!is_array($image) || is_array($image['error'] ?? null))) {
    $errors[] = '画像の送信形式が正しくありません。';
} elseif (is_array($image) && ($image['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    if (($image['error'] ?? null) !== UPLOAD_ERR_OK) {
        $errors[] = '画像をアップロードできませんでした。容量を確認してください。';
    } elseif (($image['size'] ?? 0) > 5 * 1024 * 1024) {
        $errors[] = '画像は5MB以下にしてください。';
    } elseif (!is_uploaded_file($image['tmp_name'])) {
        $errors[] = '画像を選び直してください。';
    } else {
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($image['tmp_name']);
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $imageExtension = $extensions[$mime] ?? null;
        if ($imageExtension === null || @getimagesize($image['tmp_name']) === false) {
            $errors[] = '画像はJPEG・PNG・WebP形式を選択してください。';
        }
    }
}
