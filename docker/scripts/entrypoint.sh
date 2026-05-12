#!/bin/sh
set -eu

wait_for_database() {
    if [ -z "${DB_HOST:-}" ] || [ -z "${DB_PORT:-}" ]; then
        return 0
    fi

    echo "Waiting for database at ${DB_HOST}:${DB_PORT}..."

    until php -r '
        $host = getenv("DB_HOST");
        $port = (int) getenv("DB_PORT");
        $connection = @fsockopen($host, $port, $errno, $error, 2);
        if ($connection) {
            fclose($connection);
            exit(0);
        }
        fwrite(STDERR, "Database not ready yet\n");
        exit(1);
    '; do
        sleep 2
    done
}

install_dependencies_if_needed() {
    if [ ! -f /app/vendor/autoload.php ]; then
        echo "Installing Composer dependencies..."
        composer install --prefer-dist --no-interaction --no-progress
    fi
}

clear_cached_config() {
    php artisan config:clear --ansi >/dev/null 2>&1 || true
}

prepare_storage() {
    mkdir -p \
        /app/storage/framework/cache/data \
        /app/storage/framework/sessions \
        /app/storage/framework/views \
        /app/storage/logs \
        /app/bootstrap/cache
}

run_migrations_if_enabled() {
    if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
        echo "Running database migrations..."
        php artisan migrate --force
    fi
}

cd /app

prepare_storage
install_dependencies_if_needed
clear_cached_config
wait_for_database
run_migrations_if_enabled

exec "$@"