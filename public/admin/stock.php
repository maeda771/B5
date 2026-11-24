<!-- A07 在庫管理画面 -->
<!-- 実装する機能：対象商品の色・サイズ別在庫表示。A06へ戻る、A08在庫変更、A09変動履歴への導線。 -->
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

// 商品の色・サイズ・在庫数を取得する。
$details = getProductVariants($pdo, $productId);
// 色ごとの情報
$stockColors = [];
// 色・サイズ別の在庫数
$stockColors = groupProductVariantsByColor($details);

// サイズをS・M・L・LL・Fの順にそろえる
$stockSizes = getProductSizes($details);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/product-summary.css">
    <link rel="stylesheet" href="../css/stock.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/common-list.js" defer></script>
    <script src="../js/product-delete.js" defer></script>
    <title>在庫管理画面</title>
</head>
<body class="has-header">
    <?php
    require_once __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('在庫管理','back','product-edit.php?product_id='.(int)$productId);
    ?>
    <main>
        <!-- 商品概要の呼び出し -->
        <?php renderProductSummary($product); ?>

        <section class="product-stock-section">
            <div class="product-stock-heading">
                <h2>色・サイズ別の在庫数</h2>

                <a class="product-stock-edit"
                href="stock-edit.php?product_id=<?= (int) $productId ?>">
                    在庫を変更 ›
                </a>
            </div>

            <?php if (!$stockColors): ?>
                <p>色・サイズが登録されていません。</p>
            <?php else: ?>
                <div class="product-stock-table-wrapper">
                    <table class="product-stock-table">
                        <thead>
                            <tr>
                                <th scope="col">カラー</th>

                                <!-- サイズごとの見出し -->
                                <?php foreach ($stockSizes as $size): ?>
                                    <th scope="col">
                                        <?= htmlspecialchars(
                                            $size, ENT_QUOTES, 'UTF-8'
                                        ) ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- 色ごとに1行表示 -->
                            <?php foreach ($stockColors as $color): ?>
                                <?php
                                $colorCode = preg_match(
                                    '/\A[0-9a-fA-F]{6}\z/',
                                    $color['code']
                                ) ? $color['code'] : 'CCCCCC';
                                ?>

                                <tr>
                                    <th scope="row">
                                        <span class="product-stock-color">
                                            <span
                                                class="product-stock-color-dot"
                                                style="background-color: #<?= $colorCode ?>"
                                            ></span>

                                            <?= htmlspecialchars(
                                                $color['name'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </span>
                                    </th>

                                    <!-- その色のサイズ別在庫 -->
                                    <?php foreach ($stockSizes as $size): ?>
                                        <td>
                                            <?php if (isset($color['stocks'][$size])): ?>
                                                <?= number_format(
                                                    $color['stocks'][$size]
                                                ) ?><small>点</small>
                                            <?php else: ?>
                                                ―
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <!-- 対象商品の在庫変動履歴へ移動 -->
            <a class="product-stock-history"
            href="stock-history.php?product_id=<?= (int) $productId ?>">
                <i class="far fa-file-alt"></i>
                <span>在庫の変動履歴を確認</span>
                <span class="product-stock-history-arrow">→</span>
            </a>
        </section>
    </main>
</body>
</html>
