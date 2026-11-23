<?php
// CSRF対策

require_once __DIR__ . '/auth.php';

// 共通のCSRFトークンを取得。まだなければ作成する
function getCsrfToken(): string
{
    if (
        !isset($_SESSION['csrf_token'])
        || !is_string($_SESSION['csrf_token'])
        || $_SESSION['csrf_token'] === ''
    ) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// フォームから送られたトークンが正しいか確認
function verifyCsrfToken($postedToken): bool
{
    $savedToken = $_SESSION['csrf_token'] ?? null;
    if (
        !is_string($savedToken)
        || $savedToken === ''
        || !is_string($postedToken)
        || $postedToken === ''
    ) {
        return false;
    }

    return hash_equals($savedToken, $postedToken);
}