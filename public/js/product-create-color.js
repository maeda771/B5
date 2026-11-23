// カラー追加モーダルの開閉・色選択・保存通信を担当 
(() => {
    if (!document.querySelector('#product-form')) return;
    const colorDetails = document.querySelector('#product-colors');
    const colorOptions = document.querySelector('#product-color-options');
    const dialog = document.querySelector('#color-dialog');
    const colorForm = document.querySelector('#color-form');
    const colorCode = document.querySelector('#color-code');
    const picker = document.querySelector('#color-picker');
    const swatch = document.querySelector('#color-swatch');
    const colorError = document.querySelector('#color-error');
    const saveColor = document.querySelector('#save-color');

    document.querySelector('#open-color-dialog').addEventListener('click', () => {
        colorDetails.open = false;
        colorError.hidden = true;
        dialog.showModal();
    });
    document.querySelector('#cancel-color-dialog').addEventListener('click', () => dialog.close());
    // 色のコード入力とカラーピッカーを同期
    colorCode.addEventListener('input', () => {
        if (/^#[0-9a-f]{6}$/i.test(colorCode.value)) {
            picker.value = colorCode.value;
            swatch.style.backgroundColor = colorCode.value;
        }
    });
    picker.addEventListener('input', () => {
        colorCode.value = picker.value.toUpperCase();
        swatch.style.backgroundColor = picker.value;
    });

    colorForm.addEventListener('submit', async event => {
        event.preventDefault();
        if (saveColor.disabled) return;
        saveColor.disabled = true;
        colorError.hidden = true;
        try {
            // 追加ボタンでカラーをDBに保存
            const response = await fetch(colorForm.getAttribute('action'), {
                method: 'POST',
                body: new FormData(colorForm),
                headers: { Accept: 'application/json' },
            });
            const result = await response.json();
            if (!response.ok || !result.color) {
                throw new Error(result.error || 'カラーを追加できませんでした。');
            }
            const color = result.color;
            if (!/^\d+$/.test(String(color.color_id)) || !/^[0-9a-f]{6}$/i.test(color.color_code)) {
                throw new Error('カラーの情報を読み込めませんでした。');
            }
            let input = [...colorOptions.querySelectorAll('input')]
                .find(item => item.value === String(color.color_id));
            if (!input) {
                const label = document.createElement('label');
                label.className = 'product-select-option';
                const dot = document.createElement('span');
                dot.className = 'product-color-dot';
                dot.style.backgroundColor = `#${color.color_code}`;
                const name = document.createElement('span');
                name.textContent = color.color_name;
                const code = document.createElement('small');
                code.textContent = `#${color.color_code}`;
                name.append(code);
                input = document.createElement('input');
                input.type = 'checkbox';
                input.name = 'colors[]';
                input.value = String(color.color_id);
                input.dataset.label = color.color_name;
                input.dataset.colorCode = color.color_code;
                label.append(dot, name, input);
                colorOptions.append(label);
            }
            input.checked = true;
            input.dispatchEvent(new Event('change', { bubbles: true }));
            dialog.close();
            colorForm.reset();
            swatch.style.backgroundColor = picker.value;
            colorDetails.open = true;
        } catch (error) {
            colorError.textContent = error instanceof SyntaxError
                ? '追加結果を読み込めませんでした。通信を確認してもう一度お試しください。'
                : error.message;
            colorError.hidden = false;
        } finally {
            saveColor.disabled = false;
        }
    });
})();
