# Reciclin - Sistema de Gestión de Reciclaje Escolar con Recompensas

Sistema integral de gestión para incentivar el reciclaje en instituciones educativas mediante un sistema de puntos (Recipuntos) que los alumnos pueden canjear por útiles escolares.

## Descripción del Proyecto

**Reciclin** es una plataforma web desarrollada con Laravel 11 que permite a profesores gestionar el reciclaje de sus alumnos, asignar puntos (Recipuntos) por materiales reciclados y por rendimiento académico, mientras que los alumnos pueden canjear sus puntos acumulados por útiles escolares.

### Características Principales

- **Sistema de Recipuntos**: Puntos otorgados por reciclaje y desempeño académico
- **Gestión de Usuarios**: Roles de Administrador, Profesor y Alumno
- **Registro de Reciclaje**: Control de materiales reciclados con cálculo automático de recipuntos
- **Registro de Exámenes**: Asignación de recipuntos según notas académicas (1 punto por pregunta correcta)
- **Marketplace de Útiles**: Catálogo de útiles escolares para canjear con recipuntos
- **API REST Completa**: Endpoints documentados con autenticación Sanctum
- **Interfaz Web y API**: Uso simultáneo o independiente según necesidades
- **Contenedorización**: Implementación con Docker para fácil despliegue

## Tecnologías Utilizadas

- **Backend**: Laravel 11.x (PHP 8.2+)
- **Base de Datos**: MySQL 8.0
- **Autenticación**: Laravel Sanctum (API) + Sessions (Web)
- **Contenedores**: Docker & Docker Compose
- **Servidor Web**: Nginx
- **Gestión de DB**: phpMyAdmin

## Requisitos Previos

- Docker Desktop instalado y en ejecución
- Git (opcional, para clonar el repositorio)
- Puerto 80 (web), 8000 (API), 8080 (phpMyAdmin) disponibles

## Instalación Rápida

### 1. Iniciar Contenedores Docker

```bash
docker-compose up -d
```

Esto iniciará automáticamente:
- PHP 8.2-FPM
- Nginx
- MySQL 8.0
- phpMyAdmin

### 2. Instalar Dependencias

```bash
docker exec -it reciclin-app composer install
```

### 3. Configurar Aplicación

```bash
# Generar key de aplicación
docker exec -it reciclin-app php artisan key:generate

# Ejecutar migraciones y seeders (datos de prueba)
docker exec -it reciclin-app php artisan migrate:fresh --seed

# Configurar almacenamiento de archivos
docker exec -it reciclin-app php artisan storage:link
```

### 4. Acceder a la Aplicación

- **Interfaz Web**: http://localhost
- **Marketplace**: http://localhost/marketplace
- **Panel Admin**: http://localhost/admin/dashboard
- **API Base**: http://localhost:8000/api
- **phpMyAdmin**: http://localhost:8080

## Usuarios de Prueba

### Administrador
```
Email: admin@recipuntos.com
Contraseña: admin123
```

### Profesores
```
Email: admin2@recipuntos.com | Contraseña: inicial3abc
Email: admin3@recipuntos.com | Contraseña: inicial4abc
Email: admin5@recipuntos.com | Contraseña: primaria3abc
```

### Alumnos
Múltiples alumnos de prueba disponibles tras ejecutar seeders

## Uso del Sistema

### Para Profesores

1. **Registrar Alumnos**
   - Vía web: Panel de gestión de alumnos
   - Vía API: `POST /api/profesores/alumnos`

2. **Asignar Recipuntos por Reciclaje**
   - Registrar material reciclado (botellas, papel, cartón, etc.)
   - El sistema calcula automáticamente los recipuntos según cantidad
   - Vía API: `POST /api/profesores/alumnos/{id}/asignar-recipuntos`

3. **Registrar Exámenes**
   - Registrar tipo de examen (matemática, comunicación, general)
   - Ingresar preguntas correctas (sobre 20)
   - Sistema asigna 1 recipunto por pregunta correcta
   - Vía API: `POST /api/profesores/alumnos/{id}/registrar-examen`

### Para Alumnos

1. **Acumular Recipuntos**
   - Reciclando materiales (registrado por profesor)
   - Rindiendo bien en exámenes

2. **Canjear Recipuntos**
   - Acceder al marketplace de útiles
   - Seleccionar útiles escolares
   - Canjear con recipuntos acumulados

### Para Administradores

- Gestión completa de usuarios (CRUD)
- Dashboard con estadísticas en tiempo real
- Gestión de materiales reciclables
- Gestión de útiles escolares
- Vista de todas las transacciones

## API REST

El sistema incluye una API REST completa con autenticación mediante tokens Bearer (Laravel Sanctum).

### Endpoints Principales

