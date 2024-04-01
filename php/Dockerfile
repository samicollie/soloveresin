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

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Copy the entire project into the container
COPY . /var/www

# Change working directory
WORKDIR /var/www

# Install PHPMailer
RUN composer require phpmailer/phpmailer

# Install project dependencies
RUN composer install


# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Expose port
EXPOSE 9000

# Command to run
CMD ["php-fpm"]