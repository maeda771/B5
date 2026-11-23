<?php
// 共通のCSRF処理を読み込む
require_once __DIR__ . '/../../csrf.php';

// 削除フォームに渡す共通トークン
$deleteToken = getCsrfToken();

// 削除結果のメッセージを取得
$deleteResult = $_SESSION['product_delete_result'] ?? null;
unset($_SESSION['product_delete_result']);