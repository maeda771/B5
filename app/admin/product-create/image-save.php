<?php
// 確認済みの画像を保存し、$imagePathと$savedImageに保存先を入れる
$imagePath = null;
if ($imageExtension !== null) {
    $directory = __DIR__ . '/../../../public/images/products';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('画像フォルダを作成できませんでした。');
    }
    $imagePath = 'images/products/' . bin2hex(random_bytes(16)) . '.' . $imageExtension;
    $savedImage = __DIR__ . '/../../../public/' . $imagePath;
    if (!move_uploaded_file($image['tmp_name'], $savedImage)) {
        throw new RuntimeException('画像を保存できませんでした。');
    }
}
