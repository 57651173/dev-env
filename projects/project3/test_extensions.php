<?php
/**
 * PHP 8.2 扩展测试文件
 * 测试已安装的扩展和功能
 */

// 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 获取所有已加载的扩展
$loaded_extensions = get_loaded_extensions();
sort($loaded_extensions);

// 定义重要扩展分类
$extension_categories = [
    '核心扩展' => [
        'bcmath', 'ctype', 'date', 'filter', 'hash', 'json', 'libxml', 'openssl',
        'pcre', 'phar', 'posix', 'readline', 'reflection', 'session', 'spl',
        'standard', 'tokenizer', 'xml', 'zlib'
    ],
    '数据库扩展' => [
        'mysqli', 'mysqlnd', 'pdo', 'pdo_mysql', 'sqlite3'
    ],
    '图像处理' => [
        'gd', 'exif'
    ],
    '网络扩展' => [
        'curl', 'soap', 'sockets', 'xmlrpc'
    ],
    '缓存扩展' => [
        'apcu', 'opcache', 'redis'
    ],
    'NoSQL扩展' => [
        'mongodb'
    ],
    '高性能扩展' => [
        'swoole'
    ],
    '其他扩展' => [
        'bz2', 'iconv', 'intl', 'mbstring', 'simplexml', 'xmlreader', 'xmlwriter',
        'tidy', 'xsl', 'sodium'
    ]
];

// 检查扩展状态
function checkExtensionStatus($extension_name) {
    if (extension_loaded($extension_name)) {
        return [
            'status' => '已安装',
            'class' => 'enabled',
            'version' => phpversion($extension_name) ?: '未知'
        ];
    } else {
        return [
            'status' => '未安装',
            'class' => 'disabled',
            'version' => 'N/A'
        ];
    }
}

// 测试特定功能
function testFunctionality() {
    $tests = [];
    
    // 测试 OPcache
    if (function_exists('opcache_get_status')) {
        $opcache_status = opcache_get_status();
        $tests['OPcache'] = [
            'status' => '可用',
            'memory_usage' => isset($opcache_status['memory_usage']) ? 
                round($opcache_status['memory_usage']['used_memory'] / 1024 / 1024, 2) . ' MB' : 'N/A',
            'hit_rate' => isset($opcache_status['opcache_statistics']) ? 
                round($opcache_status['opcache_statistics']['opcache_hit_rate'], 2) . '%' : 'N/A'
        ];
    } else {
        $tests['OPcache'] = ['status' => '不可用'];
    }
    
    // 测试 Redis
    if (extension_loaded('redis')) {
        $tests['Redis'] = ['status' => '已安装'];
    } else {
        $tests['Redis'] = ['status' => '未安装'];
    }
    
    // 测试 MongoDB
    if (extension_loaded('mongodb')) {
        $tests['MongoDB'] = ['status' => '已安装'];
    } else {
        $tests['MongoDB'] = ['status' => '未安装'];
    }
    
    // 测试 Swoole
    if (extension_loaded('swoole')) {
        $tests['Swoole'] = ['status' => '已安装'];
    } else {
        $tests['Swoole'] = ['status' => '未安装'];
    }
    
    return $tests;
}

