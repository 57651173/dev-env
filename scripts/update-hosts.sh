#!/bin/bash

# 更新hosts文件脚本 - 将域名映射改为内网IP
# 使用方法: sudo ./update-hosts.sh

echo "🔧 开始更新hosts文件..."

# 检查是否以root权限运行
if [ "$EUID" -ne 0 ]; then
    echo "❌ 请使用sudo权限运行此脚本"
    echo "使用方法: sudo ./update-hosts.sh"
    exit 1
fi

# 备份当前hosts文件
echo "📋 备份当前hosts文件..."
cp /etc/hosts /etc/hosts.backup.$(date +%Y%m%d_%H%M%S)
echo "✅ 备份完成: /etc/hosts.backup.$(date +%Y%m%d_%H%M%S)"

# 检测内网IP
echo "🔍 检测内网IP地址..."
INTERNAL_IP=$(ifconfig | grep "inet " | grep -v 127.0.0.1 | head -1 | awk '{print $2}')

if [ -z "$INTERNAL_IP" ]; then
    echo "❌ 无法检测到内网IP地址"
    echo "请手动设置内网IP地址"
    exit 1
fi

echo "✅ 检测到内网IP: $INTERNAL_IP"

# 更新hosts文件
echo "📝 更新hosts文件..."

# 移除旧的project域名映射
sed -i '' '/127.0.0.1.*project/d' /etc/hosts

# 添加新的内网IP域名映射
echo "$INTERNAL_IP project1.local" >> /etc/hosts
echo "$INTERNAL_IP www.project1.local" >> /etc/hosts
echo "$INTERNAL_IP project2.local" >> /etc/hosts
echo "$INTERNAL_IP www.project2.local" >> /etc/hosts
echo "$INTERNAL_IP project3.local" >> /etc/hosts
echo "$INTERNAL_IP www.project3.local" >> /etc/hosts

echo "✅ hosts文件更新完成"

# 清除DNS缓存
echo "🧹 清除DNS缓存..."
if command -v dscacheutil &> /dev/null; then
    dscacheutil -flushcache
    echo "✅ macOS DNS缓存已清除"
elif command -v systemctl &> /dev/null; then
    systemctl restart systemd-resolved
    echo "✅ Linux DNS缓存已清除"
else
    echo "⚠️  无法自动清除DNS缓存，请手动清除"
fi

# 显示更新后的配置
echo ""
echo "📋 更新后的hosts配置:"
echo "===================="
grep "project" /etc/hosts
echo "===================="

echo ""
echo "🌐 现在您可以通过以下地址访问:"
echo "本地访问:"
echo "  - Project1 (PHP 7.2): http://project1.local:8080"
echo "  - Project2 (PHP 7.4): http://project2.local:8080"
echo "  - Project3 (PHP 8.2): http://project3.local:8080"
echo ""
echo "内网访问:"
echo "  - 所有项目: http://$INTERNAL_IP:8080"
echo ""
echo "✅ hosts文件更新完成！"
