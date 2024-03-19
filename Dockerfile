FROM php:8.3-fpm

# Instalar dependências
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install zip pdo pdo_mysql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar a extensão ext-mongodb
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Definir o diretório de trabalho
WORKDIR /app