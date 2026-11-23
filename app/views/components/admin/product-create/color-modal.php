<!-- カラー追加モーダル -->
<dialog id="color-dialog" class="product-color-dialog" aria-labelledby="color-dialog-title">
    <h2 id="color-dialog-title">カラーを追加</h2>
    <form id="color-form" action="product-create.php" method="post">
        <input type="hidden" name="action" value="add_color">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
        <div class="form-group">
            <label class="form-label" for="color-name">カラー名</label>
            <input class="form-input" id="color-name" name="color_name" placeholder="カラー名を入力" maxlength="20" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="color-code">カラーコード</label>
            <div class="product-color-code-box">
                <input class="form-input" id="color-code" name="color_code" type="text" value="#BC8DD5" pattern="#[0-9a-fA-F]{6}" maxlength="7" required>
                <span class="product-color-swatch" id="color-swatch"></span>
            </div>
            <!-- カラーピッカー -->
            <label class="product-color-picker-label" for="color-picker">色を選ぶ</label>
            <input id="color-picker" class="product-color-picker" type="color" value="#bc8dd5">
        </div>
        <p id="color-error" class="form-error" role="alert" hidden></p>
        <div class="product-color-dialog-actions">
            <button class="common-button product-color-cancel" id="cancel-color-dialog" type="button">キャンセル</button>
            <button class="common-button" id="save-color" type="submit">追加</button>
        </div>
    </form>
</dialog>
