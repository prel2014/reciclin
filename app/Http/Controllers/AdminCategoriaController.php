<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCategoriaController extends Controller
{
    /**
     * Muestra la lista de categorías
     */
    public function index()
    {
        $categorias = Categoria::withCount('publicaciones')->get();
        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Almacena una nueva categoría
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_categoria' => 'required|string|max:200',
            'foto_categoria' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nombre_categoria.required' => 'El nombre de la categoría es obligatorio',
            'nombre_categoria.max' => 'El nombre no puede exceder 200 caracteres',
            'foto_categoria.image' => 'El archivo debe ser una imagen',
            'foto_categoria.mimes' => 'La imagen debe ser formato: jpeg, png, jpg o gif',
            'foto_categoria.max' => 'La imagen no puede superar 2MB',
        ]);

        try {
            // Procesar imagen si existe
            $fotoPath = null;
            if ($request->hasFile('foto_categoria')) {
                $fotoPath = $request->file('foto_categoria')->store('categorias', 'public');
            }

            Categoria::create([
                'nombre_categoria' => $validated['nombre_categoria'],
                'foto_categoria' => $fotoPath,
            ]);

            return redirect()->route('admin.categorias.index')
                ->with('success', 'Categoría creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.categorias.index')
                ->with('error', 'Error al crear la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza una categoría existente
     */
    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nombre_categoria' => 'required|string|max:200',
            'foto_categoria' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nombre_categoria.required' => 'El nombre de la categoría es obligatorio',
            'nombre_categoria.max' => 'El nombre no puede exceder 200 caracteres',
            'foto_categoria.image' => 'El archivo debe ser una imagen',
            'foto_categoria.mimes' => 'La imagen debe ser formato: jpeg, png, jpg o gif',
            'foto_categoria.max' => 'La imagen no puede superar 2MB',
        ]);

        try {
            // Actualizar nombre
            $categoria->nombre_categoria = $validated['nombre_categoria'];

            // Procesar nueva imagen si existe
            if ($request->hasFile('foto_categoria')) {
                // Eliminar imagen anterior si existe
                if ($categoria->foto_categoria && Storage::disk('public')->exists($categoria->foto_categoria)) {
                    Storage::disk('public')->delete($categoria->foto_categoria);
                }

                // Guardar nueva imagen
                $categoria->foto_categoria = $request->file('foto_categoria')->store('categorias', 'public');
            }

            $categoria->save();

            return redirect()->route('admin.categorias.index')
                ->with('success', 'Categoría actualizada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.categorias.index')
                ->with('error', 'Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Elimina una categoría
     */
    public function destroy($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);

            // Verificar si tiene productos asociados
            if ($categoria->publicaciones()->count() > 0) {
                return redirect()->route('admin.categorias.index')
                    ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados');
            }

            // Eliminar imagen si existe
            if ($categoria->foto_categoria && Storage::disk('public')->exists($categoria->foto_categoria)) {
                Storage::disk('public')->delete($categoria->foto_categoria);
            }

            $categoria->delete();

            return redirect()->route('admin.categorias.index')
                ->with('success', 'Categoría eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.categorias.index')
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }
}
