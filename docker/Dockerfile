FROM php:8.0-fpm

# Installing requirements
RUN apt-get update -y && apt-get install -y \
    openssl \
    zip \
    unzip \
    git \
    supervisor \
    libmcrypt-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    apt-transport-https \
    vim \
    gnupg \
    jpegoptim \
    optipng \
    pngquant \
    ffmpeg \
    gifsicle  \
    zlib1g-dev \
    libzip-dev \
    libonig-dev \
    cron

# Installing composer and docker extensions
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd pdo mbstring pdo_mysql exif pcntl zip bcmath

# Setting up and copy work directory
WORKDIR /var/www
COPY . /var/www
RUN chown -R www-data:www-data /var/www

EXPOSE 9000

COPY ./docker-entrypoint.sh /

ENTRYPOINT ["/docker-entrypoint.sh"]

RUN ["chmod", "+x", "/docker-entrypoint.sh"]
