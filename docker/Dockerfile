FROM php:8.2-apache

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql intl

#RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
 #  mv composer.phar /usr/local/bin/composer

#RUN apt update && apt install -yqq nodejs npm