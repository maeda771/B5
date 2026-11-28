<?php
// 列名とデータを受け取り、一覧を表示
require_once __DIR__ . '/helpers.php';

function renderListTable(array $columns, array $rows, array $config = []): void
{
    $checkbox = $config['checkbox'] ?? null;
    $detail = $config['detail'] ?? null;
    $idKey = $config['idKey'] ?? 'id';
    $labelKey = $config['labelKey'] ?? $idKey;
    $columnCount = count($columns) + ($checkbox !== null ? 1 : 0) + ($detail !== null ? 1 : 0);
    ?>
    <div class="c-list-result">
        <table class="c-list-table">
            <colgroup>
                <?php if ($checkbox !== null): ?><col class="c-list-col-check"><?php endif; ?>
                <?php foreach ($columns as $column): ?><col class="<?= listEscape($column['class'] ?? '') ?>"><?php endforeach; ?>
                <?php if ($detail !== null): ?><col class="c-list-col-detail"><?php endif; ?>
            </colgroup>
            <thead><tr>
                <?php if ($checkbox !== null): ?><th scope="col"><span class="c-list-visually-hidden">選択</span></th><?php endif; ?>
                <?php foreach ($columns as $column): ?><th scope="col" class="<?= listEscape($column['class'] ?? '') ?>"><?= listEscape($column['label']) ?></th><?php endforeach; ?>
                <?php if ($detail !== null): ?><th scope="col"><span class="c-list-visually-hidden">詳細</span></th><?php endif; ?>
            </tr></thead>
            <tbody>
                <?php if (!$rows): ?>
                    <tr><td colspan="<?= $columnCount ?>" class="c-list-empty"><?= listEscape($config['empty'] ?? '条件に一致するデータはありません。') ?></td></tr>
                <?php else: foreach ($rows as $row): ?>
                    <tr>
                        <?php if ($checkbox !== null): ?>
                            <td><input type="checkbox" name="<?= listEscape($checkbox) ?>[]" value="<?= listEscape($row[$idKey]) ?>"></td>
                        <?php endif; ?>
                        <?php foreach ($columns as $column):
                            $value = $row[$column['key']] ?? '';
                            $format = $column['format'] ?? 'text';
                            if ($format === 'yen') $value = '¥' . number_format((int) $value);
                            elseif ($format === 'number') $value = number_format((int) $value);
                            elseif (is_callable($format)) $value = $format($value, $row);
                            ?>
                            <td class="<?= listEscape($column['class'] ?? '') ?>">
                                <?php if ($column['badge'] ?? false): ?>
                                    <span class="c-list-badge"
                                        data-value="<?= listEscape($value) ?>">
                                        <?= listEscape($value) ?>
                                    </span>
                                <?php else: ?>
                                    <?= listEscape($value) ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                        <?php if ($detail !== null): ?>
                            <td><a class="c-list-detail" href="<?= listEscape($detail($row)) ?>"><i class="fas fa-chevron-right"></i></a></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
