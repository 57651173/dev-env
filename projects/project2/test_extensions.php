<?php
echo "<h1>PHP 7.4 扩展测试</h1>";
echo "<h2>PHP 版本: " . phpversion() . "</h2>";

echo "<h3>已安装的扩展:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
echo "<ul>";
foreach ($extensions as $ext) {
    echo "<li>$ext</li>";
}
echo "</ul>";

echo "<h3>关键扩展测试:</h3>";

// 测试GD扩展
if (extension_loaded('gd')) {
    echo "<p>✅ GD扩展已安装</p>";
    $gd_info = gd_info();
    echo "<p>GD版本: " . $gd_info['GD Version'] . "</p>";
} else {
    echo "<p>❌ GD扩展未安装</p>";
}

// 测试Redis扩展
if (extension_loaded('redis')) {
    echo "<p>✅ Redis扩展已安装</p>";
    echo "<p>Redis扩展版本: " . phpversion('redis') . "</p>";
} else {
    echo "<p>❌ Redis扩展未安装</p>";
}

// 测试MongoDB扩展
if (extension_loaded('mongodb')) {
    echo "<p>✅ MongoDB扩展已安装</p>";
    echo "<p>MongoDB扩展版本: " . phpversion('mongodb') . "</p>";
} else {
    echo "<p>❌ MongoDB扩展未安装</p>";
}

// 测试其他常用扩展
$common_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'curl', 'json', 'xml', 'zip'];
foreach ($common_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p>✅ $ext 扩展已安装</p>";
    } else {
        echo "<p>❌ $ext 扩展未安装</p>";
    }
}

echo "<h3>PHP配置信息:</h3>";
echo "<p>内存限制: " . ini_get('memory_limit') . "</p>";
echo "<p>上传文件大小限制: " . ini_get('upload_max_filesize') . "</p>";
echo "<p>最大执行时间: " . ini_get('max_execution_time') . "秒</p>";
echo "<p>时区: " . ini_get('date.timezone') . "</p>";
?>
