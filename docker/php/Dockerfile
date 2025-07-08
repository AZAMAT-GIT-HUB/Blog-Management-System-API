FROM php:8.2-fpm

# Установка системных зависимостей, включая SQLite
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    sqlite3 \
    libsqlite3-dev \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка PHP расширений
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip


# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Создание пользователя
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Копирование конфигурации PHP
COPY local.ini /usr/local/etc/php/conf.d/local.ini

# Смена владельца директории
COPY --chown=www:www . /var/www/html

# Переключение на пользователя www
USER www

# Открытие порта 9000
EXPOSE 9000

# Запуск PHP-FPM
CMD ["php-fpm"]
