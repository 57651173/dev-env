<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project1 - PHP 7.2</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        .info {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .php-version {
            color: #e74c3c;
            font-weight: bold;
        }
        .links {
            margin-top: 20px;
        }
        .links a {
            display: inline-block;
            margin: 5px 10px 5px 0;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .links a:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Project1 - PHP 7.2 开发环境</h1>
        
        <div class="info">
            <h3>环境信息</h3>
            <p><strong>PHP版本:</strong> <span class="php-version"><?php echo phpversion(); ?></span></p>
            <p><strong>服务器:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Nginx'; ?></p>
            <p><strong>当前时间:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            <p><strong>访问域名:</strong> <?php echo $_SERVER['HTTP_HOST'] ?? 'localhost'; ?></p>
        </div>

        <div class="info">
            <h3>已安装的扩展</h3>
            <p>共安装了 <strong><?php echo count(get_loaded_extensions()); ?></strong> 个扩展</p>
            <ul>
                <?php
                $extensions = get_loaded_extensions();
                sort($extensions);
                foreach (array_slice($extensions, 0, 10) as $ext) {
                    echo "<li>$ext</li>";
                }
                if (count($extensions) > 10) {
                    echo "<li>... 还有 " . (count($extensions) - 10) . " 个扩展</li>";
                }
                ?>
            </ul>
        </div>

        <div class="links">
            <a href="test_extensions.php">🔍 查看所有扩展</a>
            <a href="phpinfo.php">📊 PHP信息</a>
            <a href="http://project2.local:8080" target="_blank">🌐 访问 Project2</a>
        </div>

        <div style="margin-top: 30px; padding: 15px; background: #e8f5e8; border-radius: 5px;">
            <h4>✅ 域名配置成功！</h4>
            <p>您现在可以通过 <strong>http://project1.local:8080</strong> 访问此项目。</p>
            <p>无需再使用 <code>/project1/</code> 路径，直接访问根目录即可。</p>
        </div>
    </div>
</body>
</html>