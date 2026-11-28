// 注文詳細画面で状態を変更したら送信する
(() => {
    const form = document.querySelector('#order-status-form');
    const select = form?.querySelector('select[name="status"]');
    if (!form || !select) return;

    select.addEventListener('change', () => {
        form.requestSubmit();
    });
})();