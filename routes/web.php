<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUsuarioController;
use App\Http\Controllers\AdminCategoriaController;
use App\Http\Controllers\AdminProductoController;
use App\Http\Controllers\AdminMaterialController;
use App\Http\Controllers\ProfesorDashboardController;
use App\Http\Controllers\AlumnoDashboardController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\UserPublicacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CategoriaHijoController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\DistritoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\PublicidadController;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\PagoController;
use App\Models\Usuario;
use App\Models\Compra;
use App\Models\Publicacion;
use App\Models\MultimediaHomepage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Obtener top 10 usuarios por publicaciones
    $ranking = Usuario::orderBy('publicaciones', 'desc')
        ->take(10)
        ->get();

    // Métricas (datos de ejemplo, puedes calcularlos desde la BD)
    $metricas = [
        'hoy' => '45 BTL',
        'semana' => '680 BTL',
        'total' => '15 850 KGS',
        'usuarios' => number_format(Usuario::count())
    ];

    // Actividades recientes (últimas compras)
    $actividades = Compra::with('usuario', 'publicacion')
        ->orderBy('fecha', 'desc')
        ->take(6)
        ->get()
        ->map(function($compra) {
            return (object)[
                'tipo' => 'reciclaje',
                'tipo_nombre' => 'Compra',
                'usuario' => $compra->usuario,
                'descripcion' => 'realizó una compra',
                'fecha' => $compra->fecha
            ];
        });

    // Obtener contenido multimedia para la página principal
    $banners = MultimediaHomepage::activos()->seccion('banner')->ordenado()->get();
    $galeria = MultimediaHomepage::activos()->seccion('galeria')->ordenado()->take(6)->get();
    $destacados = MultimediaHomepage::activos()->seccion('destacado')->ordenado()->take(3)->get();

    return view('index', compact('ranking', 'metricas', 'actividades', 'banners', 'galeria', 'destacados'));
});

// ============================================================
// Rutas de Autenticación
// ============================================================

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Registro
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Recuperación de contraseña
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// Marketplace (Público)
// ============================================================

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/{id}', [MarketplaceController::class, 'show'])->name('marketplace.show');
Route::post('/marketplace/{id}/comprar', [MarketplaceController::class, 'comprar'])->name('marketplace.comprar');

// ============================================================
// Panel de Usuario (Protegido - requiere autenticación)
// ============================================================

Route::middleware(['auth.custom'])->prefix('user')->group(function () {
    // Gestión de Publicaciones
    Route::resource('publicaciones', UserPublicacionController::class)->names([
        'index' => 'user.publicaciones.index',
        'create' => 'user.publicaciones.create',
        'store' => 'user.publicaciones.store',
        'edit' => 'user.publicaciones.edit',
        'update' => 'user.publicaciones.update',
        'destroy' => 'user.publicaciones.destroy',
    ])->except(['show']);
});

// ============================================================
// Panel de Administración (Protegido con middleware admin)
// ============================================================

