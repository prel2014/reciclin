<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;

class ProfesorProductoController extends Controller
{
    /**
     * Mostrar lista de productos disponibles (solo lectura)
     */
    public function index()
    {
        // Obtener todos los productos
        $productos = Publicacion::orderBy('nombre')->paginate(20);

        // Estadísticas
        $totalProductos = Publicacion::count();
        $productosActivos = Publicacion::where('status', 'activo')->count();
        $productosDisponibles = Publicacion::where('status', 'activo')
            ->where('disponibilidad', '>', 0)
            ->count();
        $stockTotal = Publicacion::where('status', 'activo')->sum('disponibilidad');

        return view('profesor.productos.index', compact(
            'productos',
            'totalProductos',
            'productosActivos',
            'productosDisponibles',
            'stockTotal'
        ));
    }

    /**
     * Mostrar detalles de un producto específico
     */
    public function show($id)
    {
        $producto = Publicacion::findOrFail($id);

        return view('profesor.productos.show', compact('producto'));
    }
}
