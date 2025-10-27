<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        try {
            $productos = Publicacion::select('cod_util', 'nombre', 'descripcion', 'precio', 'disponibilidad', 'foto', 'status', 'created_at')
                ->orderBy('nombre')->get();

            return response()->json(['success' => true, 'data' => $productos], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener productos', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:200',
                'descripcion' => 'nullable|string',
                'precio' => 'required|numeric|min:0',
                'disponibilidad' => 'required|numeric|min:0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('productos', 'public');
            }

            $producto = Publicacion::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'precio' => $validated['precio'],
                'disponibilidad' => $validated['disponibilidad'],
                'foto' => $fotoPath,
                'status' => 'activo',
            ]);

            return response()->json(['success' => true, 'message' => 'Producto creado exitosamente', 'data' => $producto], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al crear producto', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $producto = Publicacion::findOrFail($id);
            return response()->json(['success' => true, 'data' => $producto], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener producto', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $producto = Publicacion::findOrFail($id);

            $validated = $request->validate([
                'nombre' => 'required|string|max:200',
                'descripcion' => 'nullable|string',
                'precio' => 'required|numeric|min:0',
                'disponibilidad' => 'required|numeric|min:0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'nullable|in:activo,inactivo',
            ]);

            $producto->nombre = $validated['nombre'];
            $producto->descripcion = $validated['descripcion'];
            $producto->precio = $validated['precio'];
            $producto->disponibilidad = $validated['disponibilidad'];

            if (isset($validated['status'])) {
                $producto->status = $validated['status'];
            }

            if ($request->hasFile('foto')) {
                if ($producto->foto && Storage::disk('public')->exists($producto->foto)) {
                    Storage::disk('public')->delete($producto->foto);
                }
                $producto->foto = $request->file('foto')->store('productos', 'public');
            }

            $producto->save();

            return response()->json(['success' => true, 'message' => 'Producto actualizado exitosamente', 'data' => $producto], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar producto', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $producto = Publicacion::findOrFail($id);

            if ($producto->compras()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'No se puede eliminar el producto porque tiene canjes asociados'], 400);
            }

            if ($producto->foto && Storage::disk('public')->exists($producto->foto)) {
                Storage::disk('public')->delete($producto->foto);
            }

            $producto->delete();

            return response()->json(['success' => true, 'message' => 'Producto eliminado exitosamente'], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar producto', 'error' => $e->getMessage()], 500);
        }
    }
}
