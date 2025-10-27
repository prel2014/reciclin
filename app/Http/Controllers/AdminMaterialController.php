<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialReciclable;
use Illuminate\Support\Facades\Storage;

class AdminMaterialController extends Controller
{
    /**
     * Display a listing of recyclable materials.
     */
    public function index()
    {
        $materiales = MaterialReciclable::orderBy('cod_material', 'desc')->get();
        return view('admin.materiales.index', compact('materiales'));
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'recipuntos_por_cantidad' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('materiales', 'public');
            $data['imagen'] = $imagePath;
        }

        MaterialReciclable::create($data);

        return redirect()->route('admin.materiales.index')
            ->with('success', 'Material reciclable creado exitosamente');
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, $id)
    {
        $material = MaterialReciclable::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'recipuntos_por_cantidad' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            // Delete old image if exists
            if ($material->imagen) {
                Storage::disk('public')->delete($material->imagen);
            }
            $imagePath = $request->file('imagen')->store('materiales', 'public');
            $data['imagen'] = $imagePath;
        }

        $material->update($data);

        return redirect()->route('admin.materiales.index')
            ->with('success', 'Material reciclable actualizado exitosamente');
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy($id)
    {
        $material = MaterialReciclable::findOrFail($id);

        // Delete image if exists
        if ($material->imagen) {
            Storage::disk('public')->delete($material->imagen);
        }

        $material->delete();

        return redirect()->route('admin.materiales.index')
            ->with('success', 'Material reciclable eliminado exitosamente');
    }

    /**
     * Toggle material status (activo/inactivo).
     */
    public function toggleStatus($id)
    {
        $material = MaterialReciclable::findOrFail($id);
        $material->estado = ($material->estado === 'activo') ? 'inactivo' : 'activo';
        $material->save();

        return redirect()->route('admin.materiales.index')
            ->with('success', 'Estado del material actualizado');
    }
}
