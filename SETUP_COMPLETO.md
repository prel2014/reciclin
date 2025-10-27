# Guía Completa de Instalación y Uso - Reciclin.com

## 📋 Sistema Completo Implementado

### ✅ Módulos Funcionales

1. **Sistema de Autenticación**
   - Login y Registro
   - Recuperación de contraseña
   - Middleware de autenticación
   - Roles: Usuario y Administrador

2. **Panel de Administración**
   - Dashboard con estadísticas en tiempo real
   - Gestión completa de usuarios (CRUD)
   - Vista de compras, pagos, publicaciones
   - Filtros y búsqueda avanzada

3. **Marketplace Público**
   - Listado de publicaciones con filtros
   - Detalle de producto con galería
   - Sistema de compras
   - Categorización de productos

4. **Base de Datos Completa**
   - 10 tablas relacionadas
   - Migraciones completas
   - Seeders con datos de prueba

---

## 🚀 Instalación Paso a Paso

### Paso 1: Iniciar Docker

Asegúrate de tener Docker Desktop corriendo, luego ejecuta:

```bash
docker-compose up -d
```

Esto iniciará:
- ✓ PHP 8.2.29-FPM
- ✓ Nginx
- ✓ MySQL 8.0
- ✓ phpMyAdmin

### Paso 2: Verificar Contenedores

```bash
docker ps
```

Deberías ver 4 contenedores corriendo:
- `reciclin-app` (PHP)
- `reciclin-nginx` (Servidor Web)
- `reciclin-db` (MySQL)
- `reciclin-phpmyadmin`

### Paso 3: Instalar Dependencias de Laravel

```bash
docker exec -it reciclin-app composer install
```

### Paso 4: Configurar Variables de Entorno

Verifica que tu archivo `.env` tenga:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

### Paso 5: Generar Key de Aplicación

```bash
docker exec -it reciclin-app php artisan key:generate
```

### Paso 6: Ejecutar Migraciones y Seeders

**Opción A: Todo en un comando (RECOMENDADO)**
```bash
docker exec -it reciclin-app php artisan migrate:fresh --seed
```

**Opción B: Por separado**
```bash
# Crear tablas
docker exec -it reciclin-app php artisan migrate:fresh

# Poblar datos
docker exec -it reciclin-app php artisan db:seed
```

### Paso 7: Acceder a la Aplicación

Abre tu navegador en:
- **Sitio Principal:** http://localhost
- **Marketplace:** http://localhost/marketplace
- **Login:** http://localhost/login
- **Panel Admin:** http://localhost/admin/dashboard
- **phpMyAdmin:** http://localhost:8080

---

## 👥 Usuarios de Prueba

### Administrador
- **Email:** admin@reciclin.com
- **Contraseña:** admin123
- **Tipo:** Administrador
- **Acceso:** Panel completo de administración

### Usuario Normal 1
- **Email:** usuario@reciclin.com
- **Contraseña:** usuario123
- **Tipo:** Usuario
- **Acceso:** Marketplace y compras

### Usuario Normal 2
- **Email:** eco@reciclin.com
- **Contraseña:** eco123
- **Tipo:** Usuario
- **Acceso:** Marketplace y compras

---

## 📊 Datos de Prueba Incluidos

### 3 Usuarios
- 1 Administrador
- 2 Usuarios normales

### 6 Categorías
1. Plástico
2. Papel y Cartón
3. Vidrio
4. Metal
5. Electrónicos
6. Textiles

### 12 Publicaciones
- Botellas PET, cartón, latas de aluminio
- Papel de oficina, vidrio, plástico HDPE
- Periódicos, cables de cobre, envases
- Chatarra, textiles, componentes electrónicos
- Cada una con descripción, precio, stock y ubicación

---

## 🗺️ Mapa de Rutas

### Rutas Públicas
```
GET  /                          → Página principal
GET  /marketplace               → Listado de publicaciones
GET  /marketplace/{id}          → Detalle de publicación
GET  /login                     → Formulario de login
GET  /register                  → Formulario de registro
POST /login                     → Procesar login
POST /register                  → Procesar registro
POST /logout                    → Cerrar sesión
```

### Rutas Protegidas (Usuario Autenticado)
```
POST /marketplace/{id}/comprar  → Realizar compra
```

