<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::with('categoriasHijas')->get();
        return response()->json($categorias);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_categoria' => 'required|string|max:200',
            'foto_categoria' => 'nullable|string|max:200',
        ]);

        $categoria = Categoria::create($validated);

        return response()->json([
            'message' => 'Categoría creada exitosamente',
            'data' => $categoria
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoria = Categoria::with(['categoriasHijas', 'publicaciones'])->findOrFail($id);
        return response()->json($categoria);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nombre_categoria' => 'string|max:200',
            'foto_categoria' => 'nullable|string|max:200',
        ]);

        $categoria->update($validated);

        return response()->json([
            'message' => 'Categoría actualizada exitosamente',
            'data' => $categoria
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return response()->json([
            'message' => 'Categoría eliminada exitosamente'
        ]);
    }

    /**
     * Obtener subcategorías de una categoría
     */
    public function obtenerHijos($id)
    {
        $categoria = Categoria::with('categoriasHijas')->findOrFail($id);
        return response()->json($categoria->categoriasHijas);
    }
}
