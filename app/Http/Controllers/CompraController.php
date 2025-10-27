<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::with(['usuario', 'publicacion'])->get();
        return response()->json($compras);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('compras.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cod_usuario' => 'required|exists:usuario,cod_usuario',
            'cod_publicacion' => 'required|exists:publicacion,cod_publicacion',
            'cantidad' => 'required|numeric|min:1',
            'precio_v' => 'required|numeric',
        ]);

        $validated['precio_t'] = $validated['cantidad'] * $validated['precio_v'];
        $validated['fecha'] = now()->format('Y-m-d H:i:s');
        $validated['status'] = 'pendiente';

        $compra = Compra::create($validated);

        return response()->json([
            'message' => 'Compra registrada exitosamente',
            'data' => $compra
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $compra = Compra::with(['usuario', 'publicacion'])->findOrFail($id);
        return response()->json($compra);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $compra = Compra::findOrFail($id);
        return view('compras.edit', compact('compra'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $compra = Compra::findOrFail($id);

        $validated = $request->validate([
            'cantidad' => 'numeric|min:1',
            'status' => 'string|max:200',
        ]);

        if (isset($validated['cantidad'])) {
            $validated['precio_t'] = $validated['cantidad'] * $compra->precio_v;
        }

        $compra->update($validated);

        return response()->json([
            'message' => 'Compra actualizada exitosamente',
            'data' => $compra
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $compra = Compra::findOrFail($id);
        $compra->delete();

        return response()->json([
            'message' => 'Compra eliminada exitosamente'
        ]);
    }
}
