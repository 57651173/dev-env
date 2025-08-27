#!/bin/sh

WATCH_DIR="/etc/nginx/conf.d"

echo "[nginx-watch] Watching $WATCH_DIR for changes..."

inotifywait -m -e create -e modify -e delete "$WATCH_DIR" |
while read -r directory events filename; do
    echo "[nginx-watch] Detected $events on $filename, reloading nginx..."
    nginx -s reload
done
