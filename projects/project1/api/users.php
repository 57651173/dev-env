<?php
/**
 * 用户管理 API
 * 提供用户 CRUD 操作的 RESTful 接口
 */

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 数据库配置
$db_config = [
    'host' => 'mysql57',
    'port' => '3306',
    'dbname' => 'db57',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8mb4'
];

/**
 * 数据库连接类
 */
class Database {
    private $pdo;
    
    public function __construct($config) {
        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            throw new Exception('数据库连接失败: ' . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}

/**
 * 用户模型类
 */
class User {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        $this->initTable();
    }
    
    /**
     * 初始化用户表
     */
    private function initTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            phone VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        try {
            $this->db->exec($sql);
            
            // 插入示例数据
            $this->insertSampleData();
        } catch (PDOException $e) {
            // 表已存在，忽略错误
        }
    }
    
    /**
     * 插入示例数据
     */
    private function insertSampleData() {
        $sql = "SELECT COUNT(*) as count FROM users";
        $stmt = $this->db->query($sql);
        $count = $stmt->fetch()['count'];
        
        if ($count == 0) {
            $sampleUsers = [
                ['张三', 'zhangsan@example.com', '13800138001'],
                ['李四', 'lisi@example.com', '13800138002'],
                ['王五', 'wangwu@example.com', '13800138003']
            ];
            
            $sql = "INSERT INTO users (name, email, phone) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            
            foreach ($sampleUsers as $user) {
                $stmt->execute($user);
            }
        }
    }
    
    /**
     * 获取所有用户
     */
    public function getAll() {
        try {
            $sql = "SELECT * FROM users ORDER BY created_at DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception('获取用户列表失败: ' . $e->getMessage());
        }
    }
    
    /**
     * 根据ID获取用户
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception('获取用户失败: ' . $e->getMessage());
        }
    }
    
    /**
     * 创建用户
     */
    public function create($data) {
        try {
            // 验证邮箱唯一性
            $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['email']]);
            if ($stmt->fetch()['count'] > 0) {
                throw new Exception('邮箱已存在');
            }
            
            $sql = "INSERT INTO users (name, email, phone) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['name'], $data['email'], $data['phone'] ?? '']);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('创建用户失败: ' . $e->getMessage());
        }
    }
    
    /**
     * 更新用户
     */
    public function update($id, $data) {
        try {
            // 验证邮箱唯一性（排除当前用户）
            $sql = "SELECT COUNT(*) as count FROM users WHERE email = ? AND id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['email'], $id]);
            if ($stmt->fetch()['count'] > 0) {
                throw new Exception('邮箱已存在');
            }
            
            $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['name'], $data['email'], $data['phone'] ?? '', $id]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception('更新用户失败: ' . $e->getMessage());
        }
    }
    
    /**
     * 删除用户
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception('删除用户失败: ' . $e->getMessage());
        }
    }
}

/**
 * API 响应类
 */
class ApiResponse {
    public static function success($data = null, $message = '操作成功') {
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ], JSON_UNESCAPED_UNICODE);
    }
    
    public static function error($message = '操作失败', $code = 400) {
        http_response_code($code);
        echo json_encode([
            'success' => false,
            'message' => $message,
            'code' => $code,
            'timestamp' => date('Y-m-d H:i:s')
        ], JSON_UNESCAPED_UNICODE);
    }
}

/**
 * 输入验证类
 */
class Validator {
    public static function validateUser($data) {
        $errors = [];
        
        if (empty($data['name']) || strlen($data['name']) > 100) {
            $errors[] = '姓名不能为空且长度不能超过100个字符';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '邮箱格式不正确';
        }
        
        if (!empty($data['phone']) && !preg_match('/^1[3-9]\d{9}$/', $data['phone'])) {
            $errors[] = '手机号格式不正确';
        }
        
        return $errors;
    }
}

// 主程序
try {
    $db = new Database($db_config);
    $userModel = new User($db->getConnection());
    
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $pathParts = explode('/', trim($path, '/'));
    
    // 获取用户ID（如果存在）
    $userId = null;
    if (count($pathParts) >= 3 && is_numeric($pathParts[2])) {
        $userId = (int)$pathParts[2];
    }
    
    switch ($method) {
        case 'GET':
            if ($userId) {
                // 获取单个用户
                $user = $userModel->getById($userId);
                if ($user) {
                    ApiResponse::success($user, '获取用户成功');
                } else {
                    ApiResponse::error('用户不存在', 404);
                }
            } else {
                // 获取所有用户
                $users = $userModel->getAll();
                ApiResponse::success($users, '获取用户列表成功');
            }
            break;
            
        case 'POST':
            // 创建用户
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                ApiResponse::error('请求数据格式错误', 400);
                break;
            }
            
            $errors = Validator::validateUser($input);
            if (!empty($errors)) {
                ApiResponse::error(implode(', ', $errors), 400);
                break;
            }
            
            $newUserId = $userModel->create($input);
            $newUser = $userModel->getById($newUserId);
            ApiResponse::success($newUser, '用户创建成功');
            break;
            
        case 'PUT':
            // 更新用户
            if (!$userId) {
                ApiResponse::error('缺少用户ID', 400);
                break;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                ApiResponse::error('请求数据格式错误', 400);
                break;
            }
            
            $errors = Validator::validateUser($input);
            if (!empty($errors)) {
                ApiResponse::error(implode(', ', $errors), 400);
                break;
            }
            
            if ($userModel->update($userId, $input)) {
                $updatedUser = $userModel->getById($userId);
                ApiResponse::success($updatedUser, '用户更新成功');
            } else {
                ApiResponse::error('用户不存在或更新失败', 404);
            }
            break;
            
        case 'DELETE':
            // 删除用户
            if (!$userId) {
                ApiResponse::error('缺少用户ID', 400);
                break;
            }
            
            if ($userModel->delete($userId)) {
                ApiResponse::success(null, '用户删除成功');
            } else {
                ApiResponse::error('用户不存在或删除失败', 404);
            }
            break;
            
        default:
            ApiResponse::error('不支持的请求方法', 405);
            break;
    }
    
} catch (Exception $e) {
    ApiResponse::error($e->getMessage(), 500);
} catch (PDOException $e) {
    ApiResponse::error('数据库操作失败: ' . $e->getMessage(), 500);
}
?>
