<?php
// 渡された選択肢で並び替えを表示
require_once __DIR__ . '/helpers.php';

function renderListSort(array $options, string $selected, string $name = 'sort'): void
{
    ?>
    <div class="c-list-sort">
        <select name="<?= listEscape($name) ?>" data-list-sort>
            <?php foreach ($options as $value => $label): ?>
                <option value="<?= listEscape($value) ?>" <?= (string) $value === $selected ? 'selected' : '' ?>><?= listEscape($label) ?></option>
            <?php endforeach; ?>
        </select>
        <i class="fas fa-chevron-down"></i>
    </div>
    <?php
}
