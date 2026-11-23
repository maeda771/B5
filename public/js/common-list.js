/* 一覧画面の共通操作。
 * SQL・商品名・カテゴリ名など画面固有の知識は持たない。
 * 縦長／横長の件数はmainのdata属性で指定する。
 */
(() => {
    document.querySelectorAll('[data-list-form]').forEach((form) => {
        // 同じスクリプトが重複して読み込まれた場合の二重登録を防ぐ。
        if (form.dataset.listReady) return;
        form.dataset.listReady = '1';
        form.querySelectorAll('[data-list-sort]').forEach((sort) => {
            sort.addEventListener('change', () => form.requestSubmit());
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
            document.addEventListener('click', (event) => {
                if (!filter.contains(event.target)) filter.open = false;
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
