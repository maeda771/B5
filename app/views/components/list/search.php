<?php
// 検索欄を表示
require_once __DIR__ . '/helpers.php';

function renderListSearch(string $value, string $placeholder = '検索', string $name = 'keyword', string $label = '検索'): void
{
?>
    <div class="c-list-search">
        <i class="fas fa-search"></i>
        <input type="search" 
            name="<?= listEscape($name) ?>"
            value="<?= listEscape($value) ?>" 
            placeholder="<?= listEscape($placeholder) ?>"
        >
    </div>
<?php
}
?>
