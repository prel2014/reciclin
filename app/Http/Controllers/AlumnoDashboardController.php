<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Publicacion;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoDashboardController extends Controller
{
    /**
     * Dashboard para alumnos
     */
    public function index()
    {
        $alumno = Auth::user();

        // Mis Recipuntos
        $misRecipuntos = $alumno->recipuntos ?? 0;

        // Mi profesor
        $miProfesor = $alumno->profesor;

        // Productos que puedo canjear
        $productosDisponibles = Publicacion::where('status', 'activo')
            ->where('disponibilidad', '>', 0)
            ->where('precio', '<=', $misRecipuntos)
            ->orderBy('precio', 'asc')
            ->get();

        // Todos los productos
        $todosProductos = Publicacion::where('status', 'activo')
            ->where('disponibilidad', '>', 0)
            ->orderBy('precio', 'asc')
            ->take(12)
            ->get();

        // Mis canjes (historial)
        $misCanjes = Compra::where('cod_usuario', $alumno->cod_usuario)
            ->with('publicacion')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Total Recipuntos canjeados
        $recipuntosCanjeados = $misCanjes->sum('precio_t');

        // Ranking (mi posición)
        $todosAlumnos = Usuario::where('tipo', 'alumno')
            ->orderBy('recipuntos', 'desc')
            ->get();

        $miPosicion = $todosAlumnos->search(function($user) use ($alumno) {
            return $user->cod_usuario === $alumno->cod_usuario;
        }) + 1;

        return view('alumno.dashboard', compact(
            'alumno',
            'misRecipuntos',
            'miProfesor',
            'productosDisponibles',
            'todosProductos',
            'misCanjes',
            'recipuntosCanjeados',
            'miPosicion',
            'todosAlumnos'
        ));
    }
}
