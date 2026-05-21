FROM dunglas/frankenphp

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN install-php-extensions \
    bcmath ctype curl dom fileinfo \
    mbstring pdo pdo_mysql tokenizer xml zip

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache \
    && chmod -R 775 storage bootstrap/cache

CMD sh -c "php artisan config:cache && \
           php artisan route:cache && \
           php artisan view:cache && \
           php artisan migrate --force && \
           php artisan storage:link --force && \
           php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"
