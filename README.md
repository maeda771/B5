# フォルダ構成

| フォルダ | 役割 |
| --- | --- |
| `app/` | `public/` の各画面から呼び出すPHPの処理を置く |
| `app/views/` | 複数の画面で使う部品を置く |
| `app/views/layouts/` | 共通のヘッダーとフッターを置く |
| `app/views/components/` | 画面の一部として使う部品を置く。「マイページに戻る」リンクや商品カードなど。 |
| `public/` | ブラウザで開く各画面のPHPファイルを置く。 |
| `public/css/` | ボタンや入力欄などの共通スタイルと、各画面で使うCSSファイルを置く |
| `public/images/` | 画面で使う画像を置く |
| `public/images/products/` | 商品画像を置く |
| `public/js/` | JavaScriptファイルを置く |
| `public/user/` | 利用者側の画面をまとめます。ホーム、商品一覧、商品詳細、検索、お気に入りなどのPHPファイルを置く |
| `public/user/account/` | 会員登録、ログイン、マイページ、注文履歴など、会員に関する画面を置く |
| `public/user/shopping/` | カート、購入手続き、購入完了など、購入に関する画面を置く |
| `public/admin/` | 管理者ログイン、商品管理、在庫管理、注文管理、会員管理など、管理者側の画面を置く |

# 利用者画面一覧

