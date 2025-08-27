FROM php:7.4-fpm

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libgd-dev \
    libonig-dev \
    libsqlite3-dev \
    libbz2-dev \
    libreadline-dev \
    libedit-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 配置GD扩展
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# 安装PHP扩展
RUN docker-php-ext-install -j$(nproc) \
    bcmath \
    bz2 \
    ctype \
    curl \
    dom \
    exif \
    fileinfo \
    filter \
    gd \
    hash \
    iconv \
    intl \
    json \
    libxml \
    mbstring \
    mysqli \
    mysqlnd \
    openssl \
    pcntl \
    pcre \
    pdo \
    pdo_mysql \
    phar \
    posix \
    readline \
    reflection \
    session \
    simplexml \
    soap \
    sockets \
    spl \
    sqlite3 \
    tokenizer \
    xml \
    xmlreader \
    xmlrpc \
    xmlwriter \
    zip

# 安装Redis扩展
RUN pecl install redis-5.3.7 && docker-php-ext-enable redis

# 安装MongoDB扩展
RUN pecl install mongodb-1.12.0 && docker-php-ext-enable mongodb

# 安装APCu扩展
RUN pecl install apcu-5.1.21 && docker-php-ext-enable apcu

# 复制PHP配置文件
COPY php.ini /usr/local/etc/php/php.ini

# 配置PHP
RUN echo "memory_limit = 256M" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "upload_max_filesize = 100M" > /usr/local/etc/php/conf.d/upload.ini \
    && echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/upload.ini \
    && echo "max_execution_time = 300" > /usr/local/etc/php/conf.d/timeout.ini \
    && echo "date.timezone = Asia/Shanghai" > /usr/local/etc/php/conf.d/timezone.ini

# 安装Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 设置工作目录
WORKDIR /var/www/html

# 暴露9000端口
EXPOSE 9000

# 启动PHP-FPM
CMD ["php-fpm"]
