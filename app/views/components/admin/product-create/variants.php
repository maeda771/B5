<!-- カラーとサイズの選択欄。登録フォーム内から読み込む。 -->
<fieldset class="form-group product-variants">
    <legend class="form-label">バリエーション <span class="required">※必須</span></legend>
    <div class="product-variant-field">
        <span id="color-label">カラー</span>
        <details class="product-select" id="product-colors">
            <summary class="form-input" aria-labelledby="color-label color-selection">
                <span id="color-selection">カラーを選択</span>
                <i class="fas fa-chevron-down" aria-hidden="true"></i>
            </summary>
            <div class="product-select-panel">
                <div id="product-color-options" class="product-color-options">
                    <?php foreach ($colors as $color): ?>
                        <label class="product-select-option">
                            <span class="product-color-dot" style="background-color: #<?= preg_match('/\A[0-9a-fA-F]{6}\z/', $color['color_code']) ? $color['color_code'] : '808080' ?>"></span>
                            <span><?= htmlspecialchars($color['color_name'], ENT_QUOTES, 'UTF-8') ?><small>#<?= htmlspecialchars($color['color_code'], ENT_QUOTES, 'UTF-8') ?></small></span>
                            <input type="checkbox" name="colors[]" value="<?= (int) $color['color_id'] ?>"
                                data-label="<?= htmlspecialchars($color['color_name'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-color-code="<?= htmlspecialchars($color['color_code'], ENT_QUOTES, 'UTF-8') ?>"
                                <?= in_array((string) $color['color_id'], $selectedColors, true) ? 'checked' : '' ?>>
                        </label>
                    <?php endforeach; ?>
                </div>
                <button class="product-add-color" type="button" id="open-color-dialog">
                    <i class="fas fa-plus" aria-hidden="true"></i> カラーを追加
                </button>
            </div>
        </details>
    </div>

    <div class="product-variant-field">
        <span id="size-label">サイズ</span>
        <details class="product-select" id="product-sizes">
            <summary class="form-input" aria-labelledby="size-label size-selection">
                <span id="size-selection">サイズを選択</span>
                <i class="fas fa-chevron-down" aria-hidden="true"></i>
            </summary>
            <div class="product-select-panel product-size-options">
                <?php foreach ($sizes as $size): ?>
                    <label class="product-select-option">
                        <span><?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?></span>
                        <input type="checkbox" name="sizes[]" value="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"
                            data-label="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"
                            <?= in_array($size, $selectedSizes, true) ? 'checked' : '' ?>>
                    </label>
                <?php endforeach; ?>
            </div>
        </details>
    </div>
    <p class="form-error" id="product-variant-error" role="alert" hidden></p>
</fieldset>