$functionality_tests = testFunctionality();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP 8.2 扩展测试</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 1.2em;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e1e5e9;
            border-radius: 10px;
            background: #f8f9fa;
        }
        .section h2 {
            color: #495057;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-top: 0;
        }
        .extension-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .extension-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .extension-card h3 {
            margin-top: 0;
            color: #495057;
            font-size: 1.1em;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .status.enabled {
            background: #d4edda;
            color: #155724;
        }
        .status.disabled {
            background: #f8d7da;
            color: #721c24;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .info-table th,
        .info-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .info-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .stat-label {
            color: #6c757d;
            font-size: 1.1em;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
            margin: 5px;
        }
        .btn:hover {
            background: #5a6fd8;
        }
        .highlight {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔌 PHP 8.2 扩展测试</h1>
            <p>查看已安装的扩展和功能状态</p>
        </div>
        
        <div class="content">
            <!-- 统计信息 -->
            <div class="section">
                <h2>📊 扩展统计</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($loaded_extensions); ?></div>
                        <div class="stat-label">已安装扩展总数</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count(array_filter($loaded_extensions, function($ext) { return in_array($ext, ['mysqli', 'pdo_mysql', 'sqlite3']); })); ?></div>
                        <div class="stat-label">数据库扩展</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count(array_filter($loaded_extensions, function($ext) { return in_array($ext, ['gd', 'exif']); })); ?></div>
                        <div class="stat-label">图像处理扩展</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count(array_filter($loaded_extensions, function($ext) { return in_array($ext, ['redis', 'apcu', 'opcache']); })); ?></div>
                        <div class="stat-label">缓存扩展</div>
                    </div>
                </div>
            </div>
            
            <!-- 扩展分类 -->
            <?php foreach ($extension_categories as $category => $extensions): ?>
            <div class="section">
                <h2>🔌 <?php echo $category; ?></h2>
                <div class="extension-grid">
                    <?php foreach ($extensions as $ext): ?>
                        <?php $status = checkExtensionStatus($ext); ?>
                        <div class="extension-card">
                            <h3><?php echo $ext; ?></h3>
                            <div class="status <?php echo $status['class']; ?>">
                                <?php echo $status['status']; ?>
                            </div>
                            <p><strong>版本:</strong> <?php echo $status['version']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
            
            <!-- 功能测试 -->
            <div class="section">
                <h2>🧪 功能测试</h2>
                <table class="info-table">
                    <thead>
                        <tr>
                            <th>功能名称</th>
                            <th>状态</th>
                            <th>详细信息</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($functionality_tests as $name => $test): ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td>
                                <span class="status <?php echo $test['status'] === '可用' || $test['status'] === '已安装' ? 'enabled' : 'disabled'; ?>">
                                    <?php echo $test['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if (isset($test['memory_usage'])): ?>
                                    内存使用: <?php echo $test['memory_usage']; ?><br>
                                <?php endif; ?>
                                <?php if (isset($test['hit_rate'])): ?>
                                    命中率: <?php echo $test['hit_rate']; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- 所有扩展列表 -->
            <div class="section">
                <h2>📋 所有已安装扩展</h2>
                <div class="highlight">
                    <p><strong>共 <?php echo count($loaded_extensions); ?> 个扩展:</strong></p>
                    <p><?php echo implode(', ', $loaded_extensions); ?></p>
                </div>
            </div>
            
            <!-- 导航链接 -->
            <div class="section">
                <h2>🔗 快速导航</h2>
                <a href="index.php" class="btn">返回首页</a>
                <a href="/api/users" class="btn">测试 API</a>
                <a href="/api-test.html" class="btn">API 测试页面</a>
                <a href="http://project1.local:8080" target="_blank" class="btn">访问 Project1</a>
                <a href="http://project2.local:8080" target="_blank" class="btn">访问 Project2</a>
            </div>
            
            <!-- PHP 信息 -->
            <div class="section">
                <h2>ℹ️ PHP 信息</h2>
                <div class="extension-grid">
                    <div class="extension-card">
                        <h3>版本信息</h3>
                        <p><strong>PHP 版本:</strong> <?php echo PHP_VERSION; ?></p>
                        <p><strong>Zend 版本:</strong> <?php echo zend_version(); ?></p>
                        <p><strong>构建日期:</strong> 未知</p>
                    </div>
                    <div class="extension-card">
                        <h3>系统信息</h3>
                        <p><strong>操作系统:</strong> <?php echo PHP_OS; ?></p>
                        <p><strong>架构:</strong> <?php echo PHP_INT_SIZE * 8; ?>位</p>
                        <p><strong>时区:</strong> <?php echo date_default_timezone_get(); ?></p>
                    </div>
                    <div class="extension-card">
                        <h3>配置信息</h3>
                        <p><strong>配置文件:</strong> <?php echo php_ini_loaded_file(); ?></p>
                        <p><strong>内存限制:</strong> <?php echo ini_get('memory_limit'); ?></p>
                        <p><strong>执行时间:</strong> <?php echo ini_get('max_execution_time'); ?>秒</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // 添加一些交互效果
        document.addEventListener('DOMContentLoaded', function() {
            // 为扩展卡片添加悬停效果
            const cards = document.querySelectorAll('.extension-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px)';
                    this.style.boxShadow = '0 6px 20px rgba(0,0,0,0.15)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
                });
            });
            
            // 添加页面加载动画
            const sections = document.querySelectorAll('.section');
            sections.forEach((section, index) => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    section.style.transition = 'all 0.6s ease';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>
