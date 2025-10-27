<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductoController extends Controller
{
    /**
     * Muestra la lista de productos (útiles escolares)
     */
    public function index()
    {
        $productos = Publicacion::orderBy('created_at', 'desc')->get();

        return view('admin.productos.index', compact('productos'));
    }

    /**
     * Almacena un nuevo producto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'disponibilidad' => 'required|numeric|min:0',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nombre.required' => 'El nombre del útil escolar es obligatorio',
            'precio.required' => 'El precio en Recipuntos es obligatorio',
            'precio.numeric' => 'El precio debe ser un número',
            'precio.min' => 'El precio no puede ser negativo',
            'disponibilidad.required' => 'El stock es obligatorio',
            'disponibilidad.numeric' => 'El stock debe ser un número',
            'foto1.image' => 'El archivo debe ser una imagen',
            'foto1.mimes' => 'La imagen debe ser formato: jpeg, png, jpg o gif',
            'foto1.max' => 'La imagen no puede superar 2MB',
        ]);

        try {
            // Procesar imagen si existe
            $fotoPath = null;
            if ($request->hasFile('foto1')) {
                $fotoPath = $request->file('foto1')->store('productos', 'public');
            }

            Publicacion::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'precio' => $validated['precio'],
                'disponibilidad' => $validated['disponibilidad'],
                'foto' => $fotoPath,
                'status' => 'activo',
            ]);

            return redirect()->route('admin.productos.index')
                ->with('success', 'Útil escolar creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.productos.index')
                ->with('error', 'Error al crear el útil: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza un producto existente
     */
    public function update(Request $request, $id)
    {
        $producto = Publicacion::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'disponibilidad' => 'required|numeric|min:0',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nombre.required' => 'El nombre del útil escolar es obligatorio',
            'precio.required' => 'El precio en Recipuntos es obligatorio',
            'disponibilidad.required' => 'El stock es obligatorio',
        ]);

        try {
            // Actualizar campos básicos
            $producto->nombre = $validated['nombre'];
            $producto->descripcion = $validated['descripcion'];
            $producto->precio = $validated['precio'];
            $producto->disponibilidad = $validated['disponibilidad'];

            // Procesar nueva imagen si existe
            if ($request->hasFile('foto1')) {
                // Eliminar imagen anterior si existe
                if ($producto->foto && Storage::disk('public')->exists($producto->foto)) {
                    Storage::disk('public')->delete($producto->foto);
                }

                // Guardar nueva imagen
                $producto->foto = $request->file('foto1')->store('productos', 'public');
            }

            $producto->save();

            return redirect()->route('admin.productos.index')
                ->with('success', 'Útil escolar actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.productos.index')
                ->with('error', 'Error al actualizar el útil: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un producto
     */
    public function destroy($id)
    {
        try {
            $producto = Publicacion::findOrFail($id);

            // Verificar si tiene canjes asociados
            if ($producto->compras()->count() > 0) {
                return redirect()->route('admin.productos.index')
                    ->with('error', 'No se puede eliminar el producto porque tiene canjes asociados');
            }

            // Eliminar imagen si existe
            if ($producto->foto && Storage::disk('public')->exists($producto->foto)) {
                Storage::disk('public')->delete($producto->foto);
            }

            $producto->delete();

            return redirect()->route('admin.productos.index')
                ->with('success', 'Producto eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('admin.productos.index')
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado del producto
     */
    public function toggleStatus($id)
    {
        try {
            $producto = Publicacion::findOrFail($id);
            $producto->status = $producto->status === 'activo' ? 'inactivo' : 'activo';
            $producto->save();

            return redirect()->route('admin.productos.index')
                ->with('success', 'Estado del producto actualizado');
        } catch (\Exception $e) {
            return redirect()->route('admin.productos.index')
                ->with('error', 'Error al actualizar el estado');
        }
    }
}
