<template>
  <div class="user-list">
    <h2>用户管理</h2>
    
    <!-- 添加用户表单 -->
    <div class="add-user-form">
      <h3>添加新用户</h3>
      <form @submit.prevent="addUser">
        <div class="form-group">
          <label for="name">姓名:</label>
          <input 
            type="text" 
            id="name" 
            v-model="newUser.name" 
            required
            placeholder="请输入姓名"
          >
        </div>
        
        <div class="form-group">
          <label for="email">邮箱:</label>
          <input 
            type="email" 
            id="email" 
            v-model="newUser.email" 
            required
            placeholder="请输入邮箱"
          >
        </div>
        
        <div class="form-group">
          <label for="phone">电话:</label>
          <input 
            type="tel" 
            id="phone" 
            v-model="newUser.phone" 
            placeholder="请输入电话"
          >
        </div>
        
        <button type="submit" :disabled="loading">
          {{ loading ? '添加中...' : '添加用户' }}
        </button>
      </form>
    </div>
    
    <!-- 用户列表 -->
    <div class="users-table">
      <h3>用户列表</h3>
      <div v-if="loading" class="loading">加载中...</div>
      <div v-else-if="error" class="error">{{ error }}</div>
      <div v-else-if="users.length === 0" class="no-users">暂无用户</div>
      <table v-else>
        <thead>
          <tr>
            <th>ID</th>
            <th>姓名</th>
            <th>邮箱</th>
            <th>电话</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.phone || '-' }}</td>
            <td>
              <button @click="editUser(user)" class="btn-edit">编辑</button>
              <button @click="deleteUser(user.id)" class="btn-delete">删除</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <!-- 编辑用户模态框 -->
    <div v-if="showEditModal" class="modal">
      <div class="modal-content">
        <h3>编辑用户</h3>
        <form @submit.prevent="updateUser">
          <div class="form-group">
            <label for="edit-name">姓名:</label>
            <input 
              type="text" 
              id="edit-name" 
              v-model="editingUser.name" 
              required
            >
          </div>
          
          <div class="form-group">
            <label for="edit-email">邮箱:</label>
            <input 
              type="email" 
              id="edit-email" 
              v-model="editingUser.email" 
              required
            >
          </div>
          
          <div class="form-group">
            <label for="edit-phone">电话:</label>
            <input 
              type="tel" 
              id="edit-phone" 
              v-model="editingUser.phone"
            >
          </div>
          
          <div class="modal-actions">
            <button type="submit" :disabled="loading">
              {{ loading ? '更新中...' : '更新' }}
            </button>
            <button type="button" @click="closeEditModal">取消</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { userApi } from '@/api/user'

export default {
  name: 'UserList',
  data() {
    return {
      users: [],
      newUser: {
        name: '',
        email: '',
        phone: ''
      },
      editingUser: {},
      showEditModal: false,
      loading: false,
      error: null
    }
  },
  
  async mounted() {
    await this.loadUsers()
  },
  
  methods: {
    async loadUsers() {
      try {
        this.loading = true
        this.error = null
        const response = await userApi.getUsers()
        this.users = response.data || []
      } catch (error) {
        console.error('加载用户失败:', error)
        this.error = '加载用户失败，请检查网络连接'
        // 模拟数据用于演示
        this.users = [
          { id: 1, name: '张三', email: 'zhangsan@example.com', phone: '13800138001' },
          { id: 2, name: '李四', email: 'lisi@example.com', phone: '13800138002' },
          { id: 3, name: '王五', email: 'wangwu@example.com', phone: '13800138003' }
        ]
      } finally {
        this.loading = false
      }
    },
    
    async addUser() {
      try {
        this.loading = true
        await userApi.createUser(this.newUser)
        
        // 清空表单
        this.newUser = { name: '', email: '', phone: '' }
        
        // 重新加载用户列表
        await this.loadUsers()
        
        this.$emit('user-added', '用户添加成功！')
      } catch (error) {
        console.error('添加用户失败:', error)
        this.$emit('user-error', '添加用户失败，请重试')
      } finally {
        this.loading = false
      }
    },
    
    editUser(user) {
      this.editingUser = { ...user }
      this.showEditModal = true
    },
    
    closeEditModal() {
      this.showEditModal = false
      this.editingUser = {}
    },
    
    async updateUser() {
      try {
        this.loading = true
        await userApi.updateUser(this.editingUser.id, this.editingUser)
        
        // 关闭模态框
        this.closeEditModal()
        
        // 重新加载用户列表
        await this.loadUsers()
        
        this.$emit('user-updated', '用户更新成功！')
      } catch (error) {
        console.error('更新用户失败:', error)
        this.$emit('user-error', '更新用户失败，请重试')
      } finally {
        this.loading = false
      }
    },
    
    async deleteUser(userId) {
      if (!confirm('确定要删除这个用户吗？')) {
        return
      }
      
      try {
        this.loading = true
        await userApi.deleteUser(userId)
        
        // 重新加载用户列表
        await this.loadUsers()
        
        this.$emit('user-deleted', '用户删除成功！')
      } catch (error) {
        console.error('删除用户失败:', error)
        this.$emit('user-error', '删除用户失败，请重试')
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.user-list {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

h2 {
  color: #2c3e50;
  text-align: center;
  margin-bottom: 30px;
}

.add-user-form {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 30px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 500;
  color: #495057;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 14px;
}

.form-group input:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

button {
  background: #007bff;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

button:hover:not(:disabled) {
  background: #0056b3;
}

button:disabled {
  background: #6c757d;
  cursor: not-allowed;
}

.users-table {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.users-table h3 {
  margin: 0;
  padding: 20px;
  background: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #dee2e6;
}

th {
  background: #f8f9fa;
  font-weight: 600;
  color: #495057;
}

.btn-edit, .btn-delete {
  margin-right: 5px;
  padding: 5px 10px;
  font-size: 12px;
}

.btn-edit {
  background: #28a745;
}

.btn-edit:hover {
  background: #218838;
}

.btn-delete {
  background: #dc3545;
}

.btn-delete:hover {
  background: #c82333;
}

.loading, .error, .no-users {
  padding: 40px;
  text-align: center;
  color: #6c757d;
}

.error {
  color: #dc3545;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 8px;
  min-width: 400px;
  max-width: 500px;
}

.modal-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  margin-top: 20px;
}

.modal-actions button:last-child {
  background: #6c757d;
}

.modal-actions button:last-child:hover {
  background: #5a6268;
}
</style>
