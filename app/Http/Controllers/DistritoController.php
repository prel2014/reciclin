<?php

namespace App\Http\Controllers;

use App\Models\Distrito;
use Illuminate\Http\Request;

class DistritoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $distritos = Distrito::all();
        return response()->json($distritos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('distritos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'region' => 'required|string|max:200',
            'provincia' => 'required|string|max:200',
            'distrito' => 'required|string|max:200',
        ]);

        $distrito = Distrito::create($validated);

        return response()->json([
            'message' => 'Distrito creado exitosamente',
            'data' => $distrito
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $distrito = Distrito::findOrFail($id);
        return response()->json($distrito);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $distrito = Distrito::findOrFail($id);
        return view('distritos.edit', compact('distrito'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $distrito = Distrito::findOrFail($id);

        $validated = $request->validate([
            'region' => 'string|max:200',
            'provincia' => 'string|max:200',
            'distrito' => 'string|max:200',
        ]);

        $distrito->update($validated);

        return response()->json([
            'message' => 'Distrito actualizado exitosamente',
            'data' => $distrito
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $distrito = Distrito::findOrFail($id);
        $distrito->delete();

        return response()->json([
            'message' => 'Distrito eliminado exitosamente'
        ]);
    }

    /**
     * Obtener distritos por provincia
     */
    public function porProvincia($provincia)
    {
        $distritos = Distrito::where('provincia', $provincia)->get();
        return response()->json($distritos);
    }
}
