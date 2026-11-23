// カラー・サイズのプルダウン表示と登録時のチェック担当
(() => {
    const form = document.querySelector('#product-form');
    if (!form) return;

    const colorDetails = document.querySelector('#product-colors');
    const sizeDetails = document.querySelector('#product-sizes');
    const variantError = document.querySelector('#product-variant-error');
    const submitButton = document.querySelector('.product-create-submit');
    // 外側をクリックしたとき選択肢を閉じる
    document.addEventListener('click', event => {
        for (const details of [colorDetails, sizeDetails]) {
            if (!details.contains(event.target)) details.open = false;
        }
    });
    // カラーとサイズのプルダウンを同時に開かないようにする
    for (const details of [colorDetails, sizeDetails]) {
        details.addEventListener('toggle', () => {
            if (details.open) {
                (details === colorDetails ? sizeDetails : colorDetails).open = false;
            }
        });
    }

    // 登録時にカラー・サイズそれぞれ1つ以上選ばれているか確認
    form.addEventListener('submit', event => {
        const hasColor = !!colorDetails.querySelector('input:checked');
        const hasSize = !!sizeDetails.querySelector('input:checked');
        if (!hasColor || !hasSize) {
            event.preventDefault();
            variantError.textContent = 'カラーとサイズをそれぞれ1つ以上選択してください。';
            variantError.hidden = false;
            const missingDetails = !hasColor ? colorDetails : sizeDetails;
            missingDetails.open = true;
            missingDetails.querySelector('summary').focus();
            return;
        }
        variantError.hidden = true;
        submitButton.disabled = true;
    });

    // ブラウザの「戻る」で再表示された場合はボタンを戻す
    window.addEventListener('pageshow', () => { submitButton.disabled = false; });

})();