Route::middleware(['admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Gestión de Usuarios
    Route::resource('usuarios', AdminUsuarioController::class)->names([
        'index' => 'admin.usuarios.index',
        'create' => 'admin.usuarios.create',
        'store' => 'admin.usuarios.store',
        'show' => 'admin.usuarios.show',
        'edit' => 'admin.usuarios.edit',
        'update' => 'admin.usuarios.update',
        'destroy' => 'admin.usuarios.destroy',
    ]);
    Route::post('/usuarios/{id}/toggle-status', [AdminUsuarioController::class, 'toggleStatus'])->name('admin.usuarios.toggle-status');

    // Gestión de Categorías
    Route::resource('categorias', AdminCategoriaController::class)->names([
        'index' => 'admin.categorias.index',
        'store' => 'admin.categorias.store',
        'update' => 'admin.categorias.update',
        'destroy' => 'admin.categorias.destroy',
    ])->only(['index', 'store', 'update', 'destroy']);

    // Gestión de Productos (Útiles Escolares)
    Route::resource('productos', AdminProductoController::class)->names([
        'index' => 'admin.productos.index',
        'store' => 'admin.productos.store',
        'update' => 'admin.productos.update',
        'destroy' => 'admin.productos.destroy',
    ])->only(['index', 'store', 'update', 'destroy']);
    Route::post('/productos/{id}/toggle-status', [AdminProductoController::class, 'toggleStatus'])->name('admin.productos.toggle-status');

    // Gestión de Materiales Reciclables
    Route::resource('materiales', AdminMaterialController::class)->names([
        'index' => 'admin.materiales.index',
        'store' => 'admin.materiales.store',
        'update' => 'admin.materiales.update',
        'destroy' => 'admin.materiales.destroy',
    ])->only(['index', 'store', 'update', 'destroy']);
    Route::patch('/materiales/{id}/toggle-status', [AdminMaterialController::class, 'toggleStatus'])->name('admin.materiales.toggleStatus');

    // Gestión de Multimedia (Imágenes y Videos para la página principal)
    Route::resource('multimedia', \App\Http\Controllers\AdminMultimediaController::class)->names([
        'index' => 'admin.multimedia.index',
        'create' => 'admin.multimedia.create',
        'store' => 'admin.multimedia.store',
        'edit' => 'admin.multimedia.edit',
        'update' => 'admin.multimedia.update',
        'destroy' => 'admin.multimedia.destroy',
    ]);
    Route::post('/multimedia/{id}/toggle-estado', [\App\Http\Controllers\AdminMultimediaController::class, 'toggleEstado'])->name('admin.multimedia.toggle-estado');

    // Gestión de Compras
    Route::get('/compras', function () {
        return view('admin.placeholder')->with('title', 'Compras')->with('message', 'Gestión de compras próximamente');
    })->name('admin.compras.index');

    // Gestión de Pagos
    Route::get('/pagos', function () {
        return view('admin.placeholder')->with('title', 'Pagos')->with('message', 'Gestión de pagos próximamente');
    })->name('admin.pagos.index');

    // Gestión de Distritos
    Route::get('/distritos', function () {
        return view('admin.placeholder')->with('title', 'Distritos')->with('message', 'Gestión de distritos próximamente');
    })->name('admin.distritos.index');

    // Gestión de Publicidad
    Route::get('/publicidad', function () {
        return view('admin.placeholder')->with('title', 'Publicidad')->with('message', 'Gestión de publicidad próximamente');
    })->name('admin.publicidad.index');

    // Gestión de Imágenes
    Route::get('/imagenes', function () {
        return view('admin.placeholder')->with('title', 'Imágenes')->with('message', 'Gestión de imágenes próximamente');
    })->name('admin.imagenes.index');

    // Configuración
    Route::get('/configuracion', function () {
        return view('admin.placeholder')->with('title', 'Configuración')->with('message', 'Configuración del sistema próximamente');
    })->name('admin.configuracion.index');
});

// ============================================================
// Panel de Profesores (Protegido con middleware profesor)
// ============================================================

