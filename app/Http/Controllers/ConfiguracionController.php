<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $configuraciones = Configuracion::all();
        return response()->json($configuraciones);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('configuracion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'precio' => 'required|numeric',
        ]);

        $configuracion = Configuracion::create($validated);

        return response()->json([
            'message' => 'Configuración creada exitosamente',
            'data' => $configuracion
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $configuracion = Configuracion::findOrFail($id);
        return response()->json($configuracion);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $configuracion = Configuracion::findOrFail($id);
        return view('configuracion.edit', compact('configuracion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $configuracion = Configuracion::findOrFail($id);

        $validated = $request->validate([
            'precio' => 'numeric',
        ]);

        $configuracion->update($validated);

        return response()->json([
            'message' => 'Configuración actualizada exitosamente',
            'data' => $configuracion
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $configuracion = Configuracion::findOrFail($id);
        $configuracion->delete();

        return response()->json([
            'message' => 'Configuración eliminada exitosamente'
        ]);
    }
}
