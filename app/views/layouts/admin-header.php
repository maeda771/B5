<!-- 
管理者用ヘッダー

　使い方
ファイルの読み込み↴
require __DIR__ . '/../../app/views/layouts/admin-header.php';
関数呼び出し↴
renderHeader(表示タイトル、アイコン種別、戻り先のリンク); 

アイコン種別
- 'back': 戻るアイコンを表示
- 'none': 何も表示しない
-->

<?php
function renderHeader(
    string $pageTitle, 
    string $icon = 'none',
    ?string $backUrl = null
): void
{
?>
    <header class="header">
        <div class="header-inner">
            <div class="header-title">
                <?php if ($icon === 'back' && $backUrl !== null): ?>
                    <a class="header-back"  
                       href="<?= htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8') ?>"
                    >
                        <i class="fas fa-chevron-left"></i>
                        <span><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></span>
                        </a>
                <?php else: ?>
                    <span style="margin-left: 30px;"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>
        </div>
    </header>
<?php
}