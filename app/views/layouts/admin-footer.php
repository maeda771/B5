<!-- 管理者用フッター。A02商品管理・A10注文管理・A12会員管理へのナビ。管理者認証は各画面側で確認する。 -->
<?php
// 画面名を引数で受け取る
function renderFooter(string $current): void
{
require_once __DIR__ . '/../../auth.php';
?>

<!-- 管理者用フッター -->
<footer class="footer">
    <nav>
        <a class="<?= $current === 'products' ? 'is-current' : '' ?>" href="/B5/public/admin/products.php">
            <!-- 現在の画面であればスタイルを変更 -->
            <i class="fas fa-tshirt"></i>
            <span>商品管理</span>
        </a>
        <a class="<?= $current === 'orders' ? 'is-current' : '' ?>" href="/B5/public/admin/orders.php">
            <i class="fas fa-clipboard-list"></i>
            <span>注文管理</span>
        </a>

        <a class="<?= $current === 'members' ? 'is-current' : '' ?>" href="/B5/public/admin/members.php">
            <i class="fas fa-users"></i>
            <span>会員管理</span>
        </a>
    </nav>
</footer>    
<?php
}
?>