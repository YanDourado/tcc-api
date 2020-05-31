FROM php:7.4-fpm-alpine

RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
    docker-php-ext-configure gd --with-freetype=/usr/include/  --with-jpeg=/usr/include/ && \
    docker-php-ext-install -j$(nproc) tokenizer mysqli pdo_mysql gd

# docker-compose exec php chown -R www-data:www-data /var/www/html
RUN chown -R www-data:www-data /var/www

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer