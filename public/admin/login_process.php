<?php
session_start();

require_once __DIR__ . '/../../app/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "
SELECT
    admin_id,
    name,
    email,
    password_hash
FROM admins
WHERE email = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);

$member = $stmt->fetch();

if (
    $member &&
    password_verify(
        $password,
        $member['password_hash']
    )
) {

    $_SESSION['admin_id'] = $member['admin_id'];
    $_SESSION['name'] = $member['name'];

    header('Location: ../index.php');
    exit();

} else {

    $_SESSION['login_error'] =
        'メールアドレスまたはパスワードが違います';

    header('Location: login.php');
    exit();
}