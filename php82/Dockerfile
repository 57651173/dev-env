FROM php:8.2-fpm

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 配置 GD 扩展
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# 安装 PHP 扩展
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    gd \
    zip \
    intl \
    xml \
    mbstring \
    curl \
    opcache \
    bcmath

# 安装 Redis 扩展
RUN pecl install redis-6.0.2 && docker-php-ext-enable redis

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 设置工作目录
WORKDIR /var/www/html

# 设置权限
RUN chown -R www-data:www-data /var/www/html

# 暴露端口
EXPOSE 9000

CMD ["php-fpm"]
