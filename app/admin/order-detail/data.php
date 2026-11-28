<?php
// 注文詳細画面用の注文情報を取得

// 注文テーブル
$stmt = $pdo->prepare('
    SELECT
        orders.order_id,
        orders.member_id,
        orders.order_datetime,
        orders.billing_amount,
        orders.order_status,
        members.member_name,
        members.email,
        members.phone
    FROM orders
    INNER JOIN members
        ON members.member_id = orders.member_id
    WHERE orders.order_id = ?
');
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// 指定された注文が存在しなければ終了
if ($order === false) {
    http_response_code(404);
    exit('注文が見つかりません。');
}

// 注文詳細テーブル
$stmt = $pdo->prepare('
    SELECT
        order_details.order_detail_id,
        order_details.quantity,
        order_details.unit_price,
        products.product_id,
        products.product_name,
        products.image,
        product_details.size,
        colors.color_name,
        colors.color_code
    FROM order_details
    INNER JOIN product_details
        ON product_details.product_details_id
            = order_details.product_details_id
    INNER JOIN products
        ON products.product_id = product_details.product_id
    INNER JOIN colors
        ON colors.color_id = product_details.color_id
    WHERE order_details.order_id = ?
    ORDER BY order_details.order_detail_id
');
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);