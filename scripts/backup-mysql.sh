#!/bin/bash

# MySQL数据备份脚本
# 使用方法: ./backup-mysql.sh [mysql57|mysql8|all]

set -e

# 配置
BACKUP_DIR="./backups"
DATE=$(date +%Y%m%d_%H%M%S)
MYSQL57_HOST="127.0.0.1"
MYSQL57_PORT="3307"
MYSQL57_USER="root"
MYSQL57_PASSWORD="root"
MYSQL8_HOST="127.0.0.1"
MYSQL8_PORT="3308"
MYSQL8_USER="root"
MYSQL8_PASSWORD="root"

# 创建备份目录
mkdir -p "$BACKUP_DIR"

# 备份MySQL 5.7
backup_mysql57() {
    echo "开始备份MySQL 5.7..."
    
    # 使用mysqldump备份
    if command -v mysqldump >/dev/null 2>&1; then
        echo "使用mysqldump备份MySQL 5.7..."
        mysqldump -h"$MYSQL57_HOST" -P"$MYSQL57_PORT" -u"$MYSQL57_USER" -p"$MYSQL57_PASSWORD" \
            --all-databases --single-transaction --routines --triggers \
            --add-drop-database --add-drop-table \
            > "$BACKUP_DIR/mysql57_backup_$DATE.sql"
        echo "✓ MySQL 5.7 SQL备份完成: mysql57_backup_$DATE.sql"
    else
        echo "! mysqldump未安装，跳过SQL备份"
    fi
    
    # 备份数据目录
    if [ -d "./mysql57/data" ]; then
        echo "备份MySQL 5.7数据目录..."
        tar -czf "$BACKUP_DIR/mysql57_data_$DATE.tar.gz" -C ./mysql57 data
        echo "✓ MySQL 5.7数据目录备份完成: mysql57_data_$DATE.tar.gz"
    else
        echo "! MySQL 5.7数据目录不存在"
    fi
}

# 备份MySQL 8.0
backup_mysql8() {
    echo "开始备份MySQL 8.0..."
    
    # 使用mysqldump备份
    if command -v mysqldump >/dev/null 2>&1; then
        echo "使用mysqldump备份MySQL 8.0..."
        mysqldump -h"$MYSQL8_HOST" -P"$MYSQL8_PORT" -u"$MYSQL8_USER" -p"$MYSQL8_PASSWORD" \
            --all-databases --single-transaction --routines --triggers \
            --add-drop-database --add-drop-table \
            > "$BACKUP_DIR/mysql8_backup_$DATE.sql"
        echo "✓ MySQL 8.0 SQL备份完成: mysql8_backup_$DATE.sql"
    else
        echo "! mysqldump未安装，跳过SQL备份"
    fi
    
    # 备份数据目录
    if [ -d "./mysql8/data" ]; then
        echo "备份MySQL 8.0数据目录..."
        tar -czf "$BACKUP_DIR/mysql8_data_$DATE.tar.gz" -C ./mysql8 data
        echo "✓ MySQL 8.0数据目录备份完成: mysql8_data_$DATE.tar.gz"
    else
        echo "! MySQL 8.0数据目录不存在"
    fi
}

# 主函数
main() {
    case "${1:-all}" in
        "mysql57")
            backup_mysql57
            ;;
        "mysql8")
            backup_mysql8
            ;;
        "all")
            backup_mysql57
            backup_mysql8
            ;;
        *)
            echo "使用方法: $0 [mysql57|mysql8|all]"
            echo "默认备份所有MySQL实例"
            backup_mysql57
            backup_mysql8
            ;;
    esac
    
    echo ""
    echo "备份完成！备份文件保存在: $BACKUP_DIR"
    echo "备份时间: $DATE"
}

# 执行主函数
main "$@"
