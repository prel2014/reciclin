<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagos = Pago::with('publicacion')->get();
        return response()->json($pagos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pagos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cod_publicacion' => 'required|exists:publicacion,cod_publicacion',
            'precio_p' => 'required|numeric',
        ]);

        $validated['fecha_pago'] = now()->format('Y-m-d H:i:s');
        $pago = Pago::create($validated);

        return response()->json([
            'message' => 'Pago registrado exitosamente',
            'data' => $pago
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pago = Pago::with('publicacion')->findOrFail($id);
        return response()->json($pago);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pago = Pago::findOrFail($id);
        return view('pagos.edit', compact('pago'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pago = Pago::findOrFail($id);

        $validated = $request->validate([
            'precio_p' => 'numeric',
        ]);

        $pago->update($validated);

        return response()->json([
            'message' => 'Pago actualizado exitosamente',
            'data' => $pago
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return response()->json([
            'message' => 'Pago eliminado exitosamente'
        ]);
    }
}
