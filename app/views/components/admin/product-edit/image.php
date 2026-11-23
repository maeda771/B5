<!-- 現在の商品画像と、画像を変更するボタン。ファイルを選ばなければ画像は維持する。 -->
<div class="form-group">
    <label class="form-label" for="product-edit-image">商品画像</label>
    <div class="product-edit-image-row">
        <?php $imageUrl = $product['image'] ? '../' . $product['image'] : ''; ?>
        <div class="product-edit-thumbnail">
            <img id="product-edit-preview" alt="商品画像"
                data-original-src="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>"
                <?= $imageUrl !== '' ? 'src="' . htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') . '"' : 'hidden' ?>>
            <span id="product-edit-no-image" <?= $imageUrl !== '' ? 'hidden' : '' ?>>画像なし</span>
        </div>
        <label class="product-edit-image-button" for="product-edit-image">
            <i class="fas fa-camera" aria-hidden="true"></i>
            <span>画像を変更</span>
            <input id="product-edit-image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
        </label>
    </div>
    <p class="form-error" id="product-edit-image-error" role="alert" hidden></p>
</div>
