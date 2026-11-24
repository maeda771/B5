// 在庫変更画面のカラー選択。
(() => {
    const picker = document.querySelector('#stock-color-picker');
    if (!picker) return;

    const label = document.querySelector('#stock-color-label');
    const value = document.querySelector('#stock-color-value');

    picker.querySelectorAll('[data-stock-color]').forEach(button => {
        button.addEventListener('click', () => {
            value.value = button.dataset.stockColor;

            // 選択されたカラーごとに、共通関数で表示部品を作る
            const selected = createColorLabel(
                button.dataset.colorName,
                button.dataset.colorCode
            );
            selected.classList.add('stock-edit-choice-label');
            selected.querySelector('.product-color-dot')
                .classList.add('stock-edit-color-dot');

            label.replaceChildren(selected);
            picker.open = false;
        });
    });
})();

// 在庫変更画面のサイズ選択。
(() => {
    const picker = document.querySelector('#stock-size-picker');
    if (!picker) return;

    const label = document.querySelector('#stock-size-label');
    const value = document.querySelector('#stock-size-value');

    picker.querySelectorAll('[data-stock-size]').forEach(button => {
        button.addEventListener('click', () => {
            const selectedSize = button.dataset.stockSize;

            // 選んだサイズを、送信用のhiddenに入れる。
            value.value = selectedSize;

            // 共通の表示処理に、選んだ1件を配列で渡す。
            updateSizeLabel(label, [selectedSize]);

            picker.open = false;
        });
    });
})();

// 在庫の増減数量を変更
(() => {
    const quantity = document.querySelector('#stock-quantity');
    const minus = document.querySelector('#stock-quantity-minus');
    const plus = document.querySelector('#stock-quantity-plus');
    if (!quantity || !minus || !plus) return;
    function changeQuantity(amount) {
        const current = Number(quantity.value);
        // 整数でなければ、0を基準に
        const base = Number.isInteger(current) ? current : 0;
        quantity.value = base + amount;
    }
    minus.addEventListener('click', () => changeQuantity(-1));
    plus.addEventListener('click', () => changeQuantity(1));
})();

// 送信後は保存ボタンを押せなくする
(() => {
    const form = document.querySelector('#stock-edit-form');
    const button = form?.querySelector('.stock-edit-submit');

    if (!form || !button) return;

    form.addEventListener('submit', () => {
        button.disabled = true;
    });

    // ブラウザの「戻る」で復元された場合は押せる状態に戻す
    window.addEventListener('pageshow', () => {
        button.disabled = false;
    });
})();

// 再表示時、hiddenに残っている値を選択欄の表示にも反映する。
(() => {
    const colorValue = document.querySelector('#stock-color-value');
    const sizeValue = document.querySelector('#stock-size-value');

    if (!colorValue || !sizeValue) return;

    const colorButton = [
        ...document.querySelectorAll('[data-stock-color]')
    ].find(button => button.dataset.stockColor === colorValue.value);

    const sizeButton = [
        ...document.querySelectorAll('[data-stock-size]')
    ].find(button => button.dataset.stockSize === sizeValue.value);

    // 既存のクリック処理を使って、色・サイズを表示する。
    colorButton?.click();
    sizeButton?.click();
})();