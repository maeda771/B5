// 保存ボタンの二重押下を防ぐ
(() => {
    const form = document.querySelector('#product-edit-form');
    const submitButton = document.querySelector('.product-edit-submit');
    if (!form || !submitButton) return;
    form.addEventListener('submit', () => { submitButton.disabled = true; });
    window.addEventListener('pageshow', () => { submitButton.disabled = false; });
})();
