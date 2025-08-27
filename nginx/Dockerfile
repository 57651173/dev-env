FROM nginx:alpine

# 安装 inotify 工具
RUN apk add --no-cache inotify-tools bash

# 添加自动 reload 脚本
COPY auto-reload.sh /usr/local/bin/auto-reload.sh
RUN chmod +x /usr/local/bin/auto-reload.sh

CMD ["/bin/sh", "-c", "nginx -g 'daemon off;' & /usr/local/bin/auto-reload.sh"]
