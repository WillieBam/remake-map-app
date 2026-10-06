FROM docker.io/php:8.1-cli-alpine

# Fix SSL certificate verify failed: use http instead of https for Alpine repository mirrors
RUN sed -i 's/https/http/g' /etc/apk/repositories

# Install system dependencies, build tools, nodejs, npm, ca-certificates
RUN apk add --no-cache \
    ca-certificates \
    curl \
    git \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    linux-headers \
    nodejs \
    npm \
    oniguruma-dev \
    unzip \
    zip

    
# Install required PHP extensions for Laravel & MySQL
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer from docker.io
COPY --from=docker.io/library/composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
