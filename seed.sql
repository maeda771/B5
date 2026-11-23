SET NAMES utf8mb4;
START TRANSACTION;

-- 商品15件
INSERT INTO products
    (product_id, product_name, category, product_price, description, image, created_at)
VALUES
    (1, '綿の長袖シャツ', 'トップス', 1980, '柔らかい綿素材の長袖シャツです。', 'images/products/test-shirt.png', '2026-11-01'),
    (2, '前開きカーディガン', 'トップス', 2980, '羽織りやすい前開きのカーディガンです。', 'images/products/test-shirt.png', '2026-11-03'),
    (3, 'ウエストゴムのパンツ', 'ボトムス', 2480, 'ウエストがゴムで着脱しやすいパンツです。', 'images/products/test-shirt.png', '2026-11-05'),
    (4, '軽量ジャケット', 'アウター', 4980, '軽くて外出時に羽織りやすいジャケットです。', 'images/products/test-shirt.png', '2026-11-07'),
    (5, '軽量ニットベスト', 'トップス', 2680, '重ね着しやすい軽いニットベストです。', 'images/products/test-shirt.png', '2026-11-09'),
    (6, 'ゆったり半袖シャツ', 'トップス', 1680,'ゆったりとした着心地の半袖シャツです。','images/products/test-shirt.png', '2026-11-10'),
    (7, 'あったかトレーナー', 'トップス', 3280,'肌寒い日に着やすいトレーナーです。','images/products/test-shirt.png', '2026-11-11'),
    (8, 'ストレッチパンツ', 'ボトムス', 2980,'伸縮性があり動きやすいパンツです。','images/products/test-shirt.png', '2026-11-12'),
    (9, 'ゆったりルームパンツ', 'ボトムス', 1980,'お部屋でくつろぐときに使いやすいパンツです。','images/products/test-shirt.png', '2026-11-13'),
    (10, '軽量ベスト', 'アウター', 3980,'軽くて重ね着しやすい前開きベストです。','images/products/test-shirt.png', '2026-11-14'),
    (11, '防風ジャケット', 'アウター', 5980,'風のある日の外出に使いやすいジャケットです。','images/products/test-shirt.png', '2026-11-15'),
    (12, '軽量ウォーキングシューズ', 'シューズ', 3980,'軽くて日々の散歩に使いやすい靴です。','images/products/test-shirt.png', '2026-11-16'),
    (13, '面ファスナーシューズ', 'シューズ', 4480,'面ファスナーで脱ぎ履きしやすい靴です。','images/products/test-shirt.png', '2026-11-17'),
    (14, 'やわらかマフラー', 'その他・小物', 1480,'首元を暖かく包む柔らかいマフラーです。','images/products/test-shirt.png', '2026-11-18'),
    (15, '軽量お出かけ帽子', 'その他・小物', 1980,'散歩や買い物に使いやすい軽い帽子です。','images/products/test-shirt.png', '2026-11-19');

-- カラー6件。カラーコードは # を含まない6桁。
INSERT INTO colors (color_id, color_name, color_code)
VALUES
    (1, '白',     'FFFFFF'),
    (2, '紺',     '243B55'),
    (3, 'グレー', '808080'),
    (4, '黒',     '202020'),
    (5, 'ベージュ', 'D4C1A3'),
    (6, '青',     '3C78B4');

-- 商品詳細45件。各商品3件。商品2のベージュ・Mで在庫切れを確認できる。
INSERT INTO product_details (product_id, color_id, size, stock_quantity)
VALUES
    (1, 1, 'S', 8),
    (1, 1, 'M', 12),
    (1, 2, 'L', 5),
    (2, 3, 'S', 4),
    (2, 5, 'M', 0),
    (2, 5, 'L', 6),
    (3, 4, 'S', 7),
    (3, 4, 'M', 11),
    (3, 5, 'L', 3),
    (4, 2, 'S', 2),
    (4, 6, 'M', 9),
    (4, 6, 'L', 4),
    (5, 3, 'S', 6),
    (5, 6, 'M', 8),
    (5, 6, 'L', 3),
    (6, 1, 'S', 10),
    (6, 1, 'M', 12),
    (6, 6, 'L', 8),
    (7, 3, 'M', 6),
    (7, 3, 'L', 5),
    (7, 2, 'LL', 4),
    (8, 4, 'S', 7),
    (8, 4, 'M', 9),
    (8, 5, 'L', 8),
    (9, 3, 'M', 0),
    (9, 3, 'L', 1),
    (9, 2, 'LL', 2),
    (10, 5, 'M', 5),
    (10, 5, 'L', 4),
    (10, 4, 'LL', 3),
    (11, 2, 'M', 0),
    (11, 2, 'L', 0),
    (11, 4, 'LL', 0),
    (12, 4, 'S', 5),
    (12, 4, 'M', 7),
    (12, 3, 'L', 6),
    (13, 5, 'S', 2),
    (13, 5, 'M', 4),
    (13, 4, 'L', 3),
    (14, 2, 'F', 15),
    (14, 3, 'F', 20),
    (14, 5, 'F', 10),
    (15, 5, 'F', 1),
    (15, 2, 'F', 2),
    (15, 4, 'F', 3);

-- テスト会員1名。
-- 会員       member@example.test / pass1234
INSERT INTO members
    (member_name, email, password_hash, registration_date, phone, postal_code, address, building_name)
VALUES
    ('テスト会員', 'member@example.test', '$2y$12$hiDC9u9yKMM5vVe483rNneNEX7bbyaZkdHGRz7dwtaUSNYCwyBCh2',
     '2026-11-01', '00000000000', '0000000', 'テスト市サンプル町1-2-3', NULL);

-- テスト管理者1名。
-- 管理者     admin@example.test  / pass1234
INSERT INTO admins (name, email, password_hash)
VALUES ('テスト管理者', 'admin@example.test', '$2y$12$Fg5UBufvxJ54fwsVk0eMzOu7rRQ8YkaIOjFAJMffj1UxH8jMB9i9W');

COMMIT;
