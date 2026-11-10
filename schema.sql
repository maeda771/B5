-- B5 高齢者向けECサイト | テーブル作成用
-- phpMyAdminで空のデータベースを選択してから、このファイルをインポートする。
-- 既存テーブルの変更やデータ投入には使用しない。
-- 出典 05_データベース設計書.xlsx の各テーブル定義シート。
-- 削除・更新時の連鎖動作は設計書に指定がないため、外部キーの既定動作を使用する。

SET NAMES utf8mb4;

-- 会員
CREATE TABLE `members` (
    `member_id` INT NOT NULL AUTO_INCREMENT,
    `member_name` VARCHAR(30) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `registration_date` DATE NOT NULL,
    `phone` VARCHAR(11) NOT NULL,
    `postal_code` VARCHAR(7) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `building_name` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`member_id`),
    UNIQUE KEY `uq_member_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 商品
CREATE TABLE `products` (
    `product_id` INT NOT NULL AUTO_INCREMENT,
    `product_name` VARCHAR(30) NOT NULL,
    `category` VARCHAR(10) NOT NULL,
    `product_price` INT NOT NULL,
    `description` VARCHAR(100) NOT NULL,
    `image` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` DATE NOT NULL,
    PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- カラー
CREATE TABLE `colors` (
    `color_id` INT NOT NULL AUTO_INCREMENT,
    `color_name` VARCHAR(20) NOT NULL,
    `color_code` VARCHAR(6) NOT NULL,
    PRIMARY KEY (`color_id`),
    UNIQUE KEY `uq_colors_name_code` (`color_name`, `color_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 管理者
CREATE TABLE `admins` (
    `admin_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(30) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`admin_id`),
    UNIQUE KEY `uq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 商品詳細
CREATE TABLE `product_details` (
    `product_details_id` INT NOT NULL AUTO_INCREMENT,
    `product_id` INT NOT NULL,
    `color_id` INT NOT NULL,
    `size` VARCHAR(2) NOT NULL,
    `stock_quantity` INT NOT NULL DEFAULT 0,
    PRIMARY KEY (`product_details_id`),
    UNIQUE KEY `uq_product_details_variant` (`product_id`, `color_id`, `size`),
    KEY `idx_product_details_color` (`color_id`),
    CONSTRAINT `fk_product_details_product`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
    CONSTRAINT `fk_product_details_color`
        FOREIGN KEY (`color_id`) REFERENCES `colors` (`color_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- お気に入り
CREATE TABLE `favorites` (
    `member_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    PRIMARY KEY (`member_id`, `product_id`),
    KEY `idx_favorites_product` (`product_id`),
    CONSTRAINT `fk_favorites_member`
        FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
    CONSTRAINT `fk_favorites_product`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- カート
CREATE TABLE `carts` (
    `member_id` INT NOT NULL,
    `product_details_id` INT NOT NULL,
    `cart_quantity` INT NOT NULL,
    PRIMARY KEY (`member_id`, `product_details_id`),
    KEY `idx_carts_product_details` (`product_details_id`),
    CONSTRAINT `fk_carts_member`
        FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
    CONSTRAINT `fk_carts_product_details`
        FOREIGN KEY (`product_details_id`) REFERENCES `product_details` (`product_details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 注文
CREATE TABLE `orders` (
    `order_id` INT NOT NULL AUTO_INCREMENT,
    `member_id` INT NOT NULL,
    `order_datetime` DATETIME NOT NULL,
    `billing_amount` INT NOT NULL,
    `order_status` VARCHAR(10) NOT NULL DEFAULT '注文受付',
    `shipping_postal_code` VARCHAR(7) NOT NULL,
    `shipping_address` VARCHAR(255) NOT NULL,
    `shipping_building_name` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`order_id`),
    KEY `idx_orders_member_date` (`member_id`, `order_datetime`),
    CONSTRAINT `fk_orders_member`
        FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 注文詳細
-- idx_order_details_product_details の列名は属性情報に従い product_details_id とする。
CREATE TABLE `order_details` (
    `order_detail_id` INT NOT NULL AUTO_INCREMENT,
    `order_id` INT NOT NULL,
    `product_details_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `unit_price` INT NOT NULL,
    PRIMARY KEY (`order_detail_id`),
    KEY `idx_order_details_order` (`order_id`),
    KEY `idx_order_details_product_details` (`product_details_id`),
    CONSTRAINT `fk_order_details_order`
        FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
    CONSTRAINT `fk_order_details_product_details`
        FOREIGN KEY (`product_details_id`) REFERENCES `product_details` (`product_details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 在庫変動履歴
CREATE TABLE `inventory_change_logs` (
    `history_id` INT NOT NULL AUTO_INCREMENT,
    `product_details_id` INT NOT NULL,
    `change_type` VARCHAR(20) NOT NULL,
    `quantity_before` INT NOT NULL,
    `quantity_after` INT NOT NULL,
    `quantity_change` INT NOT NULL,
    `memo` VARCHAR(255) NULL DEFAULT NULL,
    `order_id` INT NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`history_id`),
    KEY `idx_inventory_history_variant_date` (`product_details_id`, `created_at`),
    KEY `idx_inventory_history_order` (`order_id`),
    CONSTRAINT `fk_inventory_change_logs_product_details`
        FOREIGN KEY (`product_details_id`) REFERENCES `product_details` (`product_details_id`),
    CONSTRAINT `fk_inventory_change_logs_order`
        FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- レビュー
-- review_id は設計書に自動採番の指定がないため、登録時に値を指定する。
CREATE TABLE `reviews` (
    `review_id` INT NOT NULL,
    `member_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `rating` INT NOT NULL,
    PRIMARY KEY (`review_id`),
    UNIQUE KEY `uq_reviews_member_product` (`member_id`, `product_id`),
    KEY `idx_reviews_product` (`product_id`),
    CONSTRAINT `fk_reviews_member`
        FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
    CONSTRAINT `fk_reviews_product`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
