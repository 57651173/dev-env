<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project3 - PHP 8.2 演示</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .feature { background: #e8f5e8; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #4CAF50; }
        .version { background: #FF5722; color: white; padding: 5px 10px; border-radius: 3px; display: inline-block; margin-bottom: 20px; }
        h1 { color: #333; border-bottom: 2px solid #FF5722; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
        .info { background: #e3f2fd; padding: 15px; border-radius: 5px; border-left: 4px solid #2196F3; }
        .warning { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; }
        .performance { background: #e8f5e8; padding: 15px; border-radius: 5px; border-left: 4px solid #4CAF50; }
    </style>
</head>
<body>
    <div class="container">
        <div class="version">PHP <?php echo PHP_VERSION; ?></div>
        <h1>🚀 Project3 - PHP 8.2 特性演示</h1>
        
        <div class="info">
            <strong>项目信息：</strong> 这是一个运行在 PHP 8.2 环境下的演示项目，展示了 PHP 8.2 的最新特性和性能改进。
        </div>

        <h2>✨ PHP 8.2 新特性</h2>
        
        <div class="feature">
            <h3>1. 只读类 (Readonly Classes)</h3>
            <?php
            if (PHP_VERSION_ID >= 80200) {
                readonly class Config {
                    public function __construct(
                        public string $appName,
                        public string $version,
                        public bool $debug
                    ) {}
                }
                
                try {
                    $config = new Config("MyApp", "1.0.0", false);
                    echo "<p><strong>配置信息：</strong> {$config->appName} v{$config->version}, Debug: " . ($config->debug ? '开启' : '关闭') . "</p>";
                    
                    // 尝试修改只读属性（会抛出错误）
                    try {
                        $config->appName = "NewApp";
                    } catch (Error $e) {
                        echo "<p><strong>只读属性保护：</strong> " . $e->getMessage() . " ✅</p>";
                    }
                } catch (Error $e) {
                    echo "<p><strong>错误：</strong> " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p><strong>只读类：</strong> 需要 PHP 8.2+ 支持</p>";
            }
            ?>
        </div>

        <div class="feature">
            <h3>2. 独立类型 (null & false types)</h3>
            <?php
            if (PHP_VERSION_ID >= 80200) {
                function processData(null|false|string $data): null|false|string {
                    if ($data === null) {
                        return "数据为空";
                    }
                    if ($data === false) {
                        return "处理失败";
                    }
                    return "处理成功: " . $data;
                }
                
                echo "<p><strong>null 类型：</strong> " . processData(null) . "</p>";
                echo "<p><strong>false 类型：</strong> " . processData(false) . "</p>";
                echo "<p><strong>string 类型：</strong> " . processData("测试数据") . "</p>";
            } else {
                echo "<p><strong>独立类型：</strong> 需要 PHP 8.2+ 支持</p>";
            }
            ?>
        </div>

        <div class="feature">
            <h3>3. 随机扩展改进 (Random Extension)</h3>
            <?php
            if (PHP_VERSION_ID >= 80200) {
                try {
                    $random = new Random\Randomizer();
                    $bytes = $random->getBytes(16);
                    $int = $random->getInt(1, 100);
                    $float = $random->getFloat(0, 1);
                    
                    echo "<p><strong>随机字节：</strong> " . bin2hex($bytes) . "</p>";
                    echo "<p><strong>随机整数：</strong> {$int}</p>";
                    echo "<p><strong>随机浮点数：</strong> " . number_format($float, 4) . "</p>";
                } catch (Error $e) {
                    echo "<p><strong>随机扩展：</strong> " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p><strong>随机扩展：</strong> 需要 PHP 8.2+ 支持</p>";
            }
            ?>
        </div>

        <div class="feature">
            <h3>4. 常量表达式中的枚举 (Enums in Constant Expressions)</h3>
            <?php
            if (PHP_VERSION_ID >= 80100) {
                enum Status: string {
                    case PENDING = 'pending';
                    case ACTIVE = 'active';
                    case INACTIVE = 'inactive';
                }
                
                class User {
                    public const DEFAULT_STATUS = Status::PENDING;
                    
                    public function __construct(
                        public string $name,
                        public Status $status = self::DEFAULT_STATUS
                    ) {}
                }
                
                $user = new User("李四");
                echo "<p><strong>用户状态：</strong> {$user->status->value}</p>";
                echo "<p><strong>默认状态：</strong> " . User::DEFAULT_STATUS->value . "</p>";
            } else {
                echo "<p><strong>枚举：</strong> 需要 PHP 8.1+ 支持</p>";
            }
            ?>
        </div>

        <div class="feature">
            <h3>5. 性能优化特性</h3>
            <?php
            // 测试 OPcache 状态
            if (function_exists('opcache_get_status')) {
                $opcacheStatus = opcache_get_status();
                if ($opcacheStatus) {
                    echo "<p><strong>OPcache 状态：</strong> " . ($opcacheStatus['opcache_enabled'] ? '已启用' : '未启用') . "</p>";
                    if ($opcacheStatus['opcache_enabled']) {
                        echo "<p><strong>内存使用：</strong> " . number_format($opcacheStatus['memory_usage']['used_memory'] / 1024 / 1024, 2) . " MB</p>";
                        echo "<p><strong>命中率：</strong> " . number_format($opcacheStatus['opcache_statistics']['opcache_hit_rate'], 2) . "%</p>";
                    }
                }
            }
            
            // 测试 JIT 状态
            if (function_exists('opcache_get_status')) {
                $jitStatus = opcache_get_status(false);
                if ($jitStatus && isset($jitStatus['jit'])) {
                    echo "<p><strong>JIT 状态：</strong> " . ($jitStatus['jit']['enabled'] ? '已启用' : '未启用') . "</p>";
                }
            }
            ?>
        </div>

        <h2>🔧 已安装的扩展</h2>
        <div class="feature">
            <?php
            $extensions = ['pdo_mysql', 'mysqli', 'gd', 'zip', 'intl', 'xml', 'mbstring', 'curl', 'opcache', 'bcmath', 'redis'];
            $installed = [];
            $notInstalled = [];
            
            foreach ($extensions as $ext) {
                if (extension_loaded($ext)) {
                    $installed[] = $ext;
                } else {
                    $notInstalled[] = $ext;
                }
            }
            
            echo "<p><strong>已安装扩展：</strong></p>";
            if (!empty($installed)) {
                echo "<ul>";
                foreach ($installed as $ext) {
                    echo "<li><code>$ext</code> ✅</li>";
                }
                echo "</ul>";
            }
            
            if (!empty($notInstalled)) {
                echo "<p><strong>未安装扩展：</strong></p>";
                echo "<ul>";
                foreach ($notInstalled as $ext) {
                    echo "<li><code>$ext</code> ❌</li>";
                }
                echo "</ul>";
            }
            ?>
        </div>

        <h2>📊 系统信息</h2>
        <div class="feature">
            <p><strong>PHP 版本：</strong> <?php echo PHP_VERSION; ?></p>
            <p><strong>Zend 版本：</strong> <?php echo zend_version(); ?></p>
            <p><strong>操作系统：</strong> <?php echo PHP_OS; ?></p>
            <p><strong>服务器软件：</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
            <p><strong>内存限制：</strong> <?php echo ini_get('memory_limit'); ?></p>
            <p><strong>最大执行时间：</strong> <?php echo ini_get('max_execution_time'); ?> 秒</p>
            <p><strong>上传文件大小限制：</strong> <?php echo ini_get('upload_max_filesize'); ?></p>
        </div>

        <h2>🚀 性能测试</h2>
        <div class="performance">
            <?php
            // 简单的性能测试
            $startTime = microtime(true);
            
            // 执行一些操作
            $array = [];
            for ($i = 0; $i < 10000; $i++) {
                $array[] = $i * 2;
            }
            
            $sum = array_sum($array);
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000; // 转换为毫秒
            
            echo "<p><strong>性能测试结果：</strong></p>";
            echo "<p>创建 10,000 个元素的数组并计算总和：</p>";
            echo "<p>总和：{$sum}</p>";
            echo "<p>执行时间：{$executionTime} 毫秒</p>";
            ?>
        </div>

        <div class="info">
            <strong>提示：</strong> PHP 8.2 带来了显著的性能提升和新特性。只读类、独立类型和随机扩展改进让代码更加安全和高效。
        </div>

        <div class="warning">
            <strong>注意：</strong> 某些 PHP 8.2 特性可能需要特定的扩展支持。如果遇到错误，请检查扩展是否正确安装。
        </div>
    </div>
</body>
</html>
