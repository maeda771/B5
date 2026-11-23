// 編集画面用の画像プレビュー
(() => {
    const input = document.querySelector('#product-edit-image');
    const preview = document.querySelector('#product-edit-preview');
    const noImage = document.querySelector('#product-edit-no-image');
    const error = document.querySelector('#product-edit-image-error');
    if (!input || !preview || !noImage || !error) return;
    const original = preview.dataset.originalSrc;
    let reader = null;
    function restoreOriginal() {
        preview.hidden = !original;
        noImage.hidden = !!original;
        if (original) preview.src = original;
        else preview.removeAttribute('src');
    }
    function showError(message) {
        input.value = '';
        error.textContent = message;
        error.hidden = false;
        restoreOriginal();
    }
    input.addEventListener('change', () => {
        if (reader?.readyState === FileReader.LOADING) reader.abort();
        error.hidden = true;
        const file = input.files[0];
        if (!file) { restoreOriginal(); return; }
        if (file.size > 5 * 1024 * 1024) {
            showError('画像は5MB以下で選択してください。');
            return;
        }
        const currentReader = new FileReader();
        reader = currentReader;
        currentReader.addEventListener('load', () => {
            if (reader === currentReader) preview.src = currentReader.result;
        });
        currentReader.addEventListener('error', () => showError('画像を読み込めませんでした。'));
        currentReader.readAsDataURL(file);
    });
    preview.addEventListener('load', () => {
        preview.hidden = false;
        noImage.hidden = true;
    });
    preview.addEventListener('error', () => {
        if (input.files.length) {
            showError('JPEG・PNG・WebP形式の画像を選択してください。');
        } else {
            // 元画像自体が見つからない場合は、繰り返し読み込まない。
            preview.hidden = true;
            noImage.hidden = false;
        }
    });
})();
