<!-- 商品登録フォーム -->
<form id="product-form" action="product-create.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="create_product">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

    <div class="form-group">
        <label class="form-label" for="product-image">商品画像</label>
        <label class="product-image-picker" for="product-image">
            <span class="product-image-placeholder">
                <i class="fas fa-camera" aria-hidden="true"></i>
                <span>画像を追加</span>
            </span>
            <img id="product-image-preview" alt="選択した商品画像" hidden>
            <input id="product-image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </label>
        <p id="product-image-error" class="form-error" role="alert" hidden></p>
    </div>

    <div class="form-group">
        <label class="form-label" for="product-name">商品名 <span class="required">※必須</span></label>
        <input class="form-input" id="product-name" name="product_name" type="text"
            placeholder="商品名を入力" maxlength="30" required
            value="<?= htmlspecialchars($productName, ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="form-group">
        <label class="form-label" for="product-category">カテゴリ <span class="required">※必須</span></label>
        <select class="form-input" id="product-category" name="category" required>
            <option value="">カテゴリを選択</option>
            <?php foreach ($categories as $categoryOption): ?>
                <option value="<?= htmlspecialchars($categoryOption, ENT_QUOTES, 'UTF-8') ?>" <?= $category === $categoryOption ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categoryOption, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" for="product-price">価格 <span class="required">※必須</span></label>
        <input class="form-input" id="product-price" name="product_price" type="number"
            placeholder="価格を入力" min="1" max="2147483647" step="1" inputmode="numeric" required
            value="<?= htmlspecialchars($price, ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <!-- バリエーション項目は別ファイルで表示 -->
    <?php require __DIR__ . '/variants.php'; ?>

    <div class="form-group">
        <label class="form-label" for="product-description">商品説明 <span class="required">※必須</span></label>
        <textarea class="form-input product-description" id="product-description" name="description"
            placeholder="商品説明を入力" maxlength="100" required><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>
</form>
