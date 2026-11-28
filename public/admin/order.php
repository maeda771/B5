<!-- A11 注文詳細画面 -->
<!-- 実装する機能：会員情報・配送先・注文明細・金額・状態の表示。注文状態の変更、A10へ戻る操作。状態表記はチームで統一する。 -->

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

// 状態変更に使う共通のCSRFトークン
require_once __DIR__ . '/../../app/csrf.php';
$token = getCsrfToken();

// URLから注文番号を取得
$orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
// 注文番号が正しくなければ終了
if (!$orderId || $orderId < 1) {
    http_response_code(404);
    exit('注文が見つかりません。');
}

// 一覧から引き継いだ条件を受け取る
$returnParams = [];
foreach (['keyword', 'sort', 'status', 'year', 'month', 'page', 'per_page'] as $key) {
    $value = $_SERVER['REQUEST_METHOD'] === 'POST'
        ? ($_POST['return_' . $key] ?? null)
        : ($_GET['return_' . $key] ?? null);
    if (is_string($value)) {
        $returnParams[$key] = $value;
    }
}
// 一覧へ戻るURLにページと条件をつける
$returnUrl = 'orders.php';
if ($returnParams) {
    $returnUrl .= '?' . http_build_query($returnParams);
}

// 注文情報を取得
require __DIR__ . '/../../app/admin/order-detail/data.php';

// 注文状態を変更
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/../../app/admin/order-detail/save-status.php';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/order-detail.css">
    <script src="../js/order-detail.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>注文詳細画面</title>
</head>
<body class="has-header has-footer">
    <!-- ヘッダーの表示 -->
    <?php 
    require __DIR__ . '/../../app/views/layouts/admin-header.php';
    renderHeader('注文詳細','back',$returnUrl);
     ?>
    
    <main class="c-detail-page">
        <!-- 状態変更に失敗した場合のメッセージ -->
        <?php if ($errors): ?>
            <div role="alert">
                <?php foreach ($errors as $error): ?>
                    <p>
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- 注文情報 -->
        <section class="c-detail-section">
            <h2>注文情報</h2>

            <dl class="c-detail-info">
                <dt>注文番号</dt>
                <dd><?= (int) $order['order_id'] ?></dd>

                <dt>注文日時</dt>
                <dd>
                    <?= htmlspecialchars(
                        date('Y/m/d H:i', strtotime($order['order_datetime'])),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </dd>

                <dt>状態</dt>
                <dd>
                    <form
                        id="order-status-form"
                        action="order.php?order_id=<?= (int) $orderId ?>"
                        method="post"
                    >
                        <input type="hidden" name="token"
                            value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

                        <?php foreach ($returnParams as $key => $value): ?>
                            <input
                                type="hidden"
                                name="return_<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                                value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"
                            >
                        <?php endforeach; ?>

                        <select
                            name="status"
                            class="order-detail-status"
                            aria-label="注文の状態"
                            data-status="<?= htmlspecialchars(
                                $order['order_status'], ENT_QUOTES, 'UTF-8'
                            ) ?>"
                        >
                            <?php foreach (['注文受付', '発送準備中', '発送済み'] as $status): ?>
                                <option
                                    value="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>"
                                    <?= $order['order_status'] === $status ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </dd>

                <dt>合計金額</dt>
                <dd>¥<?= number_format($order['billing_amount']) ?></dd>
            </dl>
        </section>

        <!-- 会員情報 -->
        <section class="c-detail-section">
            <h2>会員情報</h2>

            <dl class="c-detail-info">
                <dt>会員氏名</dt>
                <dd>
                    <?= htmlspecialchars(
                        $order['member_name'], ENT_QUOTES, 'UTF-8'
                    ) ?>
                </dd>
                <dt>会員番号</dt>
                <dd><?= (int) $order['member_id'] ?></dd>

                <dt>メール</dt>
                <dd>
                    <?= htmlspecialchars(
                        $order['email'], ENT_QUOTES, 'UTF-8'
                    ) ?>
                </dd>

                <dt>電話</dt>
                <dd>
                    <?= htmlspecialchars(
                        $order['phone'], ENT_QUOTES, 'UTF-8'
                    ) ?>
                </dd>
            </dl>
        </section>

        <!-- 注文商品 -->
        <section class="c-detail-section">
            <h2>注文商品</h2>

            <table class="c-detail-table order-detail-table">
                <thead>
                    <tr>
                        <th scope="col">商品番号</th>
                        <th scope="col">数量</th>
                        <th scope="col">金額</th>
                        <th scope="col">サイズ</th>
                        <th scope="col">カラー</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td><?= (int) $item['product_id'] ?></td>
                            <td><?= (int) $item['quantity'] ?></td>
                            <td>
                                ¥<?= number_format(
                                    $item['unit_price'] * $item['quantity']
                                ) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    $item['size'], ENT_QUOTES, 'UTF-8'
                                ) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    $item['color_name'], ENT_QUOTES, 'UTF-8'
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$orderItems): ?>
                        <tr>
                            <td colspan="5">購入商品の明細がありません。</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="order-detail-total">
                <span>合計</span>
                <strong>
                    ¥<?= number_format(array_sum(array_map(
                        static function (array $item): int {
                            return (int) $item['unit_price']
                                * (int) $item['quantity'];
                        },
                        $orderItems
                    ))) ?>
                </strong>
            </div>
        </section>
    </main>
</body>
</html>
