// カラー・サイズの選択時の表示変化を担当
(() => {
    const form = document.querySelector('#product-form');
    if (!form) return;
    const colors = document.querySelector('#product-colors');
    const sizes = document.querySelector('#product-sizes');
    const colorSummary = document.querySelector('#color-selection');
    const sizeSummary = document.querySelector('#size-selection');

    // 選択済みの項目をリストの先頭に並べる
    function sortSelectedFirst(container) {
        const labels = [...container.children];
        // 選択を外したときは元の並びへ戻すため、初回の順序を記録
        labels.forEach((label, index) => {
            if (label.dataset.originalOrder === undefined) {
                label.dataset.originalOrder = String(index);
            }
        });
        labels.sort((a, b) => {
            const selected = Number(b.querySelector('input').checked)
                - Number(a.querySelector('input').checked);
            return selected || Number(a.dataset.originalOrder) - Number(b.dataset.originalOrder);
        });
        container.append(...labels);
    }
    // 選択済みのカラー・サイズを上部へ表示
    function updateSelections() {
        colorSummary.replaceChildren();
        const selectedColors = [...colors.querySelectorAll('input:checked')];
        if (!selectedColors.length) colorSummary.textContent = 'カラーを選択';
        // 選択されたカラーごとに、共通関数で表示部品を作る
        selectedColors.forEach(input => {
            const label = createColorLabel(
                input.dataset.label,
                input.dataset.colorCode
            );

            colorSummary.append(label);
        });
        const names = [...sizes.querySelectorAll('input:checked')].map(input => input.dataset.label);
        // 共通処理で、選択したサイズ名を表示する。
        updateSizeLabel(sizeSummary, names);
        sortSelectedFirst(document.querySelector('#product-color-options'));
        sortSelectedFirst(document.querySelector('.product-size-options'));
    }

    // フォームの値が変化したら表示を変更
    form.addEventListener('change', updateSelections);

    updateSelections();
})();
