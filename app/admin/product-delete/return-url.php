<?php
// 削除後に検索状態を引き継ぐ
$returnParams = [];
foreach (['keyword', 'sort', 'category', 'page', 'per_page'] as $key) {
    if (is_string($_POST['return_' . $key] ?? null)) {
        $returnParams[$key] = $_POST['return_' . $key];
    }
}
$returnUrl = 'products.php';
if ($returnParams) {
    $returnUrl .= '?' . http_build_query($returnParams);
}
