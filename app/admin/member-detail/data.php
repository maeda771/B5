<?php
// 会員詳細画面用の情報を取得

// 会員テーブル
$stmt = $pdo->prepare('
    SELECT
        members.member_id,
        members.member_name,
        members.email,
        members.registration_date,
        members.phone,
        members.postal_code,
        members.address,
        members.building_name
    FROM members
    WHERE members.member_id = ?
');
$stmt->execute([$memberId]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

// 指定された会員が存在しなければ終了
if ($member === false) {
    http_response_code(404);
    exit('会員が見つかりません。');
}

// この会員の注文一覧取得
$stmt = $pdo->prepare('
    SELECT
        orders.order_id,
        orders.order_datetime,
        orders.billing_amount,
        orders.order_status
    FROM orders
    WHERE orders.member_id = ?
    ORDER BY
        orders.order_datetime DESC,
        orders.order_id DESC
');
$stmt->execute([$memberId]);
$memberOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);