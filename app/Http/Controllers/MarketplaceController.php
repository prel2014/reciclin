<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use App\Models\Categoria;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Mostrar listado de publicaciones (marketplace)
     */
    public function index(Request $request)
    {
        $query = Publicacion::with(['usuario']);

        // Búsqueda por palabra clave
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria', $request->categoria);
        }

        // Filtro por estado (solo activas)
        $query->where('status', 'activo');

        // Ordenamiento
        $orderBy = $request->get('order', 'recent');
        switch ($orderBy) {
            case 'price_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'popular':
                $query->orderBy('vistas', 'desc');
                break;
            default:
                $query->orderBy('fecha_p', 'desc');
                break;
        }

        $publicaciones = $query->paginate(12);
        $categorias = Categoria::all();

        return view('marketplace.index', compact('publicaciones', 'categorias'));
    }

    /**
     * Mostrar detalle de una publicación
     */
    public function show($id)
    {
        $publicacion = Publicacion::with(['usuario'])->findOrFail($id);

        // Incrementar contador de visitas
        $publicacion->increment('vistas');

        // Publicaciones relacionadas (misma categoría)
        $relacionadas = Publicacion::where('categoria', $publicacion->categoria)
            ->where('cod_publicacion', '!=', $id)
            ->where('status', 'activo')
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('publicacion', 'relacionadas'));
    }

    /**
     * Procesar compra de una publicación
     */
    public function comprar(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para realizar una compra');
        }

        $publicacion = Publicacion::findOrFail($id);

        $validated = $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        // Crear compra
        $compra = new \App\Models\Compra();
        $compra->cod_usuario = auth()->id();
        $compra->cod_publicacion = $publicacion->cod_publicacion;
        $compra->cantidad = $validated['cantidad'];
        $compra->precio_v = $publicacion->precio;
        $compra->precio_t = $publicacion->precio * $validated['cantidad'];
        $compra->fecha = now();
        $compra->status = 'pendiente';
        $compra->save();

        return redirect()
            ->route('marketplace.show', $id)
            ->with('success', '¡Compra realizada exitosamente! Te contactaremos pronto.');
    }
}
