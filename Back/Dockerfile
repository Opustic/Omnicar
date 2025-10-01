FROM php:8.3-apache

# Installe les dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libsqlite3-dev \
    pkg-config \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Configure et installe les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_sqlite zip bcmath

# Installe Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définit le répertoire de travail
WORKDIR /var/www/html

# Copie le code Laravel dans le container
COPY . /var/www/html

# Installe les dépendances Laravel en mode production
RUN composer install --optimize-autoloader --no-dev

# Configure les permissions, génère la clé d'app et cache les configs
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/database \
    && php artisan key:generate \
    && php artisan config:cache \
    && php artisan route:cache

# Active le module rewrite pour Apache (utile pour Laravel)
RUN a2enmod rewrite

# Expose le port 80
EXPOSE 80

# Commande de démarrage : lance Apache
CMD ["apache2-foreground"]