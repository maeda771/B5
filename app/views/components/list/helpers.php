<?php
// 受け取った文字を安全に表示するためにエスケープ
function listEscape($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// 検索条件を引き継いで、ページ移動用のURLを作成
function createListPageUrl(string $path, array $conditions): callable
{
    return static function (int $page) use ($path, $conditions): string {
        $conditions['page'] = $page;

        return $path . '?' . http_build_query($conditions);
    };
}
