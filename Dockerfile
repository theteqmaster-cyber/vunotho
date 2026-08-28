# ==============================================================================
# VUNOTHO ENTERPRISE AGRICULTURAL OPERATING SYSTEM
# Production Docker Container for SnapDeploy, Render, Fly.io & Docker Compose
# ==============================================================================

FROM php:8.3-apache

# 1. Install system dependencies & PostgreSQL / SQLite / MySQL PDO drivers
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libsqlite3-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    ca-certificates \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pgsql \
    pdo_mysql \
    pdo_sqlite \
    opcache \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache Modules
RUN a2enmod rewrite headers expires deflate

# 3. Configure Apache DocumentRoot & AllowOverride
RUN sed -ri -e 's!/var/www/html!/var/www/html!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && echo '<Directory /var/www/html>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# 4. Configure Production PHP Settings (Opcache & Security)
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'upload_max_filesize=32M'; \
    echo 'post_max_size=32M'; \
    echo 'memory_limit=256M'; \
    echo 'date.timezone=Africa/Harare'; \
    echo 'session.cookie_httponly=1'; \
    echo 'session.use_strict_mode=1'; \
} > /usr/local/etc/php/conf.d/vunotho-production.ini

# 5. Set Working Directory & Copy Application Code
WORKDIR /var/www/html
COPY . /var/www/html/

# 6. Copy and configure Entrypoint Script
COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

# 7. Set Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 8. Expose Standard & Cloud Web Ports
EXPOSE 80 8080

# 9. Healthcheck Endpoint
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:${PORT:-80}/api/stats.php || exit 1

ENTRYPOINT ["/docker-entrypoint.sh"]
