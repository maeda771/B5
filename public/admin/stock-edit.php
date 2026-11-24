<!-- A08 在庫変更画面 -->
<!-- 実装する機能：色・サイズ、増減数量、変更理由、メモの入力。在庫更新と変更前後・増減数量を含む履歴登録を一つのトランザクションで行う。 -->
<?php
/*
ログインしていなければログイン画面へ

require_once __DIR__ . '/../../app/auth.php';

if (getAdminId() === false) {
    header('Location: /B5/public/admin/login.php');
    exit;
}
*/
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/db.php';
// 共通のCSRF処理
require_once __DIR__ . '/../../app/csrf.php';
$token = getCsrfToken();
$errors = [];

// URLから現在の商品番号取得
$productId = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);
if (!$productId || $productId < 1) {
    http_response_code(404);
    exit('商品が見つかりません。');
}

require_once __DIR__ . '/../../app/products/find.php';
require_once __DIR__ . '/../../app/views/components/product-summary.php';
$product = findProductById($pdo, $productId);
if ($product === null) {
    http_response_code(404);
    exit('商品が見つかりません。');
}
$variants = getProductVariants($pdo, $productId);
// 色・サイズ別の在庫数
$colorOptions = groupProductVariantsByColor($variants);
// サイズ選択欄で使う一覧。
$sizeOptions = getProductSizes($variants);

// 商品ごとに、保存1回分の確認番号を用意
if (!isset($_SESSION['stock_edit_requests'][$productId])) {
    $_SESSION['stock_edit_requests'][$productId]
        = bin2hex(random_bytes(16));
}

$requestId = $_SESSION['stock_edit_requests'][$productId];

// フォームの初期値。
$selectedColorId = '';
$selectedSize = '';
$quantity = '0';
$reason = '入荷';
$memo = '';

