<?php
// 在庫管理で使用する、画像・商品名・商品番号・総在庫を表示する共通部品。
function renderProductSummary(array $product): void
{
    $image = $product['image'] ?? '';

    $imageUrl = '/B5/public/' . ltrim($image, '/');
    ?>
    <section class="product-summary">
        <!-- 画像表示 -->
        <div class="product-summary-image">
            <?php if ($image !== ''): ?>
                <img
                    src="<?= htmlspecialchars(
                        $imageUrl, ENT_QUOTES, 'UTF-8'
                    ) ?>"
                    alt="<?= htmlspecialchars(
                        $product['product_name'], ENT_QUOTES, 'UTF-8'
                    ) ?>"
                >
            <?php else: ?>
                <span>画像なし</span>
            <?php endif; ?>
        </div>
        <!-- 商品情報表示 -->
        <div class="product-summary-info">
            <h2 class="product-summary-name">
                <?= htmlspecialchars(
                    $product['product_name'], ENT_QUOTES, 'UTF-8'
                ) ?>
            </h2>

            <p class="product-summary-id">
                商品番号
                <span><?= (int) $product['product_id'] ?></span>
            </p>

            <div class="product-summary-stock">
                <span>総在庫数</span>
                <p>
                    <strong>
                        <?= number_format($product['total_stock']) ?>
                    </strong>
                    <span>点</span>
                </p>
            </div>
        </div>
    </section>
    <?php
}