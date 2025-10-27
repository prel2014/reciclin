<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserPublicacionController extends Controller
{
    /**
     * Mostrar publicaciones del usuario autenticado
     */
    public function index()
    {
        $publicaciones = Publicacion::where('cod_usuario', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.publicaciones.index', compact('publicaciones'));
    }

    /**
     * Mostrar formulario para crear publicación
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('user.publicaciones.create', compact('categorias'));
    }

    /**
     * Guardar nueva publicación
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'categoria' => 'required|exists:categoria,cod_categoria',
            'disponibilidad' => 'required|numeric|min:1',
            'precio' => 'required|numeric|min:0.01',
            'calidad' => 'required|in:baja,media,alta',
            'region' => 'required|string|max:200',
            'provincia' => 'required|string|max:200',
            'distrito' => 'required|string|max:200',
            'foto1' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'foto2' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'foto3' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'descripcion.required' => 'La descripción es obligatoria',
            'categoria.required' => 'Debes seleccionar una categoría',
            'categoria.exists' => 'La categoría seleccionada no existe',
            'disponibilidad.required' => 'La disponibilidad es obligatoria',
            'disponibilidad.min' => 'La disponibilidad debe ser al menos 1',
            'precio.required' => 'El precio es obligatorio',
            'precio.min' => 'El precio debe ser mayor a 0',
            'calidad.required' => 'Debes seleccionar la calidad',
            'region.required' => 'La región es obligatoria',
            'provincia.required' => 'La provincia es obligatoria',
            'distrito.required' => 'El distrito es obligatorio',
            'foto1.image' => 'El archivo debe ser una imagen',
            'foto1.mimes' => 'La imagen debe ser jpg, jpeg, png o webp',
            'foto1.max' => 'La imagen no debe pesar más de 2MB',
            'foto2.image' => 'El archivo debe ser una imagen',
            'foto2.mimes' => 'La imagen debe ser jpg, jpeg, png o webp',
            'foto2.max' => 'La imagen no debe pesar más de 2MB',
            'foto3.image' => 'El archivo debe ser una imagen',
            'foto3.mimes' => 'La imagen debe ser jpg, jpeg, png o webp',
            'foto3.max' => 'La imagen no debe pesar más de 2MB',
        ]);

        // Procesar uploads de imágenes
        $foto1Path = null;
        $foto2Path = null;
        $foto3Path = null;

        if ($request->hasFile('foto1')) {
            $foto1Path = $request->file('foto1')->store('publicaciones', 'public');
        }

        if ($request->hasFile('foto2')) {
            $foto2Path = $request->file('foto2')->store('publicaciones', 'public');
        }

        if ($request->hasFile('foto3')) {
            $foto3Path = $request->file('foto3')->store('publicaciones', 'public');
        }

        // Crear publicación
        Publicacion::create([
            'cod_usuario' => Auth::id(),
            'nombre' => $validated['nombre'],
            'categoria' => $validated['categoria'],
            'sub_categoria' => null,
            'descripcion' => $validated['descripcion'],
            'disponibilidad' => $validated['disponibilidad'],
            'precio' => $validated['precio'],
            'moneda' => 'PEN',
            'calidad' => $validated['calidad'],
            'region' => $validated['region'],
            'provincia' => $validated['provincia'],
            'distrito' => $validated['distrito'],
            'foto1' => $foto1Path,
            'foto2' => $foto2Path,
            'foto3' => $foto3Path,
            'fecha_p' => now()->format('Y-m-d'),
            'fecha_u' => now()->format('Y-m-d'),
            'status' => 'activo',
            'vistas' => 0,
        ]);

        return redirect()
            ->route('user.publicaciones.index')
            ->with('success', '¡Publicación creada exitosamente!');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $publicacion = Publicacion::where('cod_publicacion', $id)
            ->where('cod_usuario', Auth::id())
            ->firstOrFail();

        $categorias = Categoria::all();

        return view('user.publicaciones.edit', compact('publicacion', 'categorias'));
    }

    /**
     * Actualizar publicación
     */
    public function update(Request $request, $id)
    {
        $publicacion = Publicacion::where('cod_publicacion', $id)
            ->where('cod_usuario', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'categoria' => 'required|exists:categoria,cod_categoria',
            'disponibilidad' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0.01',
            'calidad' => 'required|in:baja,media,alta',
            'region' => 'required|string|max:200',
            'provincia' => 'required|string|max:200',
            'distrito' => 'required|string|max:200',
            'status' => 'required|in:activo,inactivo',
            'foto1' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'foto2' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'foto3' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'eliminar_foto1' => 'nullable|boolean',
            'eliminar_foto2' => 'nullable|boolean',
            'eliminar_foto3' => 'nullable|boolean',
        ]);

        // Procesar nuevas imágenes
        if ($request->hasFile('foto1')) {
            // Eliminar imagen anterior si existe
            if ($publicacion->foto1) {
                Storage::disk('public')->delete($publicacion->foto1);
            }
            $validated['foto1'] = $request->file('foto1')->store('publicaciones', 'public');
        } elseif ($request->has('eliminar_foto1') && $request->eliminar_foto1) {
            // Eliminar imagen si se marcó para eliminar
            if ($publicacion->foto1) {
                Storage::disk('public')->delete($publicacion->foto1);
                $validated['foto1'] = null;
            }
        } else {
            // Mantener imagen actual
            $validated['foto1'] = $publicacion->foto1;
        }

        if ($request->hasFile('foto2')) {
            if ($publicacion->foto2) {
                Storage::disk('public')->delete($publicacion->foto2);
            }
            $validated['foto2'] = $request->file('foto2')->store('publicaciones', 'public');
        } elseif ($request->has('eliminar_foto2') && $request->eliminar_foto2) {
            if ($publicacion->foto2) {
                Storage::disk('public')->delete($publicacion->foto2);
                $validated['foto2'] = null;
            }
        } else {
            $validated['foto2'] = $publicacion->foto2;
        }

        if ($request->hasFile('foto3')) {
            if ($publicacion->foto3) {
                Storage::disk('public')->delete($publicacion->foto3);
            }
            $validated['foto3'] = $request->file('foto3')->store('publicaciones', 'public');
        } elseif ($request->has('eliminar_foto3') && $request->eliminar_foto3) {
            if ($publicacion->foto3) {
                Storage::disk('public')->delete($publicacion->foto3);
                $validated['foto3'] = null;
            }
        } else {
            $validated['foto3'] = $publicacion->foto3;
        }

        $validated['fecha_u'] = now()->format('Y-m-d');

        $publicacion->update($validated);

        return redirect()
            ->route('user.publicaciones.index')
            ->with('success', 'Publicación actualizada exitosamente');
    }

    /**
     * Eliminar publicación
     */
    public function destroy($id)
    {
        $publicacion = Publicacion::where('cod_publicacion', $id)
            ->where('cod_usuario', Auth::id())
            ->firstOrFail();

        // Eliminar imágenes del storage
        if ($publicacion->foto1) {
            Storage::disk('public')->delete($publicacion->foto1);
        }
        if ($publicacion->foto2) {
            Storage::disk('public')->delete($publicacion->foto2);
        }
        if ($publicacion->foto3) {
            Storage::disk('public')->delete($publicacion->foto3);
        }

        $publicacion->delete();

        return redirect()
            ->route('user.publicaciones.index')
            ->with('success', 'Publicación eliminada exitosamente');
    }
}
