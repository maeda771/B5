<?php
// 渡された選択肢で並べ替えを表示
require_once __DIR__ . '/helpers.php';

function renderListSort(
    array $options,
    string $selected,
    string $name = 'sort'
): void
{
    ?>
    <details class="c-list-sort" data-list-sort-menu>
        <summary>
            <?= listEscape($options[$selected] ?? '') ?>
            <i class="fas fa-chevron-down"></i>
        </summary>

        <!-- 現在の並び順を保持する -->
        <input type="hidden"
               name="<?= listEscape($name) ?>"
               value="<?= listEscape($selected) ?>"
               data-list-sort-value>

        <!-- 選択肢を押すと、その並び順でフォームを送信する -->
        <div class="c-list-sort-options">
            <?php foreach ($options as $value => $label): ?>
                <button
                    type="submit"
                    value="<?= listEscape($value) ?>"
                    data-list-sort-choice
                >
                    <?= listEscape($label) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </details>
    <?php
}