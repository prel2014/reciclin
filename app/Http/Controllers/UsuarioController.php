<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:200',
            'nick' => 'required|string|max:200',
            'correo' => 'required|email|max:200',
            'contrasena' => 'required|string|max:200',
            'nombre' => 'required|string|max:200',
            'apellido' => 'required|string|max:250',
            'telefono' => 'nullable|string|max:200',
        ]);

        $validated['contrasena'] = bcrypt($validated['contrasena']);
        $validated['publicaciones'] = 0;
        $validated['estado'] = 'activo';

        $usuario = Usuario::create($validated);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'data' => $usuario
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = Usuario::with(['publicaciones', 'compras'])->findOrFail($id);
        return response()->json($usuario);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'tipo' => 'string|max:200',
            'nick' => 'string|max:200',
            'correo' => 'email|max:200',
            'nombre' => 'string|max:200',
            'apellido' => 'string|max:250',
            'telefono' => 'nullable|string|max:200',
            'estado' => 'string|max:200',
        ]);

        $usuario->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'data' => $usuario
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado exitosamente'
        ]);
    }

    /**
     * Obtener compras de un usuario
     */
    public function obtenerCompras($id)
    {
        $usuario = Usuario::with('compras.publicacion')->findOrFail($id);
        return response()->json($usuario->compras);
    }
}
