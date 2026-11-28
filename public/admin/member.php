<!-- A13 会員詳細画面 -->
<!-- 実装する機能：対象会員の情報と注文情報の表示、A12へ戻る操作。 -->

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

// URLから会員番号を取得
$memberId = filter_input(INPUT_GET, 'member_id', FILTER_VALIDATE_INT);
// 会員番号が正しくなければ終了
if (!$memberId || $memberId < 1) {
    http_response_code(404);
    exit('会員が見つかりません。');
}

// 一覧から引き継いだ条件を受け取る
$returnParams = [];
foreach (['keyword', 'sort', 'year', 'month', 'page', 'per_page'] as $key) {
    $value = $_GET['return_' . $key] ?? null;

    if (is_string($value)) {
        $returnParams[$key] = $value;
    }
}
// 一覧へ戻るURLにページと条件をつける
$returnUrl = 'members.php';
if ($returnParams) {
    $returnUrl .= '?' . http_build_query($returnParams);
}

// 注文情報を取得
require __DIR__ . '/../../app/admin/member-detail/data.php';
?>

 <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>会員詳細画面</title>
</head>
<body class="has-header has-footer">
    <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('会員詳細','back',$returnUrl);
     ?>
    
    <main class="c-detail-page">
        <!-- お客様情報 -->
        <section class="c-detail-section">
            <h2>お客様情報</h2>
            <dl class="c-detail-info">
                <dt>会員番号</dt>
                <dd><?= (int) $member['member_id'] ?></dd>

                <dt>お名前</dt>
                <dd>
                    <?= htmlspecialchars(
                        $member['member_name'], ENT_QUOTES, 'UTF-8'
                    ) ?>
                </dd>
                <dt>メール</dt>
                <dd>
                    <?= htmlspecialchars(
                        $member['email'], ENT_QUOTES, 'UTF-8'
                    ) ?>
                </dd>
                <dt>電話</dt>
                <dd>
                    <?= htmlspecialchars(
                        $member['phone'], ENT_QUOTES, 'UTF-8'
                    ) ?>
                </dd>
            </dl>
        </section>

        <!-- お届け先情報 -->
        <section class="c-detail-section">
            <h2>お届け先情報</h2>
            <dl class="c-detail-info">
                <dt>郵便番号</dt>
                <dd>
                    <?= htmlspecialchars(
                        substr($member['postal_code'], 0, 3)
                            . '-' . substr($member['postal_code'], 3),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </dd>
                <dt>住所</dt>
                <dd>
                    <?= htmlspecialchars(
                        $member['address'], ENT_QUOTES, 'UTF-8'
                    ) ?>
                </dd>
                <dt>建物名等</dt>
                <dd>
                    <?= htmlspecialchars(
                        $member['building_name'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </dd>
            </dl>
        </section>

        <!-- 注文履歴 -->
        <section class="c-detail-section">
            <h2>注文履歴</h2>
            <table class="c-detail-table">
                <thead>
                    <tr>
                        <th scope="col">注文番号</th>
                        <th scope="col">日時</th>
                        <th scope="col">金額</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($memberOrders as $memberOrder): ?>
                        <tr>
                            <td>
                                <?= (int) $memberOrder['order_id'] ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    date(
                                        'Y/m/d H:i',
                                        strtotime($memberOrder['order_datetime'])
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                            <td>
                                ¥<?= number_format($memberOrder['billing_amount']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$memberOrders): ?>
                        <tr>
                            <td colspan="3">注文履歴はありません。</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