| 画面ID | 画面名 | パス | 画面URL | 進捗 |
| --- | --- | --- | --- | --- |
| U01 | お客様情報入力画面 | [`public/user/account/register.php`](public/user/account/register.php) | [開く](http://localhost/B5/public/user/account/register.php) | 未着手 |
| U02 | お届け先情報入力画面 | [`public/user/account/register-address.php`](public/user/account/register-address.php) | [開く](http://localhost/B5/public/user/account/register-address.php) | 未着手 |
| U03 | 新規登録確認画面 | [`public/user/account/register-confirm.php`](public/user/account/register-confirm.php) | [開く](http://localhost/B5/public/user/account/register-confirm.php) | 未着手 |
| U04 | 新規会員登録完了画面 | [`public/user/account/register-complete.php`](public/user/account/register-complete.php) | [開く](http://localhost/B5/public/user/account/register-complete.php) | 未着手 |
| U05 | ガイド一連画面 | [`public/user/guide.php`](public/user/guide.php) | [開く](http://localhost/B5/public/user/guide.php) | 未着手 |
| U06 | パスワード再設定画面（メール送信） | [`public/user/account/reset-request.php`](public/user/account/reset-request.php) | [開く](http://localhost/B5/public/user/account/reset-request.php) | 未着手 |
| U07 | パスワード再設定画面（新パスワード入力） | [`public/user/account/reset-password.php`](public/user/account/reset-password.php) | [開く](http://localhost/B5/public/user/account/reset-password.php) | 未着手 |
| U08 | パスワード再設定完了画面 | [`public/user/account/reset-complete.php`](public/user/account/reset-complete.php) | [開く](http://localhost/B5/public/user/account/reset-complete.php) | 未着手 |
| U09 | ログイン画面 | [`public/user/account/login.php`](public/user/account/login.php) | [開く](http://localhost/B5/public/user/account/login.php) | 未着手 |
| U10 | ホーム画面 | [`public/user/index.php`](public/user/index.php) | [開く](http://localhost/B5/public/user/index.php) | 未着手 |
| U11 | 商品一覧画面 | [`public/user/products.php`](public/user/products.php) | [開く](http://localhost/B5/public/user/products.php) | 未着手 |
| U12 | 商品詳細画面 | [`public/user/product.php`](public/user/product.php) | [開く](http://localhost/B5/public/user/product.php) | 未着手 |
| U13 | カート画面 | [`public/user/shopping/cart.php`](public/user/shopping/cart.php) | [開く](http://localhost/B5/public/user/shopping/cart.php) | 未着手 |
| U14 | 購入手続き画面 | [`public/user/shopping/checkout.php`](public/user/shopping/checkout.php) | [開く](http://localhost/B5/public/user/shopping/checkout.php) | 未着手 |
| U15 | 購入完了画面 | [`public/user/shopping/complete.php`](public/user/shopping/complete.php) | [開く](http://localhost/B5/public/user/shopping/complete.php) | 未着手 |
| U16 | 検索画面 | [`public/user/search.php`](public/user/search.php) | [開く](http://localhost/B5/public/user/search.php) | 未着手 |
| U17 | 並び替え・しぼり込み画面 | [`public/user/search.php`](public/user/search.php) | [開く](http://localhost/B5/public/user/search.php) | 未着手 |
| U18 | お気に入り画面 | [`public/user/favorites.php`](public/user/favorites.php) | [開く](http://localhost/B5/public/user/favorites.php) | 未着手 |
| U19 | マイページ画面 | [`public/user/account/index.php`](public/user/account/index.php) | [開く](http://localhost/B5/public/user/account/index.php) | 未着手 |
| U20 | 注文履歴画面 | [`public/user/account/orders.php`](public/user/account/orders.php) | [開く](http://localhost/B5/public/user/account/orders.php) | 未着手 |
| U21 | レビュー登録画面 | [`public/user/account/review.php`](public/user/account/review.php) | [開く](http://localhost/B5/public/user/account/review.php) | 未着手 |
| U22 | レビュー登録完了画面 | [`public/user/account/review-complete.php`](public/user/account/review-complete.php) | [開く](http://localhost/B5/public/user/account/review-complete.php) | 未着手 |
| U23 | 会員情報変更画面 | [`public/user/account/edit.php`](public/user/account/edit.php) | [開く](http://localhost/B5/public/user/account/edit.php) | 未着手 |
| U24 | 会員情報変更完了画面 | [`public/user/account/edit-complete.php`](public/user/account/edit-complete.php) | [開く](http://localhost/B5/public/user/account/edit-complete.php) | 未着手 |
| U25 | お問い合わせ画面 | [`public/user/account/contact.php`](public/user/account/contact.php) | [開く](http://localhost/B5/public/user/account/contact.php) | 未着手 |

# 管理者画面一覧

| 画面ID | 画面名 | パス | 画面URL | 進捗 |
| --- | --- | --- | --- | --- |
| A01 | 管理者ログイン画面 | [`public/admin/login.php`](public/admin/login.php) | [開く](http://localhost/B5/public/admin/login.php) | 未着手 |
| A02 | 商品一覧画面 | [`public/admin/products.php`](public/admin/products.php) | [開く](http://localhost/B5/public/admin/products.php) | 未着手 |
| A03 | 商品削除確認画面 | [`public/admin/products.php`](public/admin/products.php) | [開く](http://localhost/B5/public/admin/products.php) | 未着手 |
| A04 | 商品登録画面 | [`public/admin/product-create.php`](public/admin/product-create.php) | [開く](http://localhost/B5/public/admin/product-create.php) | 未着手 |
| A05 | カラー追加画面 | [`public/admin/product-create.php`](public/admin/product-create.php) | [開く](http://localhost/B5/public/admin/product-create.php) | 未着手 |
| A06 | 商品詳細・編集画面 | [`public/admin/product-edit.php`](public/admin/product-edit.php) | [開く](http://localhost/B5/public/admin/product-edit.php) | 未着手 |
| A07 | 在庫管理画面 | [`public/admin/stock.php`](public/admin/stock.php) | [開く](http://localhost/B5/public/admin/stock.php) | 未着手 |
| A08 | 在庫変更画面 | [`public/admin/stock-edit.php`](public/admin/stock-edit.php) | [開く](http://localhost/B5/public/admin/stock-edit.php) | 未着手 |
| A09 | 在庫変動履歴画面 | [`public/admin/stock-history.php`](public/admin/stock-history.php) | [開く](http://localhost/B5/public/admin/stock-history.php) | 未着手 |
| A10 | 注文履歴画面 | [`public/admin/orders.php`](public/admin/orders.php) | [開く](http://localhost/B5/public/admin/orders.php) | 未着手 |
| A11 | 注文詳細画面 | [`public/admin/order.php`](public/admin/order.php) | [開く](http://localhost/B5/public/admin/order.php) | 未着手 |
| A12 | 会員一覧画面 | [`public/admin/members.php`](public/admin/members.php) | [開く](http://localhost/B5/public/admin/members.php) | 未着手 |
| A13 | 会員詳細画面 | [`public/admin/member.php`](public/admin/member.php) | [開く](http://localhost/B5/public/admin/member.php) | 未着手 |



# 共通項目

| パス | 作成するもの | 進捗 |
| --- | --- | --- |
| [`app/views/layouts/header.php`](app/views/layouts/header.php) | 利用者用ヘッダー。画面タイトル、戻るリンク、ログインリンクなどを画面に合わせて表示する。 | 未着手 |
| [`app/views/layouts/footer.php`](app/views/layouts/footer.php) | 利用者用フッター。ホーム、検索、お気に入り、カート、マイページへのナビを表示する。 | 未着手 |
| [`app/views/layouts/admin-footer.php`](app/views/layouts/admin-footer.php) | 管理者用フッター。商品管理、注文管理、会員管理へのナビを表示する。 | 未着手 |
| [`app/views/components/back-to-mypage.php`](app/views/components/back-to-mypage.php) | U22とU24から読み込む「マイページに戻る」リンク。遷移先はU19。 | 未着手 |
| [`app/views/components/product-card.php`](app/views/components/product-card.php) | U11・U16・U18で使う商品カード。商品画像・商品名・価格・お気に入りの状態を表示する。 | 未着手 |
| [`public/css/common.css`](public/css/common.css) | ヘッダー、フッター、ボタン、入力欄などの共通スタイル。ボタンの色違いは追加クラスで切り替える。 | 未着手 |
| [`app/db.php`](app/db.php) | PHPからDBに接続する共通処理。 | 完了 |
| [`app/auth.php`](app/auth.php) | ログイン状態の判定を行う。ログイン済みなら会員番号、未ログインならfalseを返す。(現在は常に会員番号1を返す) | 仮実装 |