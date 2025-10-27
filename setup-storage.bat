@echo off
echo ==========================================
echo   Configurando Storage de Reciclin.com
echo ==========================================
echo.

echo [1/3] Creando enlace simbolico de storage...
docker exec -it reciclin-app php artisan storage:link

echo.
echo [2/3] Estableciendo permisos de storage...
docker exec -it reciclin-app chmod -R 775 storage bootstrap/cache
docker exec -it reciclin-app chown -R www-data:www-data storage bootstrap/cache

echo.
echo [3/3] Verificando directorios...
docker exec -it reciclin-app ls -la public/storage

echo.
echo ==========================================
echo   Configuracion completada!
echo ==========================================
echo.
echo Las imagenes se almacenaran en:
echo   - storage/app/public/publicaciones/
echo.
echo Y seran accesibles via:
echo   - public/storage/publicaciones/
echo.
pause
