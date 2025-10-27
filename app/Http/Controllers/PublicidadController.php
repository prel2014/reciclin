<?php

namespace App\Http\Controllers;

use App\Models\Publicidad;
use Illuminate\Http\Request;

class PublicidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publicidades = Publicidad::all();
        return response()->json($publicidades);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('publicidad.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|integer',
            'url' => 'required|integer',
        ]);

        $publicidad = Publicidad::create($validated);

        return response()->json([
            'message' => 'Publicidad creada exitosamente',
            'data' => $publicidad
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $publicidad = Publicidad::findOrFail($id);
        return response()->json($publicidad);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $publicidad = Publicidad::findOrFail($id);
        return view('publicidad.edit', compact('publicidad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $publicidad = Publicidad::findOrFail($id);

        $validated = $request->validate([
            'tipo' => 'integer',
            'url' => 'integer',
        ]);

        $publicidad->update($validated);

        return response()->json([
            'message' => 'Publicidad actualizada exitosamente',
            'data' => $publicidad
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $publicidad = Publicidad::findOrFail($id);
        $publicidad->delete();

        return response()->json([
            'message' => 'Publicidad eliminada exitosamente'
        ]);
    }
}
