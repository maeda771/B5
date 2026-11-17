<?php
session_start();

require_once __DIR__ . '/../../../app/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "
SELECT
    member_id,
    member_name,
    email,
    password_hash
FROM members
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

    $_SESSION['member_id'] = $member['member_id'];
    $_SESSION['member_name'] = $member['member_name'];

    header('Location: ../index.php');
    exit();

} else {

    $_SESSION['login_error'] =
        'メールアドレスまたはパスワードが違います';

    header('Location: login.php');
    exit();
}