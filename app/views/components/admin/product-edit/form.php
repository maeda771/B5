<!-- A06で編集可能な項目 -->
<form id="product-edit-form" action="product-edit.php?product_id=<?= (int) $productId ?>" method="post" enctype="multipart/form-data">
    <?php foreach ($returnParams as $key => $value): ?>
    <input type="hidden"
        name="return_<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
        value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">
    <?php endforeach; ?>
    <input type="hidden" name="action" value="update_product">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="version" value="<?= htmlspecialchars($version, ENT_QUOTES, 'UTF-8') ?>">
    <?php require __DIR__ . '/image.php'; ?>
    <div class="form-group">
        <label class="form-label" for="product-edit-name">商品名</label>
        <input class="form-input" id="product-edit-name" type="text" name="product_name" maxlength="30" required
            value="<?= htmlspecialchars($productName, ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="form-group">
        <label class="form-label" for="product-edit-category">カテゴリ</label>
        <select class="form-input" id="product-edit-category" name="category" required>
            <?php foreach ($categories as $item): ?>
                <option value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" <?= $category === $item ? 'selected' : '' ?>>
                    <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label" for="product-edit-price">価格</label>
        <div class="product-edit-price">
            <span>¥</span>
            <input class="form-input" id="product-edit-price" type="number" name="product_price" min="1" step="1" inputmode="numeric" required
                value="<?= htmlspecialchars($price, ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>
    <?php require __DIR__ . '/stock.php'; ?>
    <div class="form-group">
        <label class="form-label" for="product-edit-description">商品説明</label>
        <textarea class="form-input product-edit-description" id="product-edit-description" name="description" maxlength="100" required><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
</form>