### Rutas de Administrador
```
GET  /admin/dashboard           → Dashboard con estadísticas
GET  /admin/usuarios            → Gestión de usuarios
GET  /admin/usuarios/create     → Crear usuario
POST /admin/usuarios            → Guardar usuario
GET  /admin/usuarios/{id}       → Ver usuario
GET  /admin/usuarios/{id}/edit  → Editar usuario
PUT  /admin/usuarios/{id}       → Actualizar usuario
DELETE /admin/usuarios/{id}     → Eliminar usuario
```

---

## 📁 Estructura del Proyecto

```
reciclin/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── AdminUsuarioController.php
│   │   │   ├── MarketplaceController.php
│   │   │   └── ... (otros 7 controllers)
│   │   └── Middleware/
│   │       ├── EnsureUserIsAuthenticated.php
│   │       └── EnsureUserIsAdmin.php
│   └── Models/
│       ├── Usuario.php
│       ├── Publicacion.php
│       ├── Categoria.php
│       ├── Compra.php
│       └── ... (otros 6 modelos)
├── database/
│   ├── migrations/
│   │   └── (10 migraciones)
│   └── seeders/
│       ├── UsuarioSeeder.php
│       ├── CategoriaSeeder.php
│       └── PublicacionSeeder.php
├── resources/
│   └── views/
│       ├── auth/
│       │   ├── layout.blade.php
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── admin/
│       │   ├── layout.blade.php
│       │   ├── dashboard.blade.php
│       │   └── usuarios/ (index, create, edit, show)
│       ├── marketplace/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       └── index.blade.php
├── routes/
│   └── web.php
├── public/
│   └── css/
│       ├── reciclin.css
│       └── reciclin-sections.css
├── docker-compose.yml
├── Dockerfile
├── AUTH_GUIDE.md
├── MARKETPLACE_GUIDE.md
└── SETUP_COMPLETO.md (este archivo)
```

---

## 🎯 Flujos de Uso Completos

### Flujo 1: Usuario Nuevo se Registra y Compra

1. Visita `http://localhost/register`
2. Completa el formulario de registro
3. Es redirigido automáticamente al inicio (ya autenticado)
4. Click en "MARKETPLACE" en el navbar
5. Explora las publicaciones
6. Click en "Ver Detalles" de alguna publicación
7. Selecciona cantidad y click en "Comprar Ahora"
8. Ve mensaje de confirmación

### Flujo 2: Administrador Gestiona Usuarios

1. Visita `http://localhost/login`
2. Inicia sesión con `admin@reciclin.com` / `admin123`
3. Es redirigido a `/admin/dashboard`
4. Ve estadísticas en tiempo real:
   - Total de usuarios
   - Total de publicaciones
   - Compras recientes
   - Top usuarios
   - Ingresos mensuales
5. Click en "Usuarios" en el sidebar
6. Ve lista completa con filtros
7. Puede crear, editar, ver detalles o eliminar usuarios

### Flujo 3: Ver Marketplace sin Cuenta

1. Visita `http://localhost/marketplace`
2. Explora libremente las publicaciones
3. Usa filtros de búsqueda y categorías
4. Click en una publicación para ver detalles
5. Al intentar comprar, es redirigido a login

---

## 🔧 Comandos Útiles

### Ver logs de Laravel
```bash
docker exec -it reciclin-app tail -f storage/logs/laravel.log
```

### Limpiar caché
```bash
docker exec -it reciclin-app php artisan cache:clear
docker exec -it reciclin-app php artisan config:clear
docker exec -it reciclin-app php artisan route:clear
docker exec -it reciclin-app php artisan view:clear
```

### Ejecutar un seeder específico
```bash
docker exec -it reciclin-app php artisan db:seed --class=UsuarioSeeder
docker exec -it reciclin-app php artisan db:seed --class=CategoriaSeeder
docker exec -it reciclin-app php artisan db:seed --class=PublicacionSeeder
```

### Crear nueva migración
```bash
docker exec -it reciclin-app php artisan make:migration nombre_migracion
```

### Crear nuevo controller
```bash
docker exec -it reciclin-app php artisan make:controller NombreController
```

### Crear nuevo model
```bash
docker exec -it reciclin-app php artisan make:model NombreModelo
```

