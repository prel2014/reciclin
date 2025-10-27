<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;

class PublicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publicaciones = Publicacion::with(['usuario', 'categoriaInfo', 'subCategoriaInfo'])->get();
        return response()->json($publicaciones);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('publicaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cod_usuario' => 'required|exists:usuario,cod_usuario',
            'nombre' => 'required|string|max:200',
            'categoria' => 'required|exists:categoria,cod_categoria',
            'sub_categoria' => 'nullable|exists:categoria_hijo,cod_categoria_hijo',
            'descripcion' => 'required|string',
            'disponibilidad' => 'required|numeric',
            'precio' => 'required|numeric',
            'moneda' => 'required|string|max:200',
            'calidad' => 'nullable|string|max:200',
            'region' => 'nullable|string|max:200',
            'provincia' => 'nullable|string|max:200',
            'distrito' => 'nullable|string|max:200',
            'foto1' => 'nullable|string|max:250',
            'foto2' => 'nullable|string|max:250',
            'foto3' => 'nullable|string|max:250',
        ]);

        $validated['fecha_p'] = now()->format('Y-m-d H:i:s');
        $validated['status'] = 'activo';
        $validated['vistas'] = 0;

        $publicacion = Publicacion::create($validated);

        return response()->json([
            'message' => 'Publicación creada exitosamente',
            'data' => $publicacion
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $publicacion = Publicacion::with([
            'usuario',
            'categoriaInfo',
            'subCategoriaInfo',
            'compras',
            'pagos'
        ])->findOrFail($id);

        // Incrementar vistas
        $publicacion->increment('vistas');

        return response()->json($publicacion);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $publicacion = Publicacion::findOrFail($id);
        return view('publicaciones.edit', compact('publicacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $publicacion = Publicacion::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'string|max:200',
            'descripcion' => 'string',
            'disponibilidad' => 'numeric',
            'precio' => 'numeric',
            'moneda' => 'string|max:200',
            'calidad' => 'string|max:200',
            'status' => 'string|max:200',
            'foto1' => 'nullable|string|max:250',
            'foto2' => 'nullable|string|max:250',
            'foto3' => 'nullable|string|max:250',
        ]);

        $validated['fecha_u'] = now()->format('Y-m-d H:i:s');
        $publicacion->update($validated);

        return response()->json([
            'message' => 'Publicación actualizada exitosamente',
            'data' => $publicacion
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $publicacion = Publicacion::findOrFail($id);
        $publicacion->delete();

        return response()->json([
            'message' => 'Publicación eliminada exitosamente'
        ]);
    }

    /**
     * Obtener publicaciones por categoría
     */
    public function porCategoria($id)
    {
        $publicaciones = Publicacion::with(['usuario', 'categoriaInfo'])
            ->where('categoria', $id)
            ->where('status', 'activo')
            ->get();

        return response()->json($publicaciones);
    }

    /**
     * Obtener publicaciones por usuario
     */
    public function porUsuario($id)
    {
        $publicaciones = Publicacion::with(['categoriaInfo', 'subCategoriaInfo'])
            ->where('cod_usuario', $id)
            ->get();

        return response()->json($publicaciones);
    }
}
