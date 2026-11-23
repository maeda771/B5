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

// 共通のCSRF処理を読み込む
require_once __DIR__ . '/../../csrf.php';
// 正しいトークン
$token = getCsrfToken();
// POSTで送信されたリクエストかどうか
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
// 商品登録かカラー追加かを保持
$action = is_string($_POST['action'] ?? null)
    ? $_POST['action']
    : '';
// フォームから送られたトークンが一致しているか
$tokenMatches = verifyCsrfToken($_POST['token'] ?? null);

// DBからカラー一覧を取得
$colors = $pdo->query('SELECT color_id, color_name, color_code FROM colors ORDER BY color_id')->fetchAll(PDO::FETCH_ASSOC);
