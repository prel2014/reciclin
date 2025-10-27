<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaterialReciclable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Listar todos los materiales reciclables
     */
    public function index()
    {
        try {
            $materiales = MaterialReciclable::select('cod_material', 'nombre', 'descripcion', 'recipuntos_por_cantidad', 'imagen', 'estado', 'created_at')
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $materiales
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener materiales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nuevo material reciclable
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
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

            $material = MaterialReciclable::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Material reciclable creado exitosamente',
                'data' => $material
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un material específico
     */
    public function show($id)
    {
        try {
            $material = MaterialReciclable::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $material
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Material no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar material reciclable
     */
    public function update(Request $request, $id)
    {
        try {
            $material = MaterialReciclable::findOrFail($id);

            $validated = $request->validate([
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

            return response()->json([
                'success' => true,
                'message' => 'Material actualizado exitosamente',
                'data' => $material
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Material no encontrado'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar material reciclable
     */
    public function destroy($id)
    {
        try {
            $material = MaterialReciclable::findOrFail($id);

            // Delete image if exists
            if ($material->imagen) {
                Storage::disk('public')->delete($material->imagen);
            }

            $material->delete();

            return response()->json([
                'success' => true,
                'message' => 'Material eliminado exitosamente'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Material no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar material',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