### Acceder a MySQL
```bash
docker exec -it reciclin-db mysql -u laravel -p
# Contraseña: laravel
```

### Reiniciar contenedores
```bash
docker-compose restart
```

### Detener todos los contenedores
```bash
docker-compose down
```

### Ver contenedores corriendo
```bash
docker ps
```

---

## 🐛 Solución de Problemas

### Error: "Port 3306 already in use"
El puerto MySQL está ocupado. El proyecto ya está configurado para usar el puerto 3307.
```bash
# Verificar en docker-compose.yml
ports:
  - "3307:3306"
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"
El contenedor de MySQL no está corriendo.
```bash
docker-compose up -d db
```

### Error: "Class 'App\Models\Usuario' not found"
Las clases no están cargadas.
```bash
docker exec -it reciclin-app composer dump-autoload
```

### Error: "Target class [AuthController] does not exist"
Verifica el namespace en `routes/web.php`:
```php
use App\Http\Controllers\AuthController;
```

### Error 403 al acceder a http://localhost
Verifica permisos de la carpeta storage:
```bash
docker exec -it reciclin-app chmod -R 777 storage bootstrap/cache
```

### No aparecen estilos CSS
Verifica que los archivos CSS existan en `public/css/`

### Error: "The page has expired" al hacer submit
Token CSRF expirado. Refresca la página.

---

## 📝 Notas Importantes

### Seguridad
- ✓ Contraseñas hasheadas con bcrypt
- ✓ Protección CSRF en formularios
- ✓ Middleware de autenticación
- ✓ Validación de datos en backend
- ✓ Sanitización de entradas

### Características Responsive
- ✓ Navbar adaptable a móvil
- ✓ Grid de productos responsive
- ✓ Formularios adaptables
- ✓ Sidebar colapsable en admin

### Base de Datos
- ✓ Foreign keys con cascade
- ✓ Índices en campos comunes
- ✓ Timestamps automáticos
- ✓ Soft deletes disponible (no implementado)

---

## 🎨 Personalización

### Cambiar Colores del Tema
Edita las variables en los archivos CSS:
```css
/* Gradiente principal */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Cambiar a otro gradiente */
background: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);
```

### Agregar Logo Personalizado
Reemplaza la URL en `index.blade.php` y otras vistas:
```html
<img src="https://tu-dominio.com/tu-logo.png" alt="Logo">
```

### Cambiar Paginación
En `MarketplaceController.php`:
```php
// Cambiar de 12 a otro número
$publicaciones = $query->paginate(12);
```

---

## 📚 Documentación de Referencia

- [Laravel 11 Docs](https://laravel.com/docs/11.x)
- [Blade Templates](https://laravel.com/docs/11.x/blade)
- [Eloquent ORM](https://laravel.com/docs/11.x/eloquent)
- [Docker Docs](https://docs.docker.com/)

---

## ✅ Checklist Post-Instalación

- [ ] Docker contenedores corriendo
- [ ] Base de datos creada
- [ ] Migraciones ejecutadas
- [ ] Seeders ejecutados
- [ ] Acceso a http://localhost funciona
- [ ] Login funciona con usuarios de prueba
- [ ] Panel admin accesible
- [ ] Marketplace muestra publicaciones
- [ ] Sistema de compras funciona

---

## 🚀 Próximos Pasos Recomendados

1. **Implementar Sistema de Imágenes**
   - Upload de archivos
   - Almacenamiento en storage/
   - Redimensionamiento automático

2. **Panel de Usuario Normal**
   - Mis publicaciones
   - Mis compras
   - Perfil editable

3. **Sistema de Mensajería**
   - Chat entre comprador/vendedor
   - Notificaciones en tiempo real

4. **Pasarela de Pago**
   - Integración con Mercado Pago/PayPal
   - Procesamiento de pagos reales

5. **API RESTful**
   - Endpoints para app móvil
   - Autenticación con tokens

---

## 📞 Contacto y Soporte

Para reportar bugs o solicitar features, consulta la documentación en:
- `AUTH_GUIDE.md` - Sistema de autenticación
- `MARKETPLACE_GUIDE.md` - Sistema de marketplace

---

**Versión:** 1.0
**Última actualización:** 2025-10-20
**Framework:** Laravel 11.x
**PHP:** 8.2.29
**Base de Datos:** MySQL 8.0
