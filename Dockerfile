# Usar PHP 8.2 con Apache
FROM php:8.2-apache

# Establecer variables de entorno
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV APACHE_LOG_DIR=/var/log/apache2

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Node.js LTS
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache
RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar composer.json y composer.lock primero (para cache de capas)
COPY composer.json composer.lock ./

# Instalar dependencias PHP
RUN composer install --no-dev --no-scripts --no-autoloader

# Copiar package.json si existe (para cache de capas)
COPY package*.json ./

# Instalar dependencias Node.js si existe package.json
RUN if [ -f "package.json" ]; then \
        echo "📦 Instalando dependencias Node.js..." && \
        npm ci --include=dev && \
        echo "✅ Dependencias Node.js instaladas"; \
    else \
        echo "⚠️  No se encontró package.json, omitiendo instalación de Node.js"; \
    fi

# Copiar el resto de archivos del proyecto
COPY . .

# Finalizar instalación de Composer
RUN composer dump-autoload --no-dev --optimize

# Construir assets si existen
RUN if [ -f "package.json" ]; then \
        echo "🏗️  Construyendo assets..." && \
        echo "🧹 Limpiando cache de npm..." && \
        npm cache clean --force && \
        echo "🚀 Ejecutando build..." && \
        NODE_ENV=production npm run build && \
        echo "✅ Assets construidos exitosamente" && \
        ls -la public/build && \
        echo "📁 Contenido del directorio build:" && \
        ls -la public/build/assets/ | head -10 && \
        echo "🔄 Verificando manifest.json..." && \
        if [ -f "public/build/.vite/manifest.json" ]; then \
            cp public/build/.vite/manifest.json public/build/manifest.json && \
            echo "✅ Manifest copiado exitosamente"; \
        fi && \
        if [ -f "public/build/manifest.json" ]; then \
            echo "📄 Manifest.json existe y contiene:" && \
            cat public/build/manifest.json | head -10; \
        else \
            echo "❌ ERROR: No se encontró manifest.json"; \
        fi; \
    else \
        echo "⚠️  No se encontró package.json, omitiendo build de assets"; \
    fi

# Limpiar dependencias de Node.js después del build (opcional para reducir tamaño)
RUN if [ -f "package.json" ]; then \
        echo "🧼 Limpiando dependencias de desarrollo..." && \
        npm ci --production && \
        echo "✅ Dependencias de desarrollo eliminadas"; \
    fi

# Crear directorios necesarios y configurar permisos
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configurar PHP para producción
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Exponer puerto
EXPOSE 80

# Copiar script de inicio
COPY docker-start.sh /usr/local/bin/docker-start.sh
RUN chmod +x /usr/local/bin/docker-start.sh

# Comando de inicio
CMD ["/usr/local/bin/docker-start.sh"]
