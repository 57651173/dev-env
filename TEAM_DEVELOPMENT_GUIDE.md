# 🚀 团队开发环境使用指南

## 📋 目录

1. [环境概述](#环境概述)
2. [快速开始](#快速开始)
3. [开发流程](#开发流程)
4. [最佳实践](#最佳实践)
5. [常见问题](#常见问题)
6. [团队协作](#团队协作)

## 🌟 环境概述

这是一个专为团队开发设计的统一 PHP + Vue 开发环境，具有以下特点：

### ✨ 核心特性

- **多版本 PHP 支持**: PHP 7.2、7.4、8.2 并行运行
- **双数据库版本**: MySQL 5.7 和 8.0 同时支持
- **现代化前端**: Vue 3 + 最新工具链
- **容器化部署**: Docker 统一管理，环境一致
- **自动化脚本**: 一键配置、备份、恢复

### 🏗️ 技术架构

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Nginx 网关    │    │   Vue 前端      │    │   PHP 后端      │
│   端口: 8080    │    │   端口: 8081    │    │   多版本支持    │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         └───────────────────────┼───────────────────────┘
                                 │
                    ┌─────────────────┐
                    │   MySQL 数据库  │
                    │   5.7 + 8.0     │
                    └─────────────────┘
```

## 🚀 快速开始

### 1. 环境准备

确保系统已安装：
- Docker Desktop
- Git
- 文本编辑器 (VS Code 推荐)

### 2. 克隆项目

```bash
git clone <your-repo-url>
cd dev-env
```

### 3. 启动环境

```bash
# 启动所有服务
docker compose up -d

# 配置本地域名
sudo ./scripts/update-hosts.sh

# 配置 Vue 环境
./scripts/setup-vue.sh
```

### 4. 验证环境

访问以下地址验证环境：

- **Project1 (PHP 7.2)**: http://project1.local:8080
- **Project2 (PHP 7.4)**: http://project2.local:8080  
- **Project3 (PHP 8.2)**: http://project3.local:8080
- **Vue 开发服务器**: http://localhost:8081

## 🔄 开发流程

### 日常开发流程

#### 1. 启动开发环境

```bash
# 每天开始开发时
cd dev-env
docker compose up -d
```

#### 2. 开发新功能

```bash
# 创建功能分支
git checkout -b feature/new-feature

# 开发完成后提交
git add .
git commit -m "feat: 添加新功能"
git push origin feature/new-feature
```

#### 3. 代码审查

- 创建 Pull Request
- 团队成员代码审查
- 通过后合并到主分支

#### 4. 结束开发

```bash
# 停止环境（可选）
docker compose down
```

### 项目切换流程

#### 切换到不同 PHP 版本项目

```bash
# 编辑项目文件
code projects/project1/    # PHP 7.2
code projects/project2/    # PHP 7.4  
code projects/project3/    # PHP 8.2
```

#### 前端开发

```bash
cd projects/vue-project
npm run serve
```

## 💡 最佳实践

### 1. 代码组织

#### PHP 项目结构

```
project1/
├── api/              # API 接口
├── config/           # 配置文件
├── src/              # 源代码
│   ├── Controllers/  # 控制器
│   ├── Models/       # 模型
│   └── Services/     # 服务层
├── public/           # 公共文件
├── tests/            # 测试文件
└── composer.json     # 依赖管理
```

#### Vue 项目结构

```
vue-project/
├── src/
│   ├── components/   # 组件
│   ├── views/        # 页面
│   ├── router/       # 路由
│   ├── store/        # 状态管理
│   ├── api/          # API 接口
│   └── utils/        # 工具函数
├── public/           # 静态资源
└── package.json      # 依赖管理
```

### 2. 数据库管理

#### 开发环境数据库

- **开发数据**: 使用测试数据，避免生产数据
- **数据备份**: 定期备份重要数据
- **版本控制**: 数据库结构变更使用迁移文件

#### 备份策略

```bash
# 每日备份
./scripts/backup-mysql.sh

# 恢复数据
./scripts/restore-mysql.sh mysql57 backup_file.tar.gz
```

### 3. 环境配置

#### 环境变量管理

```bash
# 复制环境配置模板
cp env.example .env

# 根据团队需求修改配置
vim .env
```

#### 端口配置

- **Nginx**: 8080 (可修改)
- **Vue**: 8081 (可修改)
- **MySQL 5.7**: 3307 (可修改)
- **MySQL 8.0**: 3308 (可修改)

### 4. 代码质量

#### PHP 代码规范

- 遵循 PSR-12 编码规范
- 使用 PHPStan 进行静态分析
- 编写单元测试
- 使用 Composer 管理依赖

#### Vue 代码规范

- 遵循 Vue 官方风格指南
- 使用 ESLint + Prettier
- 组件命名使用 PascalCase
- 文件命名使用 kebab-case

## 🐛 常见问题

### 1. 容器启动失败

```bash
# 检查容器状态
docker compose ps

# 查看容器日志
docker compose logs [service_name]

# 重新构建容器
docker compose build --no-cache
docker compose up -d
```

### 2. 域名无法访问

```bash
# 检查 hosts 文件
cat /etc/hosts | grep project

# 重新配置 hosts
sudo ./scripts/update-hosts.sh

# 清除 DNS 缓存
sudo dscacheutil -flushcache  # macOS
sudo systemctl restart systemd-resolved  # Linux
```

### 3. 数据库连接失败

```bash
# 检查数据库容器状态
docker compose ps mysql57 mysql8

# 测试数据库连接
docker compose exec mysql57 mysql -u root -proot
docker compose exec mysql8 mysql -u root -proot

# 检查端口占用
lsof -i :3307
lsof -i :3308
```

### 4. Vue 环境问题

```bash
# 检查 Node.js 版本
node --version

# 重新安装依赖
cd projects/vue-project
rm -rf node_modules package-lock.json
npm install

# 重新配置环境
./scripts/setup-vue.sh
```

## 👥 团队协作

### 1. 版本控制

#### Git 工作流

```bash
# 主分支
main/master          # 生产环境代码
develop              # 开发环境代码

# 功能分支
feature/xxx          # 新功能开发
bugfix/xxx           # 问题修复
hotfix/xxx           # 紧急修复
```

#### 提交规范

```
feat: 新功能
fix: 修复问题
docs: 文档更新
style: 代码格式调整
refactor: 代码重构
test: 测试相关
chore: 构建过程或辅助工具的变动
```

### 2. 代码审查

#### 审查要点

- 代码逻辑正确性
- 性能优化
- 安全性检查
- 代码规范遵循
- 测试覆盖

#### 审查流程

1. 开发者提交 PR
2. 团队成员审查代码
3. 发现问题，开发者修改
4. 审查通过，合并代码

### 3. 环境同步

#### 新成员加入

```bash
# 1. 克隆项目
git clone <repo-url>
cd dev-env

# 2. 启动环境
docker compose up -d

# 3. 配置域名
sudo ./scripts/update-hosts.sh

# 4. 配置 Vue 环境
./scripts/setup-vue.sh
```

#### 环境更新

```bash
# 1. 拉取最新代码
git pull origin main

# 2. 更新环境配置
docker compose down
docker compose up -d --build

# 3. 更新依赖
cd projects/vue-project
npm install
```

### 4. 沟通协作

#### 日常沟通

- **晨会**: 同步开发进度
- **代码审查**: 及时反馈
- **问题讨论**: 技术难点交流
- **知识分享**: 定期技术分享

#### 工具推荐

- **即时通讯**: Slack/Discord
- **项目管理**: Jira/Trello
- **代码托管**: GitHub/GitLab
- **文档协作**: Notion/Confluence

## 📚 学习资源

### PHP 学习

- [PHP 官方文档](https://www.php.net/manual/zh/)
- [Laravel 中文文档](https://learnku.com/docs/laravel)
- [Composer 中文文档](https://docs.phpcomposer.com/)

### Vue 学习

- [Vue 3 官方文档](https://cn.vuejs.org/)
- [Vue Router 文档](https://router.vuejs.org/zh/)
- [Pinia 状态管理](https://pinia.vuejs.org/zh/)

### Docker 学习

- [Docker 官方文档](https://docs.docker.com/)
- [Docker Compose 文档](https://docs.docker.com/compose/)

## 🎯 总结

这个团队开发环境为 PHP + Vue 项目提供了：

1. **环境一致性**: Docker 容器化，避免"在我机器上能运行"问题
2. **多版本支持**: 支持不同 PHP 版本，便于项目迁移和升级
3. **自动化工具**: 脚本化配置，减少重复工作
4. **团队协作**: 统一的开发流程和规范

通过遵循本指南，团队成员可以：

- 快速搭建开发环境
- 高效协作开发
- 保持代码质量
- 提升开发效率

记住：**好的开发环境是团队生产力的基础**！ 🚀
