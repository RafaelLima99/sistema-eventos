# syntax=docker/dockerfile:1
#
# Dockerfile único (multi-stage) para dev e produção.
# Contexto de build: raiz do repositório.
#
#   base    PHP-FPM + extensões + configs comuns
#   dev     base + Composer + Xdebug (código montado por bind mount)
#   vendor  dependências PHP de produção (sem dev)
#   web     Nginx com public/ (frontend estático: Bootstrap/JS em public/)
#   prod    imagem final da aplicação (último stage = alvo padrão)

ARG PHP_VERSION=8.5
ARG COMPOSER_VERSION=2
ARG NGINX_VERSION=alpine


FROM composer:${COMPOSER_VERSION} AS composer-bin


# ---------------------------------------------------------------------------
# base
# ---------------------------------------------------------------------------
FROM php:${PHP_VERSION}-fpm-alpine AS base

# Ajustáveis por variável de ambiente em tempo de execução.
ENV PHP_FPM_PM_MAX_CHILDREN=10 \
    PHP_FPM_PM_START_SERVERS=3 \
    PHP_FPM_PM_MIN_SPARE_SERVERS=2 \
    PHP_FPM_PM_MAX_SPARE_SERVERS=5 \
    PHP_MEMORY_LIMIT=256M \
    PHP_UPLOAD_MAX_FILESIZE=20M

COPY --from=ghcr.io/mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
        bcmath \
        exif \
        gd \
        intl \
        opcache \
        pcntl \
        pdo_mysql \
        zip

COPY docker/php/conf.d/app.ini "$PHP_INI_DIR/conf.d/zz-app.ini"
COPY docker/php/php-fpm.d/pool.conf /usr/local/etc/php-fpm.d/zz-pool.conf

WORKDIR /var/www/html


# ---------------------------------------------------------------------------
# dev
# ---------------------------------------------------------------------------
FROM base AS dev

RUN install-php-extensions xdebug

COPY --from=composer-bin /usr/bin/composer /usr/local/bin/composer
COPY docker/php/conf.d/xdebug.ini "$PHP_INI_DIR/conf.d/zz-xdebug.ini"

# O usuário é definido no compose (UID/GID do host) para evitar arquivos de root.
ENV COMPOSER_HOME=/tmp/composer


# ---------------------------------------------------------------------------
# vendor
# ---------------------------------------------------------------------------
FROM base AS vendor

COPY --from=composer-bin /usr/bin/composer /usr/local/bin/composer

COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --no-scripts \
        --no-autoloader \
        --prefer-dist

COPY . .
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative


# ---------------------------------------------------------------------------
# web (Nginx)
# ---------------------------------------------------------------------------
FROM nginxinc/nginx-unprivileged:${NGINX_VERSION} AS web

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY --chown=nginx:nginx public /var/www/html/public

# Uploads públicos: o volume de storage é montado em /var/www/html/storage (somente leitura).
USER root
RUN ln -s ../storage/app/public /var/www/html/public/storage
USER nginx

EXPOSE 8080


# ---------------------------------------------------------------------------
# prod (último stage = alvo padrão)
# ---------------------------------------------------------------------------
FROM base AS prod

RUN apk add --no-cache fcgi

COPY docker/php/conf.d/opcache.ini "$PHP_INI_DIR/conf.d/zz-opcache.ini"
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

COPY --from=vendor --chown=www-data:www-data /var/www/html /var/www/html

RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1