Route::middleware(['auth.custom'])->prefix('profesor')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ProfesorDashboardController::class, 'index'])->name('profesor.dashboard');

    // Gestión de Canjes
    Route::resource('canjes', \App\Http\Controllers\ProfesorCanjeController::class)->names([
        'index' => 'profesor.canjes.index',
        'create' => 'profesor.canjes.create',
        'store' => 'profesor.canjes.store',
        'show' => 'profesor.canjes.show',
        'destroy' => 'profesor.canjes.destroy',
    ])->only(['index', 'create', 'store', 'show', 'destroy']);

    // Actualizar estado de canje
    Route::post('/canjes/{id}/update-estado', [\App\Http\Controllers\ProfesorCanjeController::class, 'updateEstado'])->name('profesor.canjes.update-estado');

    // API para autocompletado de canjes
    Route::get('/api/buscar-alumnos', [\App\Http\Controllers\ProfesorCanjeController::class, 'buscarAlumnos'])->name('profesor.api.buscar-alumnos');
    Route::get('/api/buscar-productos', [\App\Http\Controllers\ProfesorCanjeController::class, 'buscarProductos'])->name('profesor.api.buscar-productos');

    // Catálogo de Productos (solo lectura)
    Route::get('/productos', [\App\Http\Controllers\ProfesorProductoController::class, 'index'])->name('profesor.productos.index');
    Route::get('/productos/{id}', [\App\Http\Controllers\ProfesorProductoController::class, 'show'])->name('profesor.productos.show');

    // Gestión de Alumnos
    Route::get('/alumnos', [\App\Http\Controllers\ProfesorAlumnoController::class, 'index'])->name('profesor.alumnos.index');
    Route::get('/alumnos/crear', [\App\Http\Controllers\ProfesorAlumnoController::class, 'create'])->name('profesor.alumnos.create');
    Route::post('/alumnos', [\App\Http\Controllers\ProfesorAlumnoController::class, 'store'])->name('profesor.alumnos.store');
    Route::get('/alumnos/{id}', [\App\Http\Controllers\ProfesorAlumnoController::class, 'show'])->name('profesor.alumnos.show');

    // Asignar Recipuntos por Reciclaje
    Route::get('/alumnos/{id}/asignar-recipuntos', [\App\Http\Controllers\ProfesorAlumnoController::class, 'asignarRecipuntosForm'])->name('profesor.alumnos.asignar-recipuntos');
    Route::post('/alumnos/{id}/asignar-recipuntos', [\App\Http\Controllers\ProfesorAlumnoController::class, 'asignarRecipuntos'])->name('profesor.alumnos.asignar-recipuntos.store');

    // Asignar Recipuntos por Examen
    Route::get('/alumnos/{id}/registrar-examen', [\App\Http\Controllers\ProfesorAlumnoController::class, 'registrarExamenForm'])->name('profesor.alumnos.registrar-examen');
    Route::post('/alumnos/{id}/registrar-examen', [\App\Http\Controllers\ProfesorAlumnoController::class, 'registrarExamen'])->name('profesor.alumnos.registrar-examen.store');
});

// ============================================================
// Panel de Alumnos (Protegido con middleware alumno)
// ============================================================

Route::middleware(['auth.custom'])->prefix('alumno')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AlumnoDashboardController::class, 'index'])->name('alumno.dashboard');

    // Ver Productos Disponibles
    Route::get('/productos', [\App\Http\Controllers\AlumnoProductoController::class, 'index'])->name('alumno.productos.index');
    Route::get('/productos/{id}', [\App\Http\Controllers\AlumnoProductoController::class, 'show'])->name('alumno.productos.show');
    Route::post('/productos/{id}/canjear', [\App\Http\Controllers\AlumnoProductoController::class, 'solicitarCanje'])->name('alumno.productos.canjear');

    // Historial de Canjes
    Route::get('/canjes', [\App\Http\Controllers\AlumnoCanjeController::class, 'index'])->name('alumno.canjes.index');

    // Ranking
    Route::get('/ranking', [\App\Http\Controllers\AlumnoRankingController::class, 'index'])->name('alumno.ranking.index');

    // Historial de Recipuntos
    Route::get('/historial-puntos', [\App\Http\Controllers\AlumnoRecipuntoController::class, 'historial'])->name('alumno.historial-puntos.index');
});

// ============================================================
// Rutas de Recursos (CRUD completo)
// ============================================================

Route::resource('usuarios', UsuarioController::class);
Route::resource('categorias', CategoriaController::class);
Route::resource('categorias-hijos', CategoriaHijoController::class);
Route::resource('imagenes', ImagenController::class);
Route::resource('distritos', DistritoController::class);
Route::resource('configuracion', ConfiguracionController::class);
Route::resource('publicidad', PublicidadController::class);
Route::resource('publicaciones', PublicacionController::class);
Route::resource('compras', CompraController::class);
Route::resource('pagos', PagoController::class);

// ============================================================
// Rutas API personalizadas
// ============================================================

Route::prefix('api')->group(function () {

    // Usuarios
    Route::get('usuarios', [UsuarioController::class, 'index']);
    Route::get('usuarios/{id}/compras', [UsuarioController::class, 'obtenerCompras']);

    // Categorías
    Route::get('categorias/{id}/hijos', [CategoriaController::class, 'obtenerHijos']);

    // Publicaciones
    Route::get('publicaciones/categoria/{id}', [PublicacionController::class, 'porCategoria']);
    Route::get('publicaciones/usuario/{id}', [PublicacionController::class, 'porUsuario']);

    // Distritos
    Route::get('distritos/provincia/{provincia}', [DistritoController::class, 'porProvincia']);
});