// トークンの確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['token'] ?? null)) {
        $errors[] = '画面を開き直してから保存してください。';
    } else {
        // 正しいトークンなら、入力内容を確認
        require __DIR__ . '/../../app/admin/stock-edit/validate.php';

        // 入力内容を履歴に保存・在庫更新
        if (!$errors) {
            require __DIR__ . '/../../app/admin/stock-edit/save.php';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/product-summary.css">
    <link rel="stylesheet" href="../css/stock-edit.css">
    <script src="../js/color-label.js" defer></script>
    <script src="../js/stock-edit.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/common-list.js" defer></script>
    <script src="../js/product-delete.js" defer></script>
    <title>在庫変更画面</title>
</head>
<body class="has-header">
    <?php
    require_once __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('在庫変更','back','stock.php?product_id='.(int)$productId);
    ?>
    <main>
        <!-- 商品概要の呼び出し -->
        <?php renderProductSummary($product); ?>

        <!-- 保存時のエラーを表示する -->
        <?php if ($errors): ?>
            <div class="form-error" role="alert">
                <?php foreach ($errors as $error): ?>
                    <p>
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- 変更内容入力フォーム -->
        <form id="stock-edit-form" action="stock-edit.php?product_id=<?= (int) $productId ?>" method="post">
            <!-- 保存時に確認するトークン -->
            <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

            <!-- 二重保存を防ぐ確認番号 -->
            <input type="hidden" name="request_id" value="<?= htmlspecialchars($requestId, ENT_QUOTES, 'UTF-8') ?>">

            <section class="stock-edit-section">
                <h2>
                    <span class="stock-edit-number">1</span>
                    変更する商品
                </h2>
                <div class="stock-edit-variants">
                    <div>
                        <!-- カラー選択プルダウン -->
                        <span class="stock-edit-label">カラー</span>
                        <details class="stock-edit-picker" id="stock-color-picker">
                            <summary>
                                <span id="stock-color-label">カラーを選択</span>
                                <i class="fas fa-chevron-down"></i>
                            </summary>

                            <!-- 選択した色番号を入れ、保存時にPHPへ送る -->
                            <input type="hidden" name="color_id" id="stock-color-value" value="<?= htmlspecialchars($selectedColorId, ENT_QUOTES, 'UTF-8') ?>">

                            <div class="stock-edit-options">
                                <?php foreach ($colorOptions as $colorId => $color): ?>
                                    <?php
                                    // 色の丸に使うカラーコードを確認する。
                                    $colorCode = preg_match(
                                        '/\A[0-9a-fA-F]{6}\z/',
                                        $color['code']
                                    ) ? $color['code'] : 'CCCCCC';
                                    ?>

                                    <button type="button"
                                        data-stock-color="<?= (int) $colorId ?>"
                                        data-color-name="<?= htmlspecialchars(
                                            $color['name'], ENT_QUOTES, 'UTF-8'
                                        ) ?>"
                                        data-color-code="<?= htmlspecialchars(
                                            $colorCode, ENT_QUOTES, 'UTF-8'
                                        ) ?>">
                                        <span class="stock-edit-choice-label">
                                            <span class="stock-edit-color-dot"
                                                style="background-color: #<?= $colorCode ?>">
                                            </span>

                                            <?= htmlspecialchars(
                                                $color['name'], ENT_QUOTES, 'UTF-8'
                                            ) ?>
                                        </span>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    </div>
                    <div>
                        <!-- サイズ選択プルダウン -->
                        <span class="stock-edit-label">サイズ</span>
                        <details class="stock-edit-picker" id="stock-size-picker">
                            <summary>
                                <span id="stock-size-label">サイズを選択</span>
                                <i class="fas fa-chevron-down"></i>
                            </summary>
                            <!-- 選択したサイズを保存時に送る -->
                            <input type="hidden" name="size" id="stock-size-value" value="<?= htmlspecialchars($selectedSize, ENT_QUOTES, 'UTF-8') ?>">
                            <div class="stock-edit-options">
                                <?php foreach ($sizeOptions as $size): ?>
                                    <button type="button" data-stock-size="<?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?>"> <?= htmlspecialchars($size, ENT_QUOTES, 'UTF-8') ?></button>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    </div>
                </div>
            </section>
            <!-- 数量選択 -->
            <section class="stock-edit-section">
                <h2>
                    <span class="stock-edit-number">2</span>
                    数量
                </h2>
                <div class="stock-edit-quantity">
                    <button type="button" id="stock-quantity-minus">−</button>
                    <input type="number" name="quantity" id="stock-quantity" value="<?= htmlspecialchars($quantity, ENT_QUOTES, 'UTF-8') ?>" step="1" required>
                    <button type="button" id="stock-quantity-plus">＋</button>
                </div>
            </section>
            <!-- 在庫内容 -->
            <section class="stock-edit-section">
                <h2>
                    <span class="stock-edit-number">3</span>
                    変更内容
                </h2>
                <!-- 変更理由プルダウン -->
                <label class="stock-edit-label" for="stock-reason">変更理由</label>
                <select id="stock-reason" name="reason" class="stock-edit-reason">
                    <option value="入荷" <?= $reason === '入荷' ? 'selected' : '' ?>>
                        入荷
                    </option>
                    <option value="調整" <?= $reason === '調整' ? 'selected' : '' ?>>
                        調整
                    </option>
                </select>
                <!-- メモ欄 -->
                <label class="stock-edit-label stock-edit-memo-label" for="stock-memo">メモ（任意）</label>
                <textarea
                    id="stock-memo"
                    name="memo"
                    class="form-input stock-edit-memo"
                    maxlength="255"
                    placeholder="補足事項を入力"
                ><?= htmlspecialchars($memo, ENT_QUOTES, 'UTF-8') ?></textarea>
            </section>
            <button type="submit" class="button stock-edit-submit">
                保存
            </button>
        </form>
    </main>
</body>
</html>

