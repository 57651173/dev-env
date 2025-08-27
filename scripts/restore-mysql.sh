#!/bin/bash

# MySQL数据恢复脚本
# 使用方法: ./restore-mysql.sh [mysql57|mysql8] [backup_file]

set -e

# 配置
BACKUP_DIR="./backups"
MYSQL57_HOST="localhost"
MYSQL57_PORT="3307"
MYSQL57_USER="root"
MYSQL57_PASSWORD="root"
MYSQL8_HOST="localhost"
MYSQL8_PORT="3308"
MYSQL8_USER="root"
MYSQL8_PASSWORD="root"

# 恢复MySQL 5.7
restore_mysql57() {
    local backup_file="$1"
    
    if [ -z "$backup_file" ]; then
        echo "请指定备份文件路径"
        echo "使用方法: $0 mysql57 [backup_file]"
        exit 1
    fi
    
    if [ ! -f "$backup_file" ]; then
        echo "备份文件不存在: $backup_file"
        exit 1
    fi
    
    echo "开始恢复MySQL 5.7..."
    
    # 停止MySQL容器
    echo "停止MySQL 5.7容器..."
    docker compose stop mysql57
    
    # 备份当前数据目录
    if [ -d "./mysql57/data" ]; then
        echo "备份当前数据目录..."
        mv ./mysql57/data ./mysql57/data_backup_$(date +%Y%m%d_%H%M%S)
    fi
    
    # 恢复数据
    if [[ "$backup_file" == *.tar.gz ]]; then
        echo "从压缩包恢复数据目录..."
        mkdir -p ./mysql57/data
        tar -xzf "$backup_file" -C ./mysql57/
        echo "✓ 数据目录恢复完成"
    elif [[ "$backup_file" == *.sql ]]; then
        echo "从SQL文件恢复数据库..."
        mkdir -p ./mysql57/data
        # 启动MySQL容器进行SQL恢复
        docker compose up -d mysql57
        sleep 30  # 等待MySQL启动
        
        # 恢复SQL
        if command -v mysql >/dev/null 2>&1; then
            mysql -h"$MYSQL57_HOST" -P"$MYSQL57_PORT" -u"$MYSQL57_USER" -p"$MYSQL57_PASSWORD" < "$backup_file"
            echo "✓ SQL恢复完成"
        else
            echo "! mysql客户端未安装，无法执行SQL恢复"
        fi
    else
        echo "! 不支持的备份文件格式"
        exit 1
    fi
    
    # 启动MySQL容器
    echo "启动MySQL 5.7容器..."
    docker compose up -d mysql57
    
    echo "✓ MySQL 5.7恢复完成"
}

# 恢复MySQL 8.0
restore_mysql8() {
    local backup_file="$1"
    
    if [ -z "$backup_file" ]; then
        echo "请指定备份文件路径"
        echo "使用方法: $0 mysql8 [backup_file]"
        exit 1
    fi
    
    if [ ! -f "$backup_file" ]; then
        echo "备份文件不存在: $backup_file"
        exit 1
    fi
    
    echo "开始恢复MySQL 8.0..."
    
    # 停止MySQL容器
    echo "停止MySQL 8.0容器..."
    docker compose stop mysql8
    
    # 备份当前数据目录
    if [ -d "./mysql8/data" ]; then
        echo "备份当前数据目录..."
        mv ./mysql8/data ./mysql8/data_backup_$(date +%Y%m%d_%H%M%S)
    fi
    
    # 恢复数据
    if [[ "$backup_file" == *.tar.gz ]]; then
        echo "从压缩包恢复数据目录..."
        mkdir -p ./mysql8/data
        tar -xzf "$backup_file" -C ./mysql8/
        echo "✓ 数据目录恢复完成"
    elif [[ "$backup_file" == *.sql ]]; then
        echo "从SQL文件恢复数据库..."
        mkdir -p ./mysql8/data
        # 启动MySQL容器进行SQL恢复
        docker compose up -d mysql8
        sleep 30  # 等待MySQL启动
        
        # 恢复SQL
        if command -v mysql >/dev/null 2>&1; then
            mysql -h"$MYSQL8_HOST" -P"$MYSQL8_PORT" -u"$MYSQL8_USER" -p"$MYSQL8_PASSWORD" < "$backup_file"
            echo "✓ SQL恢复完成"
        else
            echo "! mysql客户端未安装，无法执行SQL恢复"
        fi
    else
        echo "! 不支持的备份文件格式"
        exit 1
    fi
    
    # 启动MySQL容器
    echo "启动MySQL 8.0容器..."
    docker compose up -d mysql8
    
    echo "✓ MySQL 8.0恢复完成"
}

# 显示帮助信息
show_help() {
    echo "MySQL数据恢复脚本"
    echo ""
    echo "使用方法:"
    echo "  $0 mysql57 [backup_file]  - 恢复MySQL 5.7"
    echo "  $0 mysql8 [backup_file]   - 恢复MySQL 8.0"
    echo ""
    echo "示例:"
    echo "  $0 mysql57 ./backups/mysql57_data_20250101_120000.tar.gz"
    echo "  $0 mysql8 ./backups/mysql8_backup_20250101_120000.sql"
    echo ""
    echo "注意事项:"
    echo "  - 恢复前会自动备份当前数据"
    echo "  - 支持.tar.gz和.sql格式的备份文件"
    echo "  - 恢复过程中会重启对应的MySQL容器"
}

# 主函数
main() {
    case "${1:-}" in
        "mysql57")
            restore_mysql57 "$2"
            ;;
        "mysql8")
            restore_mysql8 "$2"
            ;;
        "help"|"-h"|"--help")
            show_help
            ;;
        "")
            echo "请指定要恢复的MySQL实例"
            show_help
            exit 1
            ;;
        *)
            echo "未知的MySQL实例: $1"
            show_help
            exit 1
            ;;
    esac
}

# 执行主函数
main "$@"
