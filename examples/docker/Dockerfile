FROM nginx:latest

COPY openssl.cnf /etc/ssl/openssl.localhost.cnf
COPY default.conf /etc/nginx/conf.d/

RUN rm -rf /usr/share/nginx/html/index.html \
    && chown -R www-data:www-data /usr/share/nginx/html

RUN apt-get update \
    && apt-get install php7.0 php-curl openssl -y

RUN openssl req \
    -config /etc/ssl/openssl.localhost.cnf \
    -x509 \
    -nodes \
    -days 365 \
    -sha256 \
    -newkey rsa:2048 \
    -keyout /etc/ssl/server.key \
    -out /etc/ssl/server.crt
