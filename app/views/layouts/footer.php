<!-- 利用者用フッター。U10ホーム・U16検索・U18お気に入り・U13カート・U19マイページへのナビ。後者3画面は未ログインならU09へ案内する。アクセス制御は各画面側でも行う。 -->
<?php
// 画面名を引数で受け取る
function renderFooter(string $current): void
{
require_once __DIR__ . '/../../auth.php';

// ログイン状態の確認
$isLoggedIn = getMemberId() !== false;

// ログインしていない場合はログイン画面に遷移
$loginUrl = '/B5/public/user/account/login.php';
?>

<!-- 利用者用フッター -->
<footer class="footer">
    <nav>
        <a class="<?= $current === 'home' ? 'is-current' : '' ?>" href="/B5/public/user/index.php">
            <!-- 現在の画面であればスタイルを変更 -->
            <i class="fas fa-home"></i>
            <span>ホーム</span>
        </a>
        <a class="<?= $current === 'search' ? 'is-current' : '' ?>" href="/B5/public/user/search.php">
            <i class="fas fa-search"></i>
            <span>検索</span>
        </a>

        <a class="<?= $current === 'favorites' ? 'is-current' : '' ?>" href="<?= $isLoggedIn
            ? '/B5/public/user/favorites.php'
            : $loginUrl ?>">
            <i class="fas fa-heart"></i>
            <span>お気に入り</span>
        </a>

        <a class="<?= $current === 'cart' ? 'is-current' : '' ?>" href="<?= $isLoggedIn
            ? '/B5/public/user/shopping/cart.php'
            : $loginUrl ?>">
            <i class="fas fa-shopping-cart"></i>
            <span>カート</span>
        </a>

        <a class="<?= $current === 'mypage' ? 'is-current' : '' ?>" href="<?= $isLoggedIn
            ? '/B5/public/user/account/index.php'
            : $loginUrl ?>">
            <i class="fas fa-user"></i>
            <span>マイページ</span>
        </a>
    </nav>
</footer>    
<?php
}
?>