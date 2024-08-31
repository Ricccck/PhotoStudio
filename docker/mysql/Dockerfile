FROM mysql:latest

ENV TZ=Asia/Tokyo

COPY ./docker/mysql/my.cnf /etc/mysql/conf.d/my.cnf
COPY ./docker/mysql/data /docker-entrypoint-initdb.d

CMD [ "mysqld", "--character-set-server=utf8", "--collation-server=utf8_unicode_ci" ]