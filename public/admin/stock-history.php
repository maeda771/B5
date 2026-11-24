<!-- A09 在庫変動履歴画面 -->
<!-- 実装する機能：在庫変動履歴表示。色・サイズ・変動区分・変動日での絞り込み、条件リセット、A07へ戻る操作。 -->
<?php
require_once __DIR__ . '/../../app/auth.php';
/*
ログインしていなければログイン画面へ
if (getAdminId() === false) {
    header('Location: /B5/public/admin/login.php');
    exit;
}
*/
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

// 履歴の取得と絞り込み項目の準備
require __DIR__ . '/../../app/admin/stock-history/data.php';

// 絞り込みの共通部品
require_once __DIR__ . '/../../app/views/components/list/filter.php';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/product-summary.css">
    <link rel="stylesheet" href="../css/common-list.css">
    <link rel="stylesheet" href="../css/stock-history.css">
    <script src="../js/common-list.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>在庫変動履歴画面</title>
</head>
<body class="has-header">
    <?php
    require_once __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('在庫変動履歴','back','stock.php?product_id='.(int)$productId);
    ?>
    <main>
        <!-- 商品概要の呼び出し -->
        <?php renderProductSummary($product); ?>

        <!-- 絞り込み -->
        <form action="stock-history.php"
            method="get"
            class="c-list-form stock-history-filter"
            data-list-form>

            <input type="hidden"
                name="product_id"
                value="<?= (int) $productId ?>">

            <?php renderListFilter($filterGroups); ?>
        </form>
    
        <!-- 在庫変動履歴 -->
        <div class="stock-history-list">
            <?php if (!$histories): ?>
                <p>在庫の変動履歴はありません。</p>
            <?php endif; ?>

            <!-- 履歴の表示 -->
            <?php foreach ($histories as $history): ?>
                <article class="stock-history-card">
                    <!-- 日時と変動区分 -->
                    <div class="stock-history-heading">
                        <!-- 変動日時 -->
                        <time><?= htmlspecialchars( date('Y/m/d H:i', strtotime($history['created_at'])), ENT_QUOTES, 'UTF-8') ?></time>
                        <!-- 変動区分、区分ごとにクラスを変更 -->
                        <span class="stock-history-type"
                            data-type="<?= htmlspecialchars(
                                $history['change_type'], ENT_QUOTES, 'UTF-8'
                            ) ?>"><?= htmlspecialchars( $history['change_type'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>

                    <!-- 変更対象の色とサイズ -->
                    <p class="stock-history-variant">
                        カラー：<?= htmlspecialchars($history['color_name'], ENT_QUOTES, 'UTF-8') ?>
                        ／サイズ：<?= htmlspecialchars($history['size'], ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <!-- 変更前後の在庫と増減数 -->
                    <div class="stock-history-quantities">
                        <div>
                            <span>変更前</span>
                            <p>
                                <?= number_format($history['quantity_before']) ?><small>点</small>
                            </p>
                        </div>
                        <span>→</span>
                        <div>
                            <span>変更後</span>
                            <p>
                                <?= number_format($history['quantity_after']) ?><small>点</small>
                            </p>
                        </div>
                        <!-- 変動数量、区分ごとにクラスを変更 -->
                        <span class="stock-history-change"
                            data-type="<?= htmlspecialchars(
                                $history['change_type'], ENT_QUOTES, 'UTF-8'
                        ) ?>">
                            <?= $history['quantity_change'] > 0 ? '+' : '' ?><?= number_format(
                                $history['quantity_change']
                            ) ?>点
                        </span>
                    </div>

                    <!-- 変動区分が「購入」の場合だけ注文番号を表示 -->
                    <?php if ($history['order_id'] !== null): ?>
                        <p class="stock-history-note">
                            注文番号：<?= (int) $history['order_id'] ?>
                        </p>
                    <?php endif; ?>

                    <!-- メモがある場合だけ表示 -->
                    <?php if ($history['memo'] !== null && $history['memo'] !== ''): ?>
                        <p class="stock-history-note">
                            メモ：<?= htmlspecialchars(
                                $history['memo'], ENT_QUOTES, 'UTF-8'
                            ) ?>
                        </p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>

