<!-- A01 管理者ログイン画面 -->
<!-- 実装する機能：管理者認証、パスワード表示切替、エラー表示、成功後A02への遷移。 -->
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>


    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="login-container">

    <h1>管理者ログイン</h1>
    <div class="title-line"></div>

    <form action="login_process.php" method="post">

        <div class="form-group">
            <label class="form-label">
                メールアドレス
                <span class="required">※必須</span>
            </label>

            <input class="form-input"
                type="email"
                name="email"
                placeholder="メールアドレスを入力"
                required>
        </div>

        <div class="form-group">
            <label class="form-label">
                パスワード
                <span class="required">※必須</span>
            </label>

            <div class="password-area">
                <input
                    class="form-input"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="パスワードを入力"
                    required>

                <button
                    type="button"
                    id="eyeBtn"
                    class="eye-btn"
                    onclick="togglePassword()">
                <i id="eyeIcon" class="fas fa-eye-slash"></i>
                </button>            
            </div>
        </div>
        <div class="error-area">
            <?php if (!empty($_SESSION['login_error'])) : ?>
            <p class="form-error">
                <?= htmlspecialchars($_SESSION['login_error']) ?>
            </p>
            <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>
        </div>

        <button type="submit" class="button">
            ログイン
        </button>

    </form>

</div>
<script>
    function togglePassword() {

        const password = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (password.type === "password") {

            password.type = "text";

            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");

        } else {

            password.type = "password";

            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        }
    }
</script>
</body>
</html>