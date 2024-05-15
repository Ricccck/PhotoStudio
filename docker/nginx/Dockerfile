FROM nginx:latest

# Nginxの設定ファイルを追加
ADD ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# ソースコードをNginxのデフォルトのHTMLディレクトリにコピー
ADD ./src /usr/share/nginx/html
ADD ./twig /user/share/nginx/html/twig

# Nginxを起動するためのCMD命令を追加
CMD ["nginx", "-g", "daemon off;"]