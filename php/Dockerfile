# Image and version
FROM php:8.2-fpm

# Install dependencies
RUN apt update && \
    apt install --no-install-recommends -y \
    zlib1g-dev \
    libpng-dev \
    libzip-dev \
    unzip

# Call PHP images script `docker-php-ext-install` and install language extensions
RUN docker-php-ext-install  \
    zip \
    pdo \
    pdo_mysql

# copy the Composer PHAR from the Composer image into the PHP image
COPY --from=composer /usr/bin/composer /usr/bin/composer
