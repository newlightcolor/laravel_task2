## Name
***
laravel_task2

## Overview
***
Laravel10

## Requirement
***
### 実行環境の想定
https://github.com/newlightcolor/LEMP-8.1

### 初期設定
**installation.shを実行**
```
sh installation.sh
```
- .envファイル作成
- composer パッケージインストール
- ファイル権限を全員に付与


## Usage
***
#### データベースにテーブル作成
```
php artisan migrate
```

#### 日記のテストデータ登録
```
php artisan db:seed
```