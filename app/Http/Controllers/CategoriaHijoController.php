<?php

namespace App\Http\Controllers;

use App\Models\CategoriaHijo;
use Illuminate\Http\Request;

class CategoriaHijoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoriasHijas = CategoriaHijo::with('categoriaPadre')->get();
        return response()->json($categoriasHijas);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias-hijos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cod_categoria_padre' => 'required|exists:categoria,cod_categoria',
            'nombre_categoria_hijo' => 'required|string|max:200',
            'foto_categoria_hijo' => 'nullable|string|max:200',
        ]);

        $categoriaHijo = CategoriaHijo::create($validated);

        return response()->json([
            'message' => 'Subcategoría creada exitosamente',
            'data' => $categoriaHijo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categoriaHijo = CategoriaHijo::with(['categoriaPadre', 'publicaciones'])->findOrFail($id);
        return response()->json($categoriaHijo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categoriaHijo = CategoriaHijo::findOrFail($id);
        return view('categorias-hijos.edit', compact('categoriaHijo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoriaHijo = CategoriaHijo::findOrFail($id);

        $validated = $request->validate([
            'cod_categoria_padre' => 'exists:categoria,cod_categoria',
            'nombre_categoria_hijo' => 'string|max:200',
            'foto_categoria_hijo' => 'nullable|string|max:200',
        ]);

        $categoriaHijo->update($validated);

        return response()->json([
            'message' => 'Subcategoría actualizada exitosamente',
            'data' => $categoriaHijo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoriaHijo = CategoriaHijo::findOrFail($id);
        $categoriaHijo->delete();

        return response()->json([
            'message' => 'Subcategoría eliminada exitosamente'
        ]);
    }
}
