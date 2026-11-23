<!-- 商品詳細テーブルの在庫を合計 -->
<div class="form-group">
    <span class="form-label">総在庫数</span>
    <div class="product-edit-stock">
        <p><strong><?= number_format($totalStock) ?></strong><span>点</span></p>
        <a class="common-button product-edit-stock-link" href="stock.php?product_id=<?= (int) $productId ?>">在庫管理へ 　 ›</a>
    </div>
</div>
