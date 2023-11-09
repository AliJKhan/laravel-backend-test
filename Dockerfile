FROM php:8.2-apache
COPY . /app
WORKDIR /app

RUN apt-get update
RUN apt install zip unzip
RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install pcov && \
    docker-php-ext-enable pcov
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
