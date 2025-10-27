<?php

namespace App\Http\Controllers;

use App\Models\MultimediaHomepage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMultimediaController extends Controller
{
    /**
     * Mostrar lista de multimedia
     */
    public function index(Request $request)
    {
        $query = MultimediaHomepage::query();

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('seccion')) {
            $query->where('seccion', $request->seccion);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $multimedia = $query->ordenado()->paginate(15);

        // Estadísticas
        $totalMultimedia = MultimediaHomepage::count();
        $totalImagenes = MultimediaHomepage::where('tipo', 'imagen')->count();
        $totalVideos = MultimediaHomepage::where('tipo', 'video')->count();
        $totalActivos = MultimediaHomepage::where('estado', 'activo')->count();

        return view('admin.multimedia.index', compact(
            'multimedia',
            'totalMultimedia',
            'totalImagenes',
            'totalVideos',
            'totalActivos'
        ));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.multimedia.create');
    }

    /**
     * Guardar nuevo multimedia
     */
    public function store(Request $request)
    {
        $rules = [
            'tipo' => 'required|in:imagen,video',
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'seccion' => 'required|in:banner,galeria,destacado',
            'orden' => 'required|integer|min:0',
            'estado' => 'required|in:activo,inactivo',
        ];

        // Validaciones condicionales según el tipo
        if ($request->tipo === 'imagen') {
            $rules['archivo'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'; // Max 5MB
        } elseif ($request->tipo === 'video') {
            // Para video, puede ser archivo o URL (al menos uno requerido)
            $rules['archivo_video'] = 'nullable|file|mimes:mp4,mov,avi,wmv,flv,webm|max:51200'; // Max 50MB
            $rules['url_video'] = 'nullable|url|max:500';
        }

        $validated = $request->validate($rules, [
            'tipo.required' => 'Debe seleccionar un tipo de contenido',
            'titulo.required' => 'El título es obligatorio',
            'seccion.required' => 'Debe seleccionar una sección',
            'orden.required' => 'El orden es obligatorio',
            'archivo.required' => 'Debe subir una imagen',
            'archivo.image' => 'El archivo debe ser una imagen',
            'archivo.max' => 'La imagen no debe superar los 5MB',
            'url_video.required' => 'Debe ingresar la URL del video',
            'url_video.url' => 'La URL del video no es válida',
        ]);

        try {
            $data = [
                'tipo' => $validated['tipo'],
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'] ?? null,
                'seccion' => $validated['seccion'],
                'orden' => $validated['orden'],
                'estado' => $validated['estado'],
            ];

            // Si es imagen, guardar el archivo
            if ($request->tipo === 'imagen' && $request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $ruta = $archivo->storeAs('multimedia/imagenes', $nombreArchivo, 'public');
                $data['archivo'] = $ruta;
            }

            // Si es video
            if ($request->tipo === 'video') {
                // Prioridad 1: Archivo de video local
                if ($request->hasFile('archivo_video')) {
                    $archivoVideo = $request->file('archivo_video');
                    $nombreVideo = time() . '_' . $archivoVideo->getClientOriginalName();
                    $rutaVideo = $archivoVideo->storeAs('multimedia/videos', $nombreVideo, 'public');
                    $data['archivo_video'] = $rutaVideo;
                    $data['url_video'] = null; // Limpiar URL si hay archivo
                }
                // Prioridad 2: URL externa
                elseif ($request->filled('url_video')) {
                    $data['url_video'] = $validated['url_video'];
                    $data['archivo_video'] = null;
                }
                // Si no hay ninguno, error
                else {
                    return back()->withErrors(['error' => 'Debe proporcionar un archivo de video o una URL'])->withInput();
                }
            }

            MultimediaHomepage::create($data);

            return redirect()->route('admin.multimedia.index')
                ->with('success', 'Contenido multimedia creado exitosamente');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear multimedia: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $multimedia = MultimediaHomepage::findOrFail($id);
        return view('admin.multimedia.edit', compact('multimedia'));
    }

    /**
     * Actualizar multimedia
     */
    public function update(Request $request, $id)
    {
        $multimedia = MultimediaHomepage::findOrFail($id);

        $rules = [
            'tipo' => 'required|in:imagen,video',
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'seccion' => 'required|in:banner,galeria,destacado',
            'orden' => 'required|integer|min:0',
            'estado' => 'required|in:activo,inactivo',
        ];

        // Validaciones condicionales según el tipo
        if ($request->tipo === 'imagen') {
            $rules['archivo'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
        } elseif ($request->tipo === 'video') {
            $rules['archivo_video'] = 'nullable|file|mimes:mp4,mov,avi,wmv,flv,webm|max:51200'; // Max 50MB
            $rules['url_video'] = 'nullable|url|max:500';
        }

        $validated = $request->validate($rules);

        try {
            $data = [
                'tipo' => $validated['tipo'],
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'] ?? null,
                'seccion' => $validated['seccion'],
                'orden' => $validated['orden'],
                'estado' => $validated['estado'],
            ];

            // Si es imagen y se subió una nueva
            if ($request->tipo === 'imagen' && $request->hasFile('archivo')) {
                // Eliminar imagen anterior
                if ($multimedia->archivo && Storage::disk('public')->exists($multimedia->archivo)) {
                    Storage::disk('public')->delete($multimedia->archivo);
                }

                $archivo = $request->file('archivo');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $ruta = $archivo->storeAs('multimedia/imagenes', $nombreArchivo, 'public');
                $data['archivo'] = $ruta;
            }

            // Si es video
            if ($request->tipo === 'video') {
                // Si subió un nuevo archivo de video
                if ($request->hasFile('archivo_video')) {
                    // Eliminar video anterior si existe
                    if ($multimedia->archivo_video && Storage::disk('public')->exists($multimedia->archivo_video)) {
                        Storage::disk('public')->delete($multimedia->archivo_video);
                    }

                    $archivoVideo = $request->file('archivo_video');
                    $nombreVideo = time() . '_' . $archivoVideo->getClientOriginalName();
                    $rutaVideo = $archivoVideo->storeAs('multimedia/videos', $nombreVideo, 'public');
                    $data['archivo_video'] = $rutaVideo;
                    $data['url_video'] = null; // Limpiar URL si hay archivo nuevo
                }
                // Si cambió la URL
                elseif ($request->filled('url_video')) {
                    $data['url_video'] = $validated['url_video'];
                    // Solo limpiar archivo_video si explícitamente cambió a URL
                    if ($multimedia->archivo_video) {
                        Storage::disk('public')->delete($multimedia->archivo_video);
                        $data['archivo_video'] = null;
                    }
                }

                $data['archivo'] = null; // Limpiar archivo de imagen si había
            }

            $multimedia->update($data);

            return redirect()->route('admin.multimedia.index')
                ->with('success', 'Contenido multimedia actualizado exitosamente');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar multimedia: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Eliminar multimedia
     */
    public function destroy($id)
    {
        try {
            $multimedia = MultimediaHomepage::findOrFail($id);

            // Eliminar archivo de imagen si existe
            if ($multimedia->archivo && Storage::disk('public')->exists($multimedia->archivo)) {
                Storage::disk('public')->delete($multimedia->archivo);
            }

            // Eliminar archivo de video si existe
            if ($multimedia->archivo_video && Storage::disk('public')->exists($multimedia->archivo_video)) {
                Storage::disk('public')->delete($multimedia->archivo_video);
            }

            $multimedia->delete();

            return redirect()->route('admin.multimedia.index')
                ->with('success', 'Contenido multimedia eliminado exitosamente');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar multimedia: ' . $e->getMessage()]);
        }
    }

    /**
     * Cambiar estado (activo/inactivo)
     */
    public function toggleEstado($id)
    {
        try {
            $multimedia = MultimediaHomepage::findOrFail($id);
            $multimedia->estado = $multimedia->estado === 'activo' ? 'inactivo' : 'activo';
            $multimedia->save();

            return redirect()->route('admin.multimedia.index')
                ->with('success', 'Estado actualizado exitosamente');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al cambiar estado: ' . $e->getMessage()]);
        }
    }
}
