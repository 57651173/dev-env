<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project2 - PHP 7.4 演示</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .feature { background: #e8f5e8; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #4CAF50; }
        .version { background: #2196F3; color: white; padding: 5px 10px; border-radius: 3px; display: inline-block; margin-bottom: 20px; }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
        .info { background: #e3f2fd; padding: 15px; border-radius: 5px; border-left: 4px solid #2196F3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="version">PHP <?php echo PHP_VERSION; ?></div>
        <h1>🚀 Project2 - PHP 7.4 特性演示</h1>
        
        <div class="info">
            <strong>项目信息：</strong> 这是一个运行在 PHP 7.4 环境下的演示项目，展示了 PHP 7.4 的新特性和改进。
        </div>

        <h2>✨ PHP 7.4 新特性</h2>
        
        <div class="feature">
            <h3>1. 类型属性 (Typed Properties)</h3>
            <?php
            class User {
                public string $name;
                public int $age;
                public ?string $email; // 可空类型
                
                public function __construct(string $name, int $age, ?string $email = null) {
                    $this->name = $name;
                    $this->age = $age;
                    $this->email = $email;
                }
            }
            
            try {
                $user = new User("张三", 25, "zhangsan@example.com");
                echo "<p><strong>用户信息：</strong> {$user->name}, {$user->age}岁, {$user->email}</p>";
            } catch (TypeError $e) {
                echo "<p><strong>类型错误：</strong> " . $e->getMessage() . "</p>";
            }
            ?>
        </div>

        <div class="feature">
            <h3>2. 箭头函数 (Arrow Functions)</h3>
            <?php
            $numbers = [1, 2, 3, 4, 5];
            $doubled = array_map(fn($n) => $n * 2, $numbers);
            echo "<p><strong>原始数组：</strong> [" . implode(', ', $numbers) . "]</p>";
            echo "<p><strong>翻倍后：</strong> [" . implode(', ', $doubled) . "]</p>";
            ?>
        </div>

        <div class="feature">
            <h3>3. 空合并赋值运算符 (Null Coalescing Assignment)</h3>
            <?php
            $config = [];
            $config['debug'] ??= false;
            $config['timeout'] ??= 30;
            $config['cache'] ??= true;
            
            echo "<p><strong>配置信息：</strong></p>";
            echo "<ul>";
            foreach ($config as $key => $value) {
                echo "<li><code>$key</code>: " . var_export($value, true) . "</li>";
            }
            echo "</ul>";
            ?>
        </div>

        <div class="feature">
            <h3>4. 数组展开运算符 (Array Spread Operator)</h3>
            <?php
            $fruits = ['apple', 'banana'];
            $vegetables = ['carrot', 'lettuce'];
            $food = [...$fruits, 'orange', ...$vegetables];
            
            echo "<p><strong>合并后的食物列表：</strong> [" . implode(', ', $food) . "]</p>";
            ?>
        </div>

        <div class="feature">
            <h3>5. 数值字面量分隔符 (Numeric Literal Separator)</h3>
            <?php
            $largeNumber = 1_000_000;
            $price = 99_99;
            $binary = 0b1010_0001_1000_0101;
            
            echo "<p><strong>大数字：</strong> {$largeNumber}</p>";
            echo "<p><strong>价格：</strong> {$price}</p>";
            echo "<strong>二进制：</strong> " . decbin($binary) . " (十进制: {$binary})</p>";
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
            <p><strong>操作系统：</strong> <?php echo PHP_OS; ?></p>
            <p><strong>服务器软件：</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
            <p><strong>内存限制：</strong> <?php echo ini_get('memory_limit'); ?></p>
            <p><strong>最大执行时间：</strong> <?php echo ini_get('max_execution_time'); ?> 秒</p>
            <p><strong>上传文件大小限制：</strong> <?php echo ini_get('upload_max_filesize'); ?></p>
        </div>

        <div class="info">
            <strong>提示：</strong> 这个项目展示了 PHP 7.4 的主要新特性。PHP 7.4 在性能、类型安全性和开发体验方面都有显著改进。
        </div>
    </div>
</body>
</html>