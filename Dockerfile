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

RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev

# Configure o PHP para usar o Redis
RUN pecl install redis \
    && docker-php-ext-enable redis


# Definir o diretório de trabalho
WORKDIR /app

# Copie o arquivo de dependências do Composer e instale as dependências
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copie o resto do código-fonte da aplicação
COPY . .

# Execute o Composer para gerar o autoload
RUN composer dump-autoload --optimize

# Garanta que o diretório storage e seus subdiretórios tenham permissões adequadas
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
