<?php
// 確認済みの$colorName・$colorCodeをDBに保存
$findColor = $pdo->prepare('SELECT color_id, color_name, color_code FROM colors WHERE color_name = ? AND color_code = ?');
$findColor->execute([$colorName, $colorCode]);
$newColor = $findColor->fetch(PDO::FETCH_ASSOC);
if (!$newColor) {
    try {
        $insertColor = $pdo->prepare('INSERT INTO colors (color_name, color_code) VALUES (?, ?)');
        $insertColor->execute([$colorName, $colorCode]);
    } catch (PDOException $exception) {
        if ((string) $exception->getCode() !== '23000') {
            throw $exception;
        }
    }
    $findColor->execute([$colorName, $colorCode]);
    $newColor = $findColor->fetch(PDO::FETCH_ASSOC);
    if (!$newColor) {
        throw new RuntimeException('追加したカラーを取得できませんでした。');
    }
}
