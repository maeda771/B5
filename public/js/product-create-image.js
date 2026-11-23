// 商品画像の読み込みとプレビューの表示
(() => {
    const imageInput = document.querySelector('#product-image');
    const imagePreview = document.querySelector('#product-image-preview');
    const placeholder = document.querySelector('.product-image-placeholder');
    const error = document.querySelector('#product-image-error');
    if (!imageInput || !imagePreview || !placeholder || !error) return;
    let reader = null;

    // 画像にエラーがあればプレビューと選択をリセット、エラー表示
    function showError(message) {
        imagePreview.hidden = true;
        placeholder.hidden = false;
        imageInput.value = '';
        error.textContent = message;
        error.hidden = false;
    }
    imageInput.addEventListener('change', () => {
        // 画像を選び直したら、前の読み込みを止める。
        if (reader && reader.readyState === FileReader.LOADING) reader.abort();
        imagePreview.hidden = true;
        placeholder.hidden = false;
        error.hidden = true;
        const file = imageInput.files[0];
        if (!file) return;
        // ファイル容量の確認
        if (file.size > 5 * 1024 * 1024) {
            showError('画像は5MB以下で選択してください。');
            return;
        }
        reader = new FileReader();
        reader.addEventListener('load', () => {
            imagePreview.src = reader.result;
        });
        reader.addEventListener('error', () => {
            showError('ファイルを読み込めませんでした。画像を選び直してください。');
        });
        reader.readAsDataURL(file);
    });
    // 画像の読み込みが完了したら、プレビューを表示
    imagePreview.addEventListener('load', () => {
        imagePreview.hidden = false;
        placeholder.hidden = true;
    });
    imagePreview.addEventListener('error', () => {
        showError('画像を表示できませんでした。JPEG・PNG・WebP形式の画像を選んでください。');
    });
})();
