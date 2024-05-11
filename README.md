# PhotoStudio

## envファイルの準備
`.env`ファイルを作成し、以下の定数を定義する

```
MYSQL_ROOT_PASSWORD=
MYSQL_DATABASE=
MYSQL_USER=
MYSQL_PASSWORD=
```

## Dockerファイルの起動
`docker-compose up -d --build`でDockerファイルを起動


## データベースの作成
`http://localhost:8888/`にアクセス

`.env`ファイルに記入したユーザー名、パスワードを使用してログインし
データベースとテーブルがあることを確認する

もし存在していなかった場合は
`dicker/mysql/sql`ファイル内のクエリ文を用いてテーブルを作成する


## Dockerファイルの削除
`docker-compose down`でDockerファイルを削除

