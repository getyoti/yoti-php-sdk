FROM nginx:latest

COPY default.conf /etc/nginx/conf.d/
COPY keys/server.key /etc/ssl/server.key
COPY keys/server.crt /etc/ssl/server.crt

RUN rm -rf /usr/share/nginx/html/index.html \
    && chown -R www-data:www-data /usr/share/nginx/html

RUN apt-get update && apt-get install -y \
    && apt-get install curl php zip unzip -y \
    && curl -sL https://getcomposer.org/installer | php -- --install-dir /usr/bin --filename composer