#### Autenticación
- `POST /api/auth/register` - Registrar usuario
- `POST /api/auth/login` - Iniciar sesión
- `GET /api/auth/me` - Obtener usuario actual
- `POST /api/auth/logout` - Cerrar sesión

#### Gestión de Alumnos (Profesores)
- `POST /api/profesores/alumnos` - Registrar alumno
- `POST /api/profesores/alumnos/{id}/asignar-recipuntos` - Asignar recipuntos por reciclaje
- `POST /api/profesores/alumnos/{id}/registrar-examen` - Registrar examen y asignar recipuntos

### Ejemplo de Uso con cURL

```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"correo":"admin@recipuntos.com","contrasena":"admin123"}'

# Asignar recipuntos (con token)
curl -X POST http://localhost:8000/api/profesores/alumnos/16/asignar-recipuntos \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {tu_token}" \
  -d '{"cod_material":1,"cantidad":5.0,"descripcion":"Botellas PET"}'
```

Para documentación completa de la API, consulta [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

## Estructura del Proyecto

```
reciclin/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controladores de la aplicación
│   │   │   ├── AuthController.php
│   │   │   ├── Api/          # Controladores de API
│   │   │   └── Admin/        # Controladores de admin
│   │   └── Middleware/       # Middleware personalizado
│   └── Models/               # Modelos Eloquent
├── database/
│   ├── migrations/           # Migraciones de base de datos
│   └── seeders/              # Datos de prueba
├── resources/
│   └── views/                # Vistas Blade
│       ├── auth/             # Login y registro
│       ├── admin/            # Panel administrativo
│       └── marketplace/      # Tienda de útiles
├── routes/
│   ├── web.php               # Rutas web
│   └── api.php               # Rutas API
├── public/                   # Archivos públicos
│   ├── css/                  # Estilos
│   └── uploads/              # Archivos subidos
├── docker-compose.yml        # Configuración Docker
├── Dockerfile                # Imagen Docker
└── .env                      # Variables de entorno
```

## Comandos Útiles

### Gestión de Base de Datos
```bash
# Reiniciar base de datos con datos de prueba
docker exec -it reciclin-app php artisan migrate:fresh --seed

# Ejecutar seeder específico
docker exec -it reciclin-app php artisan db:seed --class=UsuarioSeeder
```

### Limpiar Caché
```bash
docker exec -it reciclin-app php artisan cache:clear
docker exec -it reciclin-app php artisan config:clear
docker exec -it reciclin-app php artisan route:clear
```

### Gestión de Contenedores
```bash
# Ver contenedores activos
docker ps

# Reiniciar contenedores
docker-compose restart

# Detener contenedores
docker-compose down

# Ver logs
docker-compose logs -f app
```

## Documentación Adicional

- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Documentación completa de la API
- [SETUP_COMPLETO.md](SETUP_COMPLETO.md) - Guía de instalación detallada
- [AUTH_GUIDE.md](AUTH_GUIDE.md) - Sistema de autenticación
- [MARKETPLACE_GUIDE.md](MARKETPLACE_GUIDE.md) - Marketplace de útiles
- [IMAGE_UPLOAD_GUIDE.md](IMAGE_UPLOAD_GUIDE.md) - Subida de imágenes

## Casos de Uso

### Escuela con Programa de Reciclaje
Una escuela implementa Reciclin para motivar a los alumnos a reciclar. Los profesores registran el material reciclado, los alumnos acumulan recipuntos y los canjean por útiles escolares.

### Colegio con Incentivos Académicos
Un colegio usa el sistema para recompensar tanto el reciclaje como el rendimiento académico, registrando exámenes y asignando recipuntos automáticamente.

### Institución con App Móvil
Una institución desarrolla una app móvil que consume la API REST para que alumnos consulten sus recipuntos y profesores registren reciclajes desde sus dispositivos móviles.

## Solución de Problemas

### Error: Puerto en uso
Si algún puerto está ocupado, modifica `docker-compose.yml`:
```yaml
ports:
  - "8001:80"  # Cambiar puerto web
```

### Error de conexión a base de datos
Verifica que el contenedor de MySQL esté corriendo:
```bash
docker-compose up -d db
```

### Permisos de archivos
```bash
docker exec -it reciclin-app chmod -R 777 storage bootstrap/cache
```

## Seguridad

- Contraseñas hasheadas con bcrypt
- Protección CSRF en formularios
- Autenticación de API con tokens Sanctum
- Validación de datos en backend
- Middleware de autorización por roles

## Contribución

Para contribuir al proyecto:
1. Realiza un fork del repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## Licencia

Este proyecto es software de código abierto licenciado bajo [MIT license](https://opensource.org/licenses/MIT).

---

**Versión**: 1.2.0
**Framework**: Laravel 11.x
**PHP**: 8.2+
**Base de Datos**: MySQL 8.0
**Última actualización**: Octubre 2025
