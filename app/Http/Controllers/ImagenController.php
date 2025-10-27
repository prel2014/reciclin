<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use Illuminate\Http\Request;

class ImagenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $imagenes = Imagen::all();
        return response()->json($imagenes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('imagenes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medida' => 'required|string|max:200',
            'nombre' => 'required|string|max:200',
            'foto' => 'required|string|max:200',
        ]);

        $imagen = Imagen::create($validated);

        return response()->json([
            'message' => 'Imagen creada exitosamente',
            'data' => $imagen
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $imagen = Imagen::findOrFail($id);
        return response()->json($imagen);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $imagen = Imagen::findOrFail($id);
        return view('imagenes.edit', compact('imagen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $imagen = Imagen::findOrFail($id);

        $validated = $request->validate([
            'medida' => 'string|max:200',
            'nombre' => 'string|max:200',
            'foto' => 'string|max:200',
        ]);

        $imagen->update($validated);

        return response()->json([
            'message' => 'Imagen actualizada exitosamente',
            'data' => $imagen
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagen = Imagen::findOrFail($id);
        $imagen->delete();

        return response()->json([
            'message' => 'Imagen eliminada exitosamente'
        ]);
    }
}
