<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Publicacion;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Mostrar dashboard de administrador con estadísticas del sistema Recipuntos
     */
    public function index()
    {
        // Estadísticas de usuarios por tipo
        $totalUsuarios = Usuario::count();
        $totalProfesores = Usuario::where('tipo', 'profesor')->count();
        $totalAlumnos = Usuario::where('tipo', 'alumno')->count();
        $totalAdmins = Usuario::where('tipo', 'admin')->count();

        // Estadísticas de Recipuntos
        $totalRecipuntosCirculacion = Usuario::sum('recipuntos');
        $totalRecipuntosCanjeados = Compra::sum('precio_t'); // Recipuntos gastados en canjes

        // Estadísticas de productos (útiles escolares)
        $totalProductos = Publicacion::where('status', 'activo')->count();
        $totalCategorias = 0; // Sin categorías en el sistema simplificado

        // Estadísticas de canjes
        $totalCanjes = Compra::count();
        $canjesMes = Compra::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Canjes recientes
        $canjesRecientes = Compra::with(['usuario', 'publicacion'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Top alumnos con más Recipuntos
        $topAlumnos = Usuario::where('tipo', 'alumno')
            ->orderBy('recipuntos', 'desc')
            ->take(5)
            ->get();

        // Productos más canjeados
        $productosMasCanjeados = Compra::select('cod_publicacion', DB::raw('COUNT(*) as total_canjes'))
            ->with('publicacion')
            ->groupBy('cod_publicacion')
            ->orderBy('total_canjes', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsuarios',
            'totalProfesores',
            'totalAlumnos',
            'totalAdmins',
            'totalRecipuntosCirculacion',
            'totalRecipuntosCanjeados',
            'totalProductos',
            'totalCategorias',
            'totalCanjes',
            'canjesMes',
            'canjesRecientes',
            'topAlumnos',
            'productosMasCanjeados'
        ));
    }
}
