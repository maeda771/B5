<!-- A03 商品削除確認モーダル -->
<dialog id="product-delete-dialog" class="product-delete-dialog" aria-labelledby="product-delete-title">
    <h2 id="product-delete-title">この商品を削除しますか？</h2>
    <p>削除すると、商品一覧に表示されなくなります。<br>過去の注文履歴は残ります。</p>
    <form id="product-delete-form" action="product-delete.php" method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($deleteToken, ENT_QUOTES, 'UTF-8') ?>">
        <?php foreach (['keyword', 'sort', 'category', 'page', 'per_page'] as $key): ?>
            <?php if (is_string($_GET[$key] ?? null)): ?>
                <input type="hidden" name="return_<?= $key ?>" value="<?= htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8') ?>">
            <?php endif; ?>
        <?php endforeach; ?>
        <div id="product-delete-ids"></div>
        <div class="product-delete-actions">
            <button type="button" id="product-delete-cancel" class="common-button product-delete-cancel">キャンセル</button>
            <button type="submit" id="product-delete-confirm" class="common-button product-delete-confirm">削除する</button>
        </div>
    </form>
</dialog>
