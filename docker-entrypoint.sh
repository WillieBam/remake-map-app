#!/bin/sh
set -e

# If vendor/autoload.php does not exist, install Composer dependencies automatically
if [ ! -f "/var/www/html/vendor/autoload.php" ]; then
    echo "vendor/autoload.php not found. Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --ignore-platform-reqs
fi

exec php artisan serve --host=0.0.0.0 --port=8000
