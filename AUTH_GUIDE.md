# Sistema de Autenticación - Reciclin.com

## Descripción
Sistema completo de autenticación implementado en Laravel 11 con las siguientes características:

- Login de usuarios
- Registro de nuevos usuarios
- Recuperación de contraseña
- Cierre de sesión
- Middleware de autenticación
- Middleware de administrador
- Distinción entre usuarios normales y administradores

## Rutas Disponibles

### Rutas Públicas
- `GET /login` - Formulario de inicio de sesión
- `POST /login` - Procesar login
- `GET /register` - Formulario de registro
- `POST /register` - Procesar registro
- `GET /forgot-password` - Formulario de recuperación de contraseña
- `POST /forgot-password` - Enviar enlace de recuperación

### Rutas Protegidas
- `POST /logout` - Cerrar sesión (requiere autenticación)

## Usuarios de Prueba

Después de ejecutar los seeders, tendrás estos usuarios disponibles:

### Administrador
- **Email:** admin@reciclin.com
- **Contraseña:** admin123
- **Nick:** admin
- **Tipo:** administrador

### Usuario Normal 1
- **Email:** usuario@reciclin.com
- **Contraseña:** usuario123
- **Nick:** reciclador1
- **Tipo:** usuario

### Usuario Normal 2
- **Email:** eco@reciclin.com
- **Contraseña:** eco123
- **Nick:** ecowarrior
- **Tipo:** usuario

## Instalación y Configuración

### 1. Ejecutar Migraciones
```bash
docker exec -it reciclin-app php artisan migrate:fresh
```

### 2. Ejecutar Seeders
```bash
docker exec -it reciclin-app php artisan db:seed
```

O ejecutar todo junto:
```bash
docker exec -it reciclin-app php artisan migrate:fresh --seed
```

## Middleware Disponibles

### auth.custom
Verifica que el usuario esté autenticado. Si no lo está, redirige a `/login`.

**Uso en rutas:**
```php
Route::get('/perfil', [PerfilController::class, 'index'])->middleware('auth.custom');
```

### admin
Verifica que el usuario esté autenticado Y sea administrador. Si no está autenticado, redirige a `/login`. Si está autenticado pero no es admin, redirige a `/` con un mensaje de error.

**Uso en rutas:**
```php
Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware('admin');
```

## Uso de Directivas Blade

### Verificar si hay usuario autenticado
```blade
@auth
    <p>Bienvenido, {{ Auth::user()->nick }}</p>
@endauth
```

### Mostrar contenido solo para invitados
```blade
@guest
    <a href="{{ url('/login') }}">Iniciar Sesión</a>
@endguest
```

### Verificar tipo de usuario
```blade
@auth
    @if(Auth::user()->tipo === 'administrador')
        <a href="/admin/dashboard">Panel de Admin</a>
    @endif
@endauth
```

## Modelo Usuario

El modelo `Usuario` está configurado para:
- Usar la tabla `usuario`
- Usar `cod_usuario` como primary key
- Usar `contrasena` en lugar de `password`
- Hashear automáticamente las contraseñas al crear/actualizar

### Crear usuario programáticamente
```php
use App\Models\Usuario;

$usuario = Usuario::create([
    'tipo' => 'usuario',
    'nick' => 'nuevo_usuario',
    'correo' => 'nuevo@ejemplo.com',
    'contrasena' => 'mi_contraseña', // Se hasheará automáticamente
    'nombre' => 'Nombre',
    'apellido' => 'Apellido',
    'telefono' => '123456789',
    'publicaciones' => 0,
    'estado' => 'activo',
]);
```

## Controlador de Autenticación

El `AuthController` incluye los siguientes métodos:

- `showLoginForm()` - Muestra formulario de login
- `login(Request $request)` - Procesa el login
- `showRegisterForm()` - Muestra formulario de registro
- `register(Request $request)` - Procesa el registro
- `logout(Request $request)` - Cierra la sesión
- `showForgotPasswordForm()` - Muestra formulario de recuperación
- `sendResetLinkEmail(Request $request)` - Envía enlace de recuperación (pendiente implementar envío real de email)

## Validaciones Implementadas

### Login
- Correo: requerido, formato email
- Contraseña: requerida, mínimo 6 caracteres

### Registro
- Nick: requerido, máximo 200 caracteres, único
- Correo: requerido, formato email, único
- Contraseña: requerida, mínimo 6 caracteres, confirmación
- Nombre: requerido, máximo 200 caracteres
- Apellido: requerido, máximo 250 caracteres
- Teléfono: opcional, máximo 200 caracteres

## Seguridad

- Las contraseñas se hashean automáticamente usando bcrypt
- Las sesiones se regeneran después del login para prevenir session fixation
- Las sesiones se invalidan completamente al hacer logout
- El campo `contrasena` está oculto en las respuestas JSON del modelo
- Verificación de estado de usuario (solo usuarios activos pueden iniciar sesión)

## Próximas Funcionalidades

- Implementar envío real de emails para recuperación de contraseña
- Verificación de email al registrarse
- Sistema de roles más complejo (si es necesario)
- Login con redes sociales (opcional)
- Autenticación de dos factores (opcional)
