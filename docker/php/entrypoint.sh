#!/bin/sh
# Entrypoint da imagem de produção (app, queue, scheduler).
#
#   RUN_MIGRATIONS=true  roda `migrate --force` antes de iniciar o php-fpm (queue e scheduler ignoram).
set -e

if [ "${APP_ENV:-production}" = "production" ]; then
    php artisan optimize --no-interaction
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ] && [ "$1" = "php-fpm" ]; then
    php artisan migrate --force --no-interaction
fi

exec "$@"
