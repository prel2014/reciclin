<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Usuario::select('cod_usuario', 'tipo', 'nick', 'correo', 'nombre', 'apellido', 'telefono', 'recipuntos', 'estado', 'cod_profesor');

            // Filtros opcionales
            if ($request->has('tipo')) {
                $query->where('tipo', $request->tipo);
            }

            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->has('cod_profesor')) {
                $query->where('cod_profesor', $request->cod_profesor);
            }

            $usuarios = $query->orderBy('apellido')->orderBy('nombre')->get();

            return response()->json(['success' => true, 'data' => $usuarios], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener usuarios', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $usuario = Usuario::select('cod_usuario', 'tipo', 'nick', 'correo', 'nombre', 'apellido', 'telefono', 'recipuntos', 'estado', 'cod_profesor', 'created_at')
                ->findOrFail($id);

            return response()->json(['success' => true, 'data' => $usuario], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener usuario', 'error' => $e->getMessage()], 500);
        }
    }
}
