# Docker 开发环境

这是一个基于 Docker 的 PHP + Vue 开发环境，支持多个 PHP 版本和项目。

## 环境组成

- **Nginx**: 反向代理服务器，端口 8080
- **PHP 7.2**: 运行在 project1 项目
- **PHP 7.4**: 运行在 project2 项目  
- **PHP 8.2**: 运行在 project3 项目
- **MySQL 5.7**: 数据库服务，端口 3307 (使用MariaDB 10.2，兼容MySQL 5.7)
- **MySQL 8.0**: 数据库服务，端口 3308
- **Vue 3**: 前端开发环境，端口 8081 (本地 nvm)

## 本地域名配置

为了方便开发，每个项目都配置了独立的本地域名：

- **PHP 7.2 站点**: http://project1.local:8080
- **PHP 7.4 站点**: http://project2.local:8080
- **PHP 8.2 站点**: http://project3.local:8080

### 配置本地hosts文件

为了让本地域名正常工作，您需要在本地系统的hosts文件中添加以下配置：

#### 方法1：使用脚本（推荐）

```bash
# 自动更新hosts文件
sudo ./scripts/update-hosts.sh
```

#### 方法2：手动配置

**macOS/Linux 系统**

1. 编辑hosts文件：
```bash
sudo nano /etc/hosts
```

2. 添加以下内容：
```
127.0.0.1 project1.local
127.0.0.1 www.project1.local
127.0.0.1 project2.local
127.0.0.1 www.project2.local
127.0.0.1 project3.local
127.0.0.1 www.project3.local
```

3. 清除DNS缓存：
```bash
sudo dscacheutil -flushcache  # macOS
sudo systemctl restart systemd-resolved  # Linux
```

**Windows 系统**

1. 以管理员身份运行记事本
2. 打开文件：`C:\Windows\System32\drivers\etc\hosts`
3. 添加相同的域名配置
4. 清除DNS缓存：`ipconfig /flushdns`

## 快速开始

### 1. 启动 PHP 环境

```bash
docker compose up -d
```

### 2. 配置 Vue 开发环境

```bash
# 一键配置 Vue 开发环境
./setup-vue.sh

# 或者手动配置（详见下方说明）
```

### 3. 访问项目

- **Project1 (PHP 7.2)**: http://project1.local:8080
- **Project2 (PHP 7.4)**: http://project2.local:8080
- **Project3 (PHP 8.2)**: http://project3.local:8080
- **Vue 开发服务器**: http://localhost:8081

### 4. 测试 PHP 8.2 新特性

访问 http://project3.local:8080 查看 PHP 8.2 的新特性演示：
- 只读类 (Readonly Classes)
- 独立类型 (null & false types)
- 性能优化 (OPcache)
- 现代扩展 (Redis, MongoDB, Swoole)

## MySQL 数据库管理

### 连接信息

- **MySQL 5.7 (MariaDB 10.2)**：
  - 主机：127.0.0.1
  - 端口：3307
  - 用户名：root
  - 密码：root
  - 数据库：db57

- **MySQL 8.0**：
  - 主机：127.0.0.1
  - 端口：3308
  - 用户名：root
  - 密码：root
  - 数据库：db80

### 数据存储

数据直接存储在宿主机目录中，确保容器删除后数据不丢失：

```
dev-env/
├── mysql57/
│   ├── data/         # MySQL 5.7数据文件
│   ├── logs/         # MySQL 5.7日志文件
│   └── conf.d/       # MySQL 5.7配置文件
├── mysql8/
│   ├── data/         # MySQL 8.0数据文件
│   ├── logs/         # MySQL 8.0日志文件
│   └── conf.d/       # MySQL 8.0配置文件
```

### 备份和恢复

```bash
# 备份所有MySQL实例
./scripts/backup-mysql.sh

# 只备份特定实例
./scripts/backup-mysql.sh mysql57
./scripts/backup-mysql.sh mysql8

# 恢复数据
./scripts/restore-mysql.sh mysql57 backup_file.tar.gz
./scripts/restore-mysql.sh mysql8 backup_file.tar.gz
```

## Vue 开发环境配置

### 一键配置（推荐）

```bash
./setup-vue.sh
```

### 手动配置

#### 1. 安装 nvm

```bash
# 安装 nvm
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash

# 重新加载配置
source ~/.zshrc

# 安装 Node.js
nvm install 18.19.0
nvm use 18.19.0
nvm alias default 18.19.0

# 安装 Vue CLI
npm install -g @vue/cli
```

