<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Publicacion;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfesorDashboardController extends Controller
{
    /**
     * Dashboard para profesores
     */
    public function index()
    {
        $profesor = Auth::user();

        // Mis alumnos - Optimizado con select específico
        $misAlumnos = Usuario::select('cod_usuario', 'nombre', 'apellido', 'nick', 'recipuntos', 'correo')
            ->where('cod_profesor', $profesor->cod_usuario)
            ->where('tipo', 'alumno')
            ->orderBy('recipuntos', 'desc')
            ->get();

        // Total de alumnos (usar count sin cargar datos)
        $totalAlumnos = $misAlumnos->count();

        // Total Recipuntos asignados a mis alumnos (usar sum sin cargar datos)
        $totalRecipuntos = $misAlumnos->sum('recipuntos');

        // Productos disponibles para canje - Optimizado con select específico
        $productosDisponibles = Publicacion::select('cod_util', 'nombre', 'descripcion', 'precio', 'disponibilidad', 'foto')
            ->where('status', 'activo')
            ->where('disponibilidad', '>', 0)
            ->orderBy('precio', 'asc')
            ->limit(10)
            ->get();

        // Canjes recientes de mis alumnos - Optimizado con eager loading
        $canjesRecientes = Compra::select('cod_compra', 'cod_usuario', 'cod_publicacion', 'cantidad', 'precio_t', 'fecha', 'created_at')
            ->whereIn('cod_usuario', $misAlumnos->pluck('cod_usuario'))
            ->with([
                'usuario:cod_usuario,nombre,apellido,nick',
                'publicacion:cod_util,nombre,foto'
            ])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Alumno con más Recipuntos
        $alumnoDestacado = $misAlumnos->first();

        return view('profesor.dashboard', compact(
            'profesor',
            'misAlumnos',
            'totalAlumnos',
            'totalRecipuntos',
            'productosDisponibles',
            'canjesRecientes',
            'alumnoDestacado'
        ));
    }
}
