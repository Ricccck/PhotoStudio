# PhotoStudio
ロゴ
![logo-black](https://github.com/user-attachments/assets/c2bc1eed-982c-4101-b39d-f99bd2fe92ce)
カメラのシャッターを意識して作成したロゴ

# はじめに
本アプリケーションは、写真を販売・購入するC(B)toCサイトです。<br>
販売者(クライアント)は、サイトに登録を行った後、画像を投稿することができます。
画像投稿後、管理者（アドミン）が投稿された画像を審査し、承認後サイトに画像が表示されます。<br>
購入者(カスタマー)は、サイトに登録を行った後、投稿された画像を検索することができ、自分にあった写真を見つけることができます。
選択した写真をカートに登録・購入後、ダウンロードを行えるようになります。

↓本サイトの発想を元にFE/BEを分けて作成したReact, Laravel版アプリケーションはこちら
GitHub：

# 言語・フレームワーク
### メイン言語
[![My Skills](https://skillicons.dev/icons?i=php)](https://skillicons.dev)　PHP
### フロント言語
[![My Skills](https://skillicons.dev/icons?i=html)](https://skillicons.dev)　HTML
[twig](https://twig.symfony.com/)
### UI
[![My Skills](https://skillicons.dev/icons?i=bootstrap)](https://skillicons.dev)　　Bootstrap  
### データベース
[![My Skills](https://skillicons.dev/icons?i=mysql)](https://skillicons.dev)　　mySQL  
### コンテナ
[![My Skills](https://skillicons.dev/icons?i=docker)](https://skillicons.dev)　　Docker  
 
[yubinbango](https://github.com/yubinbango/yubinbango)


#環境構築

## 0. Dockerをインストール

## 1. ローカル環境にクローン
`git clone https://github.com/Ricccck/PhotoStudio.git`

## 2. 環境変数を変更
- .envファイルを作成
`mkdir .env`
- .envの内容を以下に変更（NAME, USER, PASSは自由に変更可能）
```
DB_HOST=localhoset
DB_NAME=photostudio_db
DB_USER=photostudio_user
DB_PASS=photostudio_pass
ROOT_PASSWORD=root
```
- .envを変更した場合、`src/app/lib/Bootstrap.class.php`の内容を変更
```php
const DB_HOST = 'mysql';
const DB_NAME = 'your_db_name';
const DB_USER = 'your_db_user';
const DB_PASS = 'yout_db_pass';
```

## 3. docker環境構築・稼働
`docker-compose build`
`docker-compose up`

## 4.　サイトにアクセス
`http://localhost:8000/`



# Feature
### クライアント
- ユーザー登録
- マイページ
- 画像閲覧
- 投稿画像登録
- 投稿画像一覧

### カスタマー
- ユーザー登録機能
- マイページ
- 画像閲覧・カート追加
- 画像購入
- 購入画像一覧・ダウンロード

### アドミン
- 投稿画像一覧
- 投稿画像詳細・承認


# 開発者
<table>
    <td align="center"><a href="https://github.com/Ricccck"><img src="https://avatars.githubusercontent.com/u/99594245?v=4" width="200px;" alt=""/><br /><sub><b>臼井 陸
</b></sub></a></td>
</table>
