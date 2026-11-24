// カラー選択時の共通処理
// 商品登録画面、在庫変更画面で使用
function createColorLabel(colorName, colorCode) {
    const label = document.createElement('span');
    label.className = 'product-selected-color';

    const dot = document.createElement('span');
    dot.className = 'product-color-dot';

    // 正しい6桁のカラーコードなら、丸の背景色に設定。
    if (/^[0-9a-f]{6}$/i.test(colorCode || '')) {
        dot.style.backgroundColor = `#${colorCode}`;
    }

    const name = document.createElement('span');
    name.textContent = colorName;

    label.append(dot, name);

    return label;
}

// 選択したサイズ名を表示する。
// 複数なら「S・M」、未選択なら「サイズを選択」と表示する。
function updateSizeLabel(target, sizes) {
    target.textContent = sizes.length
        ? sizes.join('・')
        : 'サイズを選択';
}