<?php

/**
 * DB接続用ファイル
 * DB操作を行うファイルでは先頭で読み込みを行う
*/

$host = 'localhost';
$dbname = 'b5';
$user = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('DB接続失敗：MySQLの起動と接続設定を確認してください。');
}

// このファイルをブラウザで直接開いたときだけ表示する。
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    echo 'DB接続成功';
}
