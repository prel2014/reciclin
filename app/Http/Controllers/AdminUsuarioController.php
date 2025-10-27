<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUsuarioController extends Controller
{
    /**
     * Mostrar lista de usuarios para admin
     */
    public function index(Request $request)
    {
        $query = Usuario::query();

        // Búsqueda
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nick', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo
        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:admin,administrador,profesor,alumno,usuario',
            'nick' => 'required|string|max:200|unique:usuario,nick',
            'correo' => 'required|email|max:200|unique:usuario,correo',
            'contrasena' => 'required|string|min:6',
            'nombre' => 'required|string|max:200',
            'apellido' => 'required|string|max:250',
            'telefono' => 'nullable|string|max:200',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'tipo.required' => 'El tipo de usuario es obligatorio',
            'tipo.in' => 'El tipo de usuario seleccionado no es válido',
            'nick.required' => 'El nick es obligatorio',
            'nick.unique' => 'Este nick ya está en uso',
            'correo.required' => 'El correo es obligatorio',
            'correo.unique' => 'Este correo ya está registrado',
            'correo.email' => 'El correo debe ser válido',
            'contrasena.required' => 'La contraseña es obligatoria',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'estado.required' => 'El estado es obligatorio',
        ]);

        $validated['publicaciones'] = 0;

        Usuario::create($validated);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Mostrar detalles de un usuario
     */
    public function show($id)
    {
        $usuario = Usuario::with(['publicaciones', 'compras'])->findOrFail($id);
        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'tipo' => 'required|in:admin,administrador,profesor,alumno,usuario',
            'nick' => 'required|string|max:200|unique:usuario,nick,' . $id . ',cod_usuario',
            'correo' => 'required|email|max:200|unique:usuario,correo,' . $id . ',cod_usuario',
            'nombre' => 'required|string|max:200',
            'apellido' => 'required|string|max:250',
            'telefono' => 'nullable|string|max:200',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'tipo.required' => 'El tipo de usuario es obligatorio',
            'tipo.in' => 'El tipo de usuario seleccionado no es válido',
            'nick.required' => 'El nick es obligatorio',
            'nick.unique' => 'Este nick ya está en uso',
            'correo.required' => 'El correo es obligatorio',
            'correo.unique' => 'Este correo ya está registrado',
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'estado.required' => 'El estado es obligatorio',
        ]);

        // Solo actualizar contraseña si se proporciona
        if ($request->filled('contrasena')) {
            $request->validate([
                'contrasena' => 'string|min:6',
            ]);
            $validated['contrasena'] = $request->contrasena;
        }

        $usuario->update($validated);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        // No permitir eliminar al administrador actual
        if (auth()->id() == $id) {
            return redirect()
                ->back()
                ->with('error', 'No puedes eliminar tu propia cuenta');
        }

        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }

    /**
     * Cambiar estado de un usuario (activar/desactivar)
     */
    public function toggleStatus($id)
    {
        $usuario = Usuario::findOrFail($id);

        if (auth()->id() == $id) {
            return redirect()
                ->back()
                ->with('error', 'No puedes cambiar tu propio estado');
        }

        $usuario->estado = $usuario->estado === 'activo' ? 'inactivo' : 'activo';
        $usuario->save();

        return redirect()
            ->back()
            ->with('success', 'Estado actualizado exitosamente');
    }
}
