<?php
// 渡された項目で絞り込みを表示
require_once __DIR__ . '/helpers.php';

function renderListFilter(array $groups): void
{
    ?>
    <details class="c-list-filter" data-list-filter>
        <summary><i class="fas fa-filter"></i><span>絞り込み</span><i class="fas fa-chevron-down"></i></summary>
        <div class="c-list-filter-panel">
            <?php foreach ($groups as $group):
                $type = $group['type'] ?? 'radio';
                $selected = $group['selected'] ?? '';
                $default = $group['default'] ?? '';
                $name = $group['name'];
                ?>
                <fieldset>
                    <legend><?= listEscape($group['label']) ?></legend>
                    <?php if ($type === 'radio' || $type === 'checkbox'): ?>
                        <div class="c-list-options">
                            <?php foreach ($group['options'] as $value => $label):
                                $values = array_map('strval', (array) $selected);
                                $defaults = array_map('strval', (array) $default);
                                $checked = $type === 'checkbox' ? in_array((string) $value, $values, true) : (string) $value === (string) $selected;
                                $resetChecked = $type === 'checkbox' ? in_array((string) $value, $defaults, true) : (string) $value === (string) $default;
                                ?>
                                <label>
                                    <input type="<?= $type ?>" name="<?= listEscape($name . ($type === 'checkbox' ? '[]' : '')) ?>"
                                           value="<?= listEscape($value) ?>" <?= $checked ? 'checked' : '' ?>
                                           data-list-default-checked="<?= $resetChecked ? '1' : '0' ?>">
                                    <span><?= listEscape($label) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                                        <?php elseif ($type === 'price'): ?>
                        <!-- 価格の下限・上限を2つのつまみで指定 -->
                        <div class="c-list-price" data-price-range>
                            <p data-price-label>
                                ¥<?= number_format($group['min']) ?>
                                〜
                                ¥<?= number_format($group['max']) ?>
                            </p>

                            <div class="c-list-price-sliders">
                                <input
                                    type="range"
                                    name="min_price"
                                    min="0"
                                    max="100000"
                                    step="1"
                                    value="<?= (int) $group['min'] ?>"
                                    data-list-default="0"
                                    data-price-min
                                    aria-label="価格の下限"
                                >
                                <input
                                    type="range"
                                    name="max_price"
                                    min="0"
                                    max="100000"
                                    step="1"
                                    value="<?= (int) $group['max'] ?>"
                                    data-list-default="100000"
                                    data-price-max
                                    aria-label="価格の上限"
                                >
                            </div>
                        </div>
                    <?php elseif ($type === 'select'): ?>
                        <select name="<?= listEscape($name) ?>" data-list-default="<?= listEscape($default) ?>">
                            <?php foreach ($group['options'] as $value => $label): ?>
                                <option value="<?= listEscape($value) ?>" <?= (string) $value === (string) $selected ? 'selected' : '' ?>><?= listEscape($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif (in_array($type, ['date', 'number', 'text'], true)): ?>
                        <input type="<?= $type ?>" name="<?= listEscape($name) ?>" value="<?= listEscape($selected) ?>"
                               data-list-default="<?= listEscape($default) ?>">
                    <?php endif; ?>
                </fieldset>
            <?php endforeach; ?>
            <div class="c-list-filter-actions">
                <button type="button" class="c-list-reset" data-list-reset>リセット</button>
                <button type="submit" class="c-list-button">この条件で絞り込む</button>
            </div>
        </div>
    </details>
    <?php
}
