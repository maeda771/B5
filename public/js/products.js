/*
 * 商品一覧画面専用。
 * ・縦長の画面は1ページ8件、横長は5件
 * ・並び替え
 * ・絞り込みの開閉、リセット
 *
 * 高さによる件数調整は行わず、縦スクロールを許可する。
 */

(() => {
    const main = document.querySelector(
        ".admin-products[data-per-page]"
    );

    if (!main) {
        return;
    }

    const form = main.querySelector(".products-search-form");
    const sort = form?.querySelector('select[name="sort"]');
    const filter = form?.querySelector(".products-filter");
    const resetButton = form?.querySelector(
        ".products-filter-reset"
    );

    if (!form || !sort || !filter || !resetButton) {
        return;
    }

    let navigating = false;
    let resizeTimer;

    // 縦長・正方形は8件、横長は5件。
    const orientation = window.matchMedia(
        "(orientation: portrait)"
    );

    function updatePageSize() {
        if (navigating) {
            return;
        }

        // キーボードの表示中は、件数変更を待つ。
        if (form.contains(document.activeElement)) {
            return;
        }

        const nextCount = orientation.matches ? 8 : 5;
        const currentCount = Number(main.dataset.perPage);

        // 同じ件数なら再読み込みしない。
        if (currentCount === nextCount) {
            return;
        }

        const url = new URL(window.location.href);

        // 検索条件は残し、件数変更時だけ1ページ目に戻す。
        url.searchParams.set("per_page", String(nextCount));
        url.searchParams.set("page", "1");

        navigating = true;
        window.location.replace(url.toString());
    }

    // 並び替えを変更したら、その条件で再表示する。
    sort.addEventListener("change", () => {
        form.requestSubmit();
    });

    form.addEventListener("submit", () => {
        navigating = true;
    });

    // カテゴリの選択を「すべて」に戻す。
    resetButton.addEventListener("click", () => {
        const allCategory = form.querySelector(
            'input[name="category"][value=""]'
        );

        if (allCategory) {
            allCategory.checked = true;
        }
    });

    // 絞り込みの外側を押したら閉じる。
    document.addEventListener("click", (event) => {
        if (!filter.contains(event.target)) {
            filter.open = false;
        }
    });

    // Escキーでも閉じられるようにする。
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && filter.open) {
            filter.open = false;
            filter.querySelector("summary").focus();
        }
    });

    // 画面の回転やウィンドウサイズ変更後に向きを確認する。
    function scheduleUpdate() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(updatePageSize, 400);
    }

    window.addEventListener("resize", scheduleUpdate);
    orientation.addEventListener("change", scheduleUpdate);

    // 入力を終えたあとにも、画面の向きを確認する。
    form.addEventListener("focusout", scheduleUpdate);

    updatePageSize();
})();