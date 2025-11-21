# もぎたてフリマ（商品管理アプリ）

## 概要
```
商品を登録・編集・削除・検索できるフリマサイト風の Web アプリケーションです。
商品には複数の季節（季節タグ）を紐付けでき、商品一覧から季節や価格で絞り込み検索が可能です。
```
## セットアップ手順
### 1. リポジトリのクローン
```bash
git clone git@github.com:yurinaniko/mogitate-flea-market.git
cd mogitate-flea-market
```

### 2. Docker 起動

```
docker compose up -d --build
docker compose exec php bash
```

### 3. Composer インストール

```
composer install
```

### 4. .env ファイル作成

```
cp .env.example .env
```

### 5. .env 設定

```dotenv
APP_NAME=laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

### 6. アプリケーションキー生成

```bash
php artisan key:generate
```

### 7. データベース設定 & マイグレーション + シーディング

```
php artisan migrate --seed
```

### 8. アプリケーション確認

```
http://localhost:8000
```

### 備考 M1/M2 Mac を使用している場合

```
Dockerビルド時に以下のエラーが発生することがあります：
no matching manifest for linux/arm64/v8 in the manifest list entries
その場合は `docker-compose.yml` の `mysql` サービス内に `platform` を追記してください。

yaml
mysql:
  platform: linux/x86_64
  image: mysql:8.0
  container_name: mysql
  environment:
    MYSQL_ROOT_PASSWORD: root
    MYSQL_DATABASE: laravel_db
    MYSQL_USER: laravel_user
    MYSQL_PASSWORD: laravel_pass
```

## 使用技術

```
| 種類                     | バージョン  |
| ----------------------- | ---------- |
| PHP                     | 8.1        |
| Laravel                 | 10.x       |
| MySQL                   | 8.0        |
| Docker / docker-compose | 最新        |
| Nginx                   | 1.25       |
| Blade / BEM CSS         | UI 実装     |

```

## 機能一覧
```
・商品一覧表示
- 商品検索機能（キーワード部分一致）
- 価格順並び替え機能（安い順 / 高い順）
- ページネーション

・商品詳細
- 商品編集（画像差し替え / 画像削除）
- 商品削除

・商品登録（画像アップロード / バリデーション / 季節の複数選択）

```
## 画面一覧

```
| 画面       | HTTP method | path                   　　　　　　　　　　　　 |
|-----------|-------------|---------------------------------------------|　　　　　　　
| 商品一覧   | GET         | /products                                   |
| 商品詳細   | GET         | /products/{id}                              |
| 商品登録   | GET         | /products/create                            |
| 登録実行   | POST        | /products                                   |
| 商品編集   | GET         | /products/{id}/edit                         |
| 編集更新   | PUT         | /products/{id}                              |
| 削除      | DELETE      | /products/{id}                              |
| 検索      | GET         | /products?keyword={keyword}&sort={sort}     |

```

### ER 図

![ER図](src/docs/er.png)

### テーブル仕様書
```
- productsテーブル（商品）
| カラム名      | 型              | NULL     | 初期値             | 備考                    |
|-------------|-----------------|----------|-------------------|-------------------------|
| id          | BIGINT          | NOT NULL | auto_increment    | 主キー                   |
| name        | VARCHAR(255)    | NOT NULL | —                 | 商品名                   |
| price       | INT             | NOT NULL | —                 | 0〜10000 バリデーション    |
| description | VARCHAR(120)    | NOT NULL | —                 | 商品説明（120文字以内）     |
| image       | VARCHAR(255)    | NOT NULL | —                 | 商品画像ファイル名          |
| created_at  | TIMESTAMP       | —        | CURRENT_TIMESTAMP | 作成日時                  |
| updated_at  | TIMESTAMP       | —        | CURRENT_TIMESTAMP | 更新日時                  |
```
```
- seasonsテーブル（季節）
| カラム名     | 型           | NULL      | 初期値              | 備考              |
|-------------|-------------|-----------|--------------------|-------------------|
| id          | BIGINT      | NOT NULL  | auto_increment     | 主キー             |
| name        | VARCHAR(50) | NOT NULL  |                    | 春 / 夏 / 秋 / 冬  |
| created_at  | TIMESTAMP   |           | CURRENT_TIMESTAMP  | 作成日時           |
| updated_at  | TIMESTAMP   |           | CURRENT_TIMESTAMP  | 更新日時           |
```
```
- product_seasonテーブル（中間テーブル）
| カラム名      | 型        | NULL     | 備考                         |
|-------------|-----------|----------|------------------------------|
| id          | BIGINT    | NOT NULL | auto_increment / 主キー       |
| product_id  | BIGINT    | NOT NULL | 外部キー → products.id        |
| season_id   | BIGINT    | NOT NULL | 外部キー → seasons.id         |
| created_at  | TIMESTAMP |          |                              |
| updated_at  | TIMESTAMP |          |                              |
```
```
- リレーション
| モデル    | 関係            | モデル    |
|----------|----------------|----------|
| Product  | belongsToMany  | Season   |
| Season   | belongsToMany  | Product  |
商品（Product）と季節（Season）は多対多（N:N）関係であり、
中間テーブル `product_season` を用いて関連付けを行っています。
```
## 動作確認URL
```
http://localhost:8000
```
### 実装した応用機能
```
- 商品画像は storage/app/public に保存し、`php artisan storage:link` によるシンボリックリンクで公開
- 商品の季節は複数選択可能（belongsToMany / 中間テーブル product_season）
- 編集画面では、season の入力保持を実現（初回は product 値、バリデーションエラー時は old 優先）
- 商品検索と価格並び替え機能を同時に適用可能（クエリパラメータ保持による state 維持）
- 画像削除専用モーダルを実装（画像のみ削除 / 商品削除 / キャンセル切替）
```
### 備考
```
・検索フォームは丸型デザインとし、フォームUIとの差別化を行うことで視認性を向上
・入力フォームは共通コンポーネント（.form-input）として管理し、登録・編集画面間で統一感を持たせた
```