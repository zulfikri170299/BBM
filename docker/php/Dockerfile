# ============================================================
# STAGE 1: Build frontend assets (Node.js)
# ============================================================
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy package files dulu untuk cache layer
COPY package.json package-lock.json ./
RUN npm ci

# Copy seluruh source untuk build
COPY . .
RUN npm run build

# ============================================================
# STAGE 2: PHP Application
# ============================================================
FROM php:8.2-fpm

ARG HOST_UID=1000
ARG HOST_GID=1000

# Fix user id conflicts: only mod if UID is > 0
RUN if [ "$HOST_UID" != "0" ]; then \
        groupmod -o -g "${HOST_GID}" www-data && \
        usermod -o -u "${HOST_UID}" -g "${HOST_GID}" www-data; \
    fi

# Install system dependencies & PHP extensions
RUN set -eux; \
    if [ -f /etc/apt/sources.list ]; then \
        sed -i 's|http://deb.debian.org|https://deb.debian.org|g' /etc/apt/sources.list; \
    fi; \
    if [ -f /etc/apt/sources.list.d/debian.sources ]; then \
        sed -i 's|http://deb.debian.org|https://deb.debian.org|g' /etc/apt/sources.list.d/debian.sources; \
    fi; \
    printf '%s\n' \
      'Acquire::Retries "8";' \
      'Acquire::ForceIPv4 "true";' \
      'Acquire::http::Timeout "30";' \
      'Acquire::https::Timeout "30";' \
      > /etc/apt/apt.conf.d/99-network-hardening; \
    for i in 1 2 3; do \
      apt-get update && apt-get install -y --no-install-recommends \
        git \
        curl \
        unzip \
        libicu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libsqlite3-dev && break; \
      if [ "${i}" = "3" ]; then exit 1; fi; \
      echo "apt failed, retrying in $((i * 5))s..."; \
      sleep $((i * 5)); \
      rm -rf /var/lib/apt/lists/*; \
    done; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
      bcmath \
      gd \
      intl \
      mbstring \
      pdo_sqlite \
      zip; \
    rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy seluruh source aplikasi
COPY . .

# Copy custom PHP configuration for upload limits
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Copy hasil build frontend dari stage 1
COPY --from=node-builder /app/public/build ./public/build

# Install PHP dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Simpan seluruh folder public ke /public-init
# (akan di-copy ke named volume saat entrypoint berjalan pertama kali)
RUN cp -r /var/www/html/public /public-init

# Set permission
RUN mkdir -p storage/logs storage/framework/{sessions,views,cache} bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Izinkan PHP-FPM jalan sebagai root untuk Container BBM
RUN sed -i 's/user = www-data/user = root/g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/group = www-data/group = root/g' /usr/local/etc/php-fpm.d/www.conf

# Copy & set entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm", "-R"]
