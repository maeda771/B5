/* 一覧画面の共通操作。
 * SQL・商品名・カテゴリ名など画面固有の知識は持たない。
 * 縦長／横長の件数はmainのdata属性で指定する。
 */
(() => {
    document.querySelectorAll('[data-list-form]').forEach((form) => {
        // 同じスクリプトが重複して読み込まれた場合の二重登録を防ぐ。
        if (form.dataset.listReady) return;
        form.dataset.listReady = '1';
        // 並べ替えを押した場合だけ、送信する並び順を更新する。
        form.addEventListener('submit', event => {
            const button = event.submitter;

            if (button?.matches('[data-list-sort-choice]')) {
                form.querySelector('[data-list-sort-value]').value = button.value;
            }
        });
        form.querySelectorAll('[data-list-filter]').forEach((filter) => {
            filter.querySelector('[data-list-reset]')?.addEventListener('click', () => {
                // 絞り込み項目だけをリセットし、検索語・並び順は維持する。
                filter.querySelectorAll('input, select').forEach((input) => {
                    if (input.type === 'radio' || input.type === 'checkbox') {
                        input.checked = input.dataset.listDefaultChecked === '1';
                    } else {
                        input.value = input.dataset.listDefault ?? '';
                    }
                });
            });
            filter.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && filter.open) {
                    filter.open = false;
                    filter.querySelector('summary').focus();
                }
            });
        });
    });

    document.querySelectorAll('[data-list-page][data-per-page]').forEach((main) => {
        if (main.dataset.listPageReady) return;
        main.dataset.listPageReady = '1';
        const currentCount = Number(main.dataset.perPage);
        const portraitCount = Number(main.dataset.portraitCount);
        const landscapeCount = Number(main.dataset.landscapeCount);
        if (![currentCount, portraitCount, landscapeCount].every((n) => Number.isInteger(n) && n > 0)) return;
        const orientation = window.matchMedia('(orientation: portrait)');
        let navigating = false;
        let timer;
        main.querySelectorAll('form').forEach((form) => {
            form.addEventListener('submit', () => { navigating = true; });
        });
        function updatePageSize() {
            if (navigating) return;
            // スマホのキーボード表示中は画面の向きの判定を待つ。
            if (main.contains(document.activeElement) && document.activeElement.matches('input, select, textarea')) return;
            const nextCount = orientation.matches ? portraitCount : landscapeCount;
            if (nextCount === currentCount) return;
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', String(nextCount));
            url.searchParams.set('page', '1');
            navigating = true;
            window.location.replace(url.toString());
        }
        function scheduleUpdate() {
            clearTimeout(timer);
            timer = setTimeout(updatePageSize, 400);
        }
        window.addEventListener('resize', scheduleUpdate);
        orientation.addEventListener('change', scheduleUpdate);
        main.addEventListener('focusout', scheduleUpdate);
        updatePageSize();
    });
})();
// 並べ替え・絞り込みの開閉をそろえる。
(() => {
    const menus = [
        ...document.querySelectorAll(
            '[data-list-sort-menu], [data-list-filter]'
        )
    ];

    menus.forEach(menu => {
        // 一方を開いたら、もう一方を閉じる。
        menu.addEventListener('toggle', () => {
            if (!menu.open) return;

            menus.forEach(other => {
                if (other !== menu) other.open = false;
            });
        });
    });

    // メニューの外側を押したら閉じる。
    document.addEventListener('click', event => {
        menus.forEach(menu => {
            if (!menu.contains(event.target)) {
                menu.open = false;
            }
        });
    });
})();

// 価格スライダーの数値表示を更新する。
(() => {
    document.querySelectorAll('[data-price-range]').forEach(range => {
        const min = range.querySelector('[data-price-min]');
        const max = range.querySelector('[data-price-max]');
        const label = range.querySelector('[data-price-label]');

        function updatePrice(event) {
            // 下限と上限のつまみが逆転しないようにする。
            if (Number(min.value) > Number(max.value)) {
                if (event?.target === min) {
                    min.value = max.value;
                } else {
                    max.value = min.value;
                }
            }

            label.textContent =
                `¥${Number(min.value).toLocaleString('ja-JP')}`
                + ' 〜 '
                + `¥${Number(max.value).toLocaleString('ja-JP')}`;

            // 選択中の価格範囲を、レールの緑色に反映する。
            const sliders = range.querySelector('.c-list-price-sliders');
            const lowerLimit = Number(min.min);
            const upperLimit = Number(min.max);
            const width = upperLimit - lowerLimit;

            sliders.style.setProperty(
                '--price-min',
                `${(Number(min.value) - lowerLimit) / width * 100}%`
            );
            sliders.style.setProperty(
                '--price-max',
                `${(Number(max.value) - lowerLimit) / width * 100}%`
            );
            // 両方が右端で重なっても、下限のつまみを戻せるようにする。
            min.style.zIndex = Number(min.value) === Number(min.max)
                ? '2'
                : '0';
        }

        min.addEventListener('input', updatePrice);
        max.addEventListener('input', updatePrice);

        // 既存のリセット処理で値が戻った後、表示も更新する。
        range.closest('[data-list-filter]')
            .querySelector('[data-list-reset]')
            ?.addEventListener('click', () => updatePrice());

        updatePrice();
    });
})();
