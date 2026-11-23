// 商品一覧の選択状態と、削除確認モーダルの開閉を担当
(() => {
    const main = document.querySelector('.admin-products');
    const openButton = document.querySelector('#open-product-delete');
    const dialog = document.querySelector('#product-delete-dialog');
    const form = document.querySelector('#product-delete-form');
    if (!main || !openButton || !dialog || !form) return;
    const inputs = document.querySelector('#product-delete-ids');
    const confirm = document.querySelector('#product-delete-confirm');
    const title = document.querySelector('#product-delete-title');
    const selected = () => [...main.querySelectorAll('input[name="product_ids[]"]:checked')];

    function updateButton() { openButton.disabled = selected().length === 0; }
    main.addEventListener('change', updateButton);
    window.addEventListener('pageshow', () => {
        confirm.disabled = false;
        updateButton();
    });
    openButton.addEventListener('click', () => {
        const checked = selected();
        if (!checked.length) return;
        inputs.replaceChildren();
        checked.forEach(checkbox => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'product_ids[]';
            hidden.value = checkbox.value;
            inputs.append(hidden);
        });
        title.textContent = checked.length === 1
            ? 'この商品を削除しますか？' : `${checked.length}件の商品を削除しますか？`;
        confirm.disabled = false;
        dialog.showModal();
        document.querySelector('#product-delete-cancel').focus();
    });
    document.querySelector('#product-delete-cancel').addEventListener('click', () => dialog.close());
    form.addEventListener('submit', () => { confirm.disabled = true; });
    updateButton();
})();
