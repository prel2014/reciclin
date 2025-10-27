#!/bin/bash

echo "======================================"
echo "Instalando Laravel 11.46.1 en Docker"
echo "======================================"
echo ""

# Verificar si ya existe una instalación de Laravel
if [ -f "artisan" ]; then
    echo "Ya existe una instalación de Laravel en este directorio."
    read -p "¿Deseas reinstalar? (s/n): " reinstall
    if [ "$reinstall" != "s" ]; then
        echo "Instalación cancelada."
        exit 0
    fi
    echo "Limpiando instalación anterior..."
    rm -rf vendor composer.lock
fi

echo "Instalando Laravel 11.46.1..."
composer create-project laravel/laravel:^11.46.1 temp

echo "Moviendo archivos..."
shopt -s dotglob
mv temp/* .
rm -rf temp

echo ""
echo "Configurando permisos..."
chown -R www-data:www-data /var/www/html
chmod -R 755 storage bootstrap/cache

echo ""
echo "Copiando archivo de entorno..."
if [ ! -f ".env" ]; then
    cp .env.example .env

    # Configurar variables de base de datos
    sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env
    sed -i 's/DB_DATABASE=laravel/DB_DATABASE=laravel/' .env
    sed -i 's/DB_USERNAME=root/DB_USERNAME=laravel/' .env
    sed -i 's/DB_PASSWORD=/DB_PASSWORD=laravel/' .env
fi

echo ""
echo "Generando clave de aplicación..."
php artisan key:generate

echo ""
echo "======================================"
echo "Instalación completada!"
echo "======================================"
echo ""
echo "Próximos pasos:"
echo "1. Ejecutar migraciones: docker-compose exec app php artisan migrate"
echo "2. Acceder a la aplicación: http://localhost:8000"
echo ""
