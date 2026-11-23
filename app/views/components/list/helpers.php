<?php
// 受け取った文字を安全に表示するためにエスケープ
function listEscape($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
