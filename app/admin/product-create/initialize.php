<?php
// 商品登録画面フォームの初期値と選択肢を用意し、DBからカラーを取得
$categories = ['トップス', 'ボトムス', 'アウター', 'シューズ', 'その他・小物'];
$sizes = ['S', 'M', 'L', 'LL', 'F'];
$errors = [];
$productName = '';
$category = '';
$price = '';
$description = '';
$selectedColors = [];
$selectedSizes = [];

// CSRF対策、トークンがなければ作成し、セッションに保存する
if (empty($_SESSION['product_create_token'])) {
    $_SESSION['product_create_token'] = bin2hex(random_bytes(32));
}
// 正しいトークン
$token = $_SESSION['product_create_token'];

// POSTで送信されたリクエストかどうか
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
// 商品登録かカラー追加かを保持
$action = is_string($_POST['action'] ?? null) ? $_POST['action'] : '';
// フォームから送られてきたトークン
$postedToken = is_string($_POST['token'] ?? null) ? $_POST['token'] : '';
// トークンが一致しているか
$tokenMatches = hash_equals($token, $postedToken);

// DBからカラー一覧を取得
$colors = $pdo->query('SELECT color_id, color_name, color_code FROM colors ORDER BY color_id')->fetchAll(PDO::FETCH_ASSOC);
