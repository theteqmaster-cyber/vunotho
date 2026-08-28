#!/bin/bash
set -e

# Default PORT to 80 if not set by host platform (e.g. Render / SnapDeploy sets $PORT e.g. 10000 or 8080)
PORT="${PORT:-80}"

echo "==> Configuring Apache to listen on port ${PORT}..."
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Ensure permissions on upload and temporary directories
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

echo "==> Starting Vunotho Agricultural OS..."
exec apache2-foreground
