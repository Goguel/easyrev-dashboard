# Usa uma imagem base do PHP com as ferramentas de linha de comando
FROM php:8.3-cli

# Instala dependências e extensões do PHP
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xml

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/backend

# Copia os arquivos do projeto e instala as dependências
COPY . .
RUN composer install --optimize-autoloader

# Garante que o .env exista e gera a chave da aplicação
RUN php -r "file_exists('.env') || copy('.env.example', '.env');"
RUN php artisan key:generate

# Expõe a porta 8000
EXPOSE 8000

# Comando para iniciar o servidor de desenvolvimento do Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]