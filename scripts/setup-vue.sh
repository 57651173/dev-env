#!/bin/bash

# Vue 开发环境一键配置脚本
# 适用于 macOS/Linux 系统

echo "🚀 开始配置 Vue 开发环境..."

# 检查是否已安装 nvm
if [ ! -d "$HOME/.nvm" ]; then
    echo "📥 正在安装 nvm..."
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
    
    # 加载 nvm
    export NVM_DIR="$HOME/.nvm"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"
    
    echo "✅ nvm 安装完成"
else
    echo "✅ nvm 已安装"
    export NVM_DIR="$HOME/.nvm"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"
fi

# 安装 Node.js LTS 版本
echo "📥 正在安装 Node.js 18.19.0 (LTS)..."
nvm install 18.19.0
nvm use 18.19.0
nvm alias default 18.19.0

# 验证安装
echo "🔍 验证安装..."
node --version
npm --version

# 安装 Vue CLI
echo "📥 正在安装 Vue CLI..."
npm install -g @vue/cli

# 验证 Vue CLI 安装
echo "🔍 验证 Vue CLI 安装..."
vue --version

# 创建 Vue 项目目录
echo "📁 创建 Vue 项目目录..."
cd /Users/marcus/dev-env/projects

if [ ! -d "vue-project" ]; then
    echo "🎯 正在创建 Vue 项目..."
    echo "请按照提示选择项目配置："
    echo "- 选择 Vue 3"
    echo "- 选择 Babel, Router, Vuex"
    echo "- 选择 CSS Pre-processors (Sass/SCSS)"
    echo "- 选择 Linter / Formatter (ESLint + Prettier)"
    
    vue create vue-project --default
else
    echo "✅ Vue 项目目录已存在"
fi

# 进入项目目录
cd vue-project

# 安装额外依赖
echo "📦 安装额外依赖..."
npm install axios

# 创建 vue.config.js
echo "⚙️ 创建 vue.config.js 配置文件..."
cat > vue.config.js << 'EOF'
module.exports = {
  devServer: {
    port: 8081,
    host: '0.0.0.0',
    open: true,
    proxy: {
      '/api': {
        target: 'http://localhost:8080',
        changeOrigin: true,
        pathRewrite: {
          '^/api': ''
        }
      }
    }
  },
  outputDir: '../project1/dist',
  publicPath: process.env.NODE_ENV === 'production' ? '/project1/' : '/'
}
EOF

# 创建 API 配置文件
echo "🔧 创建 API 配置文件..."
mkdir -p src/api

cat > src/api/config.js << 'EOF'
import axios from 'axios'

const api = axios.create({
  baseURL: process.env.NODE_ENV === 'development' 
    ? 'http://localhost:8080/api' 
    : '/api',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json'
  }
})

api.interceptors.request.use(
  config => {
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

api.interceptors.response.use(
  response => {
    return response.data
  },
  error => {
    console.error('API Error:', error)
    return Promise.reject(error)
  }
)

export default api
EOF

# 创建示例 API 文件
cat > src/api/user.js << 'EOF'
import api from './config'

export const userApi = {
  getUsers() {
    return api.get('/users')
  },
  
  createUser(userData) {
    return api.post('/users', userData)
  },
  
  updateUser(id, userData) {
    return api.put(`/users/${id}`, userData)
  },
  
  deleteUser(id) {
    return api.delete(`/users/${id}`)
  }
}
EOF

# 更新 package.json 脚本
echo "📝 更新 package.json 脚本..."
npm pkg set scripts.build:dev="vue-cli-service build --mode development"

echo ""
echo "🎉 Vue 开发环境配置完成！"
echo ""
echo "📋 下一步操作："
echo "1. 进入项目目录: cd /Users/marcus/dev-env/projects/vue-project"
echo "2. 启动开发服务器: npm run serve"
echo "3. 访问: http://localhost:8081"
echo ""
echo "🔗 相关文档："
echo "- Vue 官方文档: https://vuejs.org/"
echo "- Vue CLI 文档: https://cli.vuejs.org/"
echo "- 项目配置指南: ./vue-setup.md"
echo ""
echo "💡 提示："
echo "- 开发时使用 npm run serve 启动热重载服务器"
echo "- 构建时使用 npm run build 生成生产文件"
echo "- 构建后的文件会自动输出到 PHP 项目目录"
