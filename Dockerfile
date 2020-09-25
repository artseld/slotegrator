FROM yiisoftware/yii2-php:7.2-apache
RUN apt-get update && \
    docker-php-ext-install sockets