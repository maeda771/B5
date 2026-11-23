<?php
// 現在ページ・総ページ数・URLを受け取り、ページ番号を表示
require_once __DIR__ . '/helpers.php';

function renderListPagination(int $page, int $totalPages, callable $pageUrl): void
{
    $start = max(1, $page - 2);
    $end = min($totalPages, $start + 4);
    $start = max(1, $end - 4);
    ?>
    <nav class="c-list-pagination">
        <?php if ($page > 1): ?>
            <a href="<?= listEscape($pageUrl($page - 1)) ?>"><i class="fas fa-chevron-left"></i></a>
        <?php else: ?><span class="is-disabled"><i class="fas fa-chevron-left"></i></span><?php endif; ?>
        <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i === $page): ?><span class="is-current" aria-current="page"><?= $i ?></span>
            <?php else: ?><a href="<?= listEscape($pageUrl($i)) ?>"><?= $i ?></a><?php endif; ?>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="<?= listEscape($pageUrl($page + 1)) ?>"><i class="fas fa-chevron-right"></i></a>
        <?php else: ?><span class="is-disabled"><i class="fas fa-chevron-right"></i></span><?php endif; ?>
    </nav>
    <?php
}
