FROM php:8.0-fpm

# Installing requirements
RUN apt-get update -y && apt-get install -y \
    openssl \
    zip \
    unzip \
    git \
    libmcrypt-dev \
    apt-transport-https \
    vim

# Installing composer and docker extensions
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql bcmath

# Setting up and copy work directory
WORKDIR /var/www
COPY . /var/www
RUN chown -R www-data:www-data /var/www

EXPOSE 9000

COPY ./docker-entrypoint.sh /

ENTRYPOINT ["/docker-entrypoint.sh"]

RUN ["chmod", "+x", "/docker-entrypoint.sh"]
