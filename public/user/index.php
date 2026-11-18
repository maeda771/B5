<!-- U10 ホーム画面 -->
<!-- 実装する機能：トップス・ボトムス・アウター・シューズ・その他小物のカテゴリからU11への案内、共通ナビ。 -->
<?php
session_start();

if (!isset($_SESSION["member_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ホーム</title>
</head>
<body>

<h1>ポチっと商店</h1>

<p>
    ようこそ
    <?= htmlspecialchars($_SESSION["member_name"]) ?>
    さん
</p>

</body>
</html>