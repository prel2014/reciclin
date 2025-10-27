@echo off
echo ======================================
echo Instalando Laravel 11.46.1 en Docker
echo ======================================
echo.

REM Construir y levantar los contenedores
echo Construyendo contenedores Docker...
docker-compose up -d --build

REM Esperar a que los contenedores estén listos
echo Esperando a que los contenedores esten listos...
timeout /t 5 /nobreak > nul

REM Ejecutar el script de instalación dentro del contenedor
echo Instalando Laravel...
docker-compose exec app bash /var/www/html/setup.sh

echo.
echo ======================================
echo Instalacion completada!
echo ======================================
echo.
echo La aplicacion esta disponible en: http://localhost:8000
echo phpMyAdmin esta disponible en: http://localhost:8080
echo.
pause
