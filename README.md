# PHP + Vue 开发环境

这是一个基于Docker的PHP + Vue开发环境，支持多个PHP版本和常用扩展。

## 环境组成

### 服务列表
- **Nginx**: 反向代理服务器，端口80
- **PHP 7.2**: 运行在9000端口，支持GD、Redis等扩展
- **PHP 7.4**: 运行在9000端口，支持GD、Redis、MongoDB等扩展
- **MySQL 5.7**: 数据库服务，端口3307
- **MySQL 8.0**: 数据库服务，端口3308

### PHP扩展配置

#### PHP 7.2 扩展
- **核心扩展**: bcmath, bz2, calendar, ctype, curl, date, dom, exif, fileinfo, filter, ftp, gd, gettext, hash, iconv, intl, json, ldap, libxml, mbstring, mysqli, mysqlnd, openssl, pcntl, pcre, pdo, pdo_mysql, phar, posix, readline, reflection, session, simplexml, soap, sockets, spl, sqlite3, xml, zip等
- **PECL扩展**: redis, memcached, imagick, apcu, xdebug
- **特殊配置**: 支持文件上传(100M), 内存限制(256M), 时区(Asia/Shanghai)

#### PHP 7.4 扩展
- **核心扩展**: 包含PHP 7.2的所有核心扩展
- **PECL扩展**: redis, mongodb, memcached, imagick, apcu, xdebug
- **特殊配置**: 与PHP 7.2相同的配置

## 快速开始

### 1. 启动环境
```bash
# 构建并启动所有服务
docker compose up -d

# 查看服务状态
docker compose ps

# 查看服务日志
docker compose logs -f
```

### 2. 访问测试页面
- **PHP 7.2**: http://localhost/project1/test_extensions.php
- **PHP 7.4**: http://localhost/project2/test_extensions.php

### 3. 停止环境
```bash
docker compose down
```

## 扩展管理

### 添加新扩展

#### 方法1: 修改Dockerfile
1. 编辑对应版本的Dockerfile (如 `php72/Dockerfile`)
2. 在安装扩展部分添加新的扩展
3. 重新构建镜像:
```bash
docker compose build php72
docker compose up -d php72
```

#### 方法2: 使用docker-php-ext-install
```dockerfile
# 安装系统依赖
RUN apt-get update && apt-get install -y \
    libyour-extension-dev

# 安装PHP扩展
RUN docker-php-ext-install your_extension
```

#### 方法3: 使用PECL安装
```dockerfile
# 安装PECL扩展
RUN pecl install extension_name && docker-php-ext-enable extension_name
```

### 常用扩展安装示例

#### 安装GD扩展
```dockerfile
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
```

#### 安装Redis扩展
```dockerfile
RUN pecl install redis && docker-php-ext-enable redis
```

#### 安装MongoDB扩展
```dockerfile
RUN pecl install mongodb && docker-php-ext-enable mongodb
```

#### 安装PDO MySQL扩展
```dockerfile
RUN docker-php-ext-install pdo_mysql
```

## 配置文件

### PHP配置
- **PHP 7.2**: `php72/php.ini`
- **PHP 7.4**: `php74/php.ini`

### Nginx配置
- **主配置**: `nginx/conf.d/default.conf`
- **自动重载**: `nginx/auto-reload.sh`

## 开发建议

### 1. 扩展选择
- 只安装项目必需的扩展，避免不必要的性能开销
- 生产环境建议禁用xdebug等开发扩展
- 定期更新扩展版本以修复安全漏洞

### 2. 性能优化
- 启用OPcache提高PHP执行性能
- 配置适当的memory_limit和max_execution_time
- 使用Redis等缓存扩展提升应用性能

### 3. 安全配置
- 设置合适的文件上传限制
- 配置安全的session参数
- 禁用危险的PHP函数

## 故障排除

### 常见问题

#### 扩展未加载
1. 检查Dockerfile中是否正确安装了扩展
2. 确认php.ini中扩展配置正确
3. 重启PHP容器: `docker compose restart php72`

#### 权限问题
```bash
# 修复项目目录权限
sudo chown -R $USER:$USER projects/
```

#### 端口冲突
- 检查端口是否被占用: `lsof -i :80`
- 修改docker-compose.yml中的端口映射

### 日志查看
```bash
# 查看PHP错误日志
docker compose logs php72
docker compose logs php74

# 查看Nginx访问日志
docker compose logs nginx
```

## 更新和维护

### 更新PHP版本
1. 修改Dockerfile中的基础镜像版本
2. 更新扩展版本兼容性
3. 重新构建并测试

### 备份配置
```bash
# 备份当前配置
cp -r php72/ php72_backup/
cp -r php74/ php74_backup/
cp docker-compose.yml docker-compose.yml.backup
```

## 联系和支持

如有问题或建议，请查看项目文档或联系开发团队。