#### 2. 创建 Vue 项目

```bash
cd projects
vue create vue-project

# 选择配置：
# - Vue 3
# - Babel
# - Router
# - Vuex
# - CSS Pre-processors (Sass/SCSS)
# - Linter / Formatter (ESLint + Prettier)
```

#### 3. 启动开发服务器

```bash
cd vue-project
npm run serve
```

访问：http://localhost:8081

## 项目结构

```
dev-env/
├── docker-compose.yml      # Docker服务配置
├── README.md               # 项目说明文档
├── nginx/                  # Nginx配置
├── php72/                  # PHP 7.2配置
├── php74/                  # PHP 7.4配置
├── php82/                  # PHP 8.2配置
├── mysql57/                # MySQL 5.7配置和数据
├── mysql8/                 # MySQL 8.0配置和数据
├── projects/               # 项目代码
│   ├── project1/           # PHP 7.2项目
│   ├── project2/           # PHP 7.4项目
│   ├── project3/           # PHP 8.2项目
│   └── vue-project/        # Vue前端项目
├── scripts/                # 管理脚本
│   ├── backup-mysql.sh     # MySQL备份脚本
│   └── restore-mysql.sh    # MySQL恢复脚本
├── setup-vue.sh            # Vue环境配置脚本
└── update-hosts.sh         # hosts文件更新脚本
```

## 常用命令

### Docker 管理

```bash
# 启动服务
docker compose up -d

# 停止服务
docker compose down

# 查看状态
docker compose ps

# 查看日志
docker compose logs [service_name]
```

### Vue 开发

```bash
# 启动开发服务器
npm run serve

# 构建生产版本
npm run build

# 代码检查
npm run lint

# 安装依赖
npm install
```

### 数据库管理

```bash
# 备份数据
./scripts/backup-mysql.sh

# 恢复数据
./scripts/restore-mysql.sh mysql57 backup_file.tar.gz

# 连接数据库
docker compose exec mysql57 mysql -u root -proot
docker compose exec mysql8 mysql -u root -proot
```

## 故障排除

### MySQL 连接问题

如果遇到MySQL连接问题，请检查：

1. 容器是否正常启动：`docker compose ps`
2. 端口是否被占用：`lsof -i :3307` 或 `lsof -i :3308`
3. 查看容器日志：`docker compose logs mysql57` 或 `docker compose logs mysql8`

### 域名访问问题

如果无法通过域名访问：

1. 检查hosts文件配置
2. 清除DNS缓存
3. 检查Nginx配置和日志

### Vue 开发环境问题

如果Vue环境配置失败：

1. 检查Node.js版本：`node --version`
2. 检查npm版本：`npm --version`
3. 重新运行配置脚本：`./setup-vue.sh`

## 技术说明

### MySQL 5.7 兼容性

由于MySQL 5.7在ARM架构Mac上存在兼容性问题，我们使用MariaDB 10.2作为替代方案：

- 完全兼容MySQL 5.7的SQL语法
- 原生支持ARM架构，性能更好
- 无段错误和架构兼容性问题

### 数据持久化

- 数据库文件直接存储在宿主机，确保数据安全
- 支持多种备份方式（SQL导出、数据目录压缩）
- 提供自动化备份和恢复脚本

## 👥 团队开发

### 新成员快速上手

1. **克隆项目**: `git clone <repo-url>`
2. **启动环境**: `docker compose up -d`
3. **配置域名**: `sudo ./scripts/update-hosts.sh`
4. **配置Vue**: `./scripts/setup-vue.sh`

### 团队协作流程

- 使用功能分支开发: `git checkout -b feature/xxx`
- 代码审查后合并到主分支
- 定期同步环境配置和依赖更新

详细指南请参考: [TEAM_DEVELOPMENT_GUIDE.md](./TEAM_DEVELOPMENT_GUIDE.md)

## 🔧 环境配置

### 环境变量

复制环境配置模板并根据团队需求修改：

```bash
cp env.example .env
vim .env
```

### 端口配置

- **Nginx**: 8080 (可修改)
- **Vue**: 8081 (可修改)  
- **MySQL 5.7**: 3307 (可修改)
- **MySQL 8.0**: 3308 (可修改)

## 📚 学习资源

- [PHP 官方文档](https://www.php.net/manual/zh/)
- [Vue 3 官方文档](https://cn.vuejs.org/)
- [Docker 官方文档](https://docs.docker.com/)

## 贡献

欢迎提交Issue和Pull Request来改进这个开发环境！

## 许可证

MIT License
