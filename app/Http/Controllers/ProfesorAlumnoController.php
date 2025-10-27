<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\MaterialReciclable;
use App\Models\Examen;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfesorAlumnoController extends Controller
{
    /**
     * Mostrar lista de alumnos asignados al profesor
     */
    public function index()
    {
        $profesor = Auth::user();

        // Obtener alumnos asignados a este profesor con campos específicos
        $alumnos = Usuario::select('cod_usuario', 'nombre', 'apellido', 'nick', 'correo', 'recipuntos', 'estado')
            ->where('cod_profesor', $profesor->cod_usuario)
            ->where('tipo', 'alumno')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate(20);

        // Estadísticas optimizadas (usar query builder en lugar de ORM)
        $estadisticas = Usuario::where('cod_profesor', $profesor->cod_usuario)
            ->where('tipo', 'alumno')
            ->selectRaw('COUNT(*) as total, SUM(recipuntos) as total_recipuntos, MAX(recipuntos) as max_recipuntos')
            ->first();

        $totalAlumnos = $estadisticas->total ?? 0;
        $totalRecipuntos = $estadisticas->total_recipuntos ?? 0;
        $promedioRecipuntos = $totalAlumnos > 0 ? round($totalRecipuntos / $totalAlumnos, 2) : 0;

        // Alumno destacado solo si hay alumnos
        $alumnoDestacado = $totalAlumnos > 0
            ? Usuario::select('cod_usuario', 'nombre', 'apellido', 'recipuntos')
                ->where('cod_profesor', $profesor->cod_usuario)
                ->where('tipo', 'alumno')
                ->orderBy('recipuntos', 'desc')
                ->first()
            : null;

        return view('profesor.alumnos.index', compact(
            'alumnos',
            'totalAlumnos',
            'totalRecipuntos',
            'promedioRecipuntos',
            'alumnoDestacado'
        ));
    }

    /**
     * Mostrar formulario para asignar Recipuntos
     */
    public function asignarRecipuntosForm($id)
    {
        $profesor = Auth::user();

        // Verificar que el alumno pertenece a este profesor con campos específicos
        $alumno = Usuario::select('cod_usuario', 'nombre', 'apellido', 'nick', 'recipuntos')
            ->where('cod_usuario', $id)
            ->where('cod_profesor', $profesor->cod_usuario)
            ->where('tipo', 'alumno')
            ->firstOrFail();

        // Obtener materiales reciclables activos con campos específicos
        $materiales = MaterialReciclable::select('cod_material', 'nombre', 'descripcion', 'recipuntos_por_cantidad', 'estado')
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        return view('profesor.alumnos.asignar-recipuntos', compact('alumno', 'materiales'));
    }

    /**
     * Procesar asignación de Recipuntos
     */
    public function asignarRecipuntos(Request $request, $id)
    {
        $profesor = Auth::user();

        $validated = $request->validate([
            'cod_material' => 'required|exists:materiales_reciclables,cod_material',
            'cantidad' => 'required|numeric|min:0.01',
            'descripcion' => 'nullable|string|max:500',
        ], [
            'cod_material.required' => 'Debe seleccionar un material reciclable',
            'cod_material.exists' => 'El material seleccionado no existe',
            'cantidad.required' => 'Debe especificar la cantidad',
            'cantidad.numeric' => 'La cantidad debe ser un número',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
        ]);

        try {
            DB::beginTransaction();

            // Verificar que el alumno pertenece a este profesor
            $alumno = Usuario::where('cod_usuario', $id)
                ->where('cod_profesor', $profesor->cod_usuario)
                ->where('tipo', 'alumno')
                ->firstOrFail();

            // Obtener el material
            $material = MaterialReciclable::findOrFail($validated['cod_material']);

            // Calcular recipuntos a asignar (cantidad * precio por cantidad)
            $recipuntosAsignados = $validated['cantidad'] * $material->recipuntos_por_cantidad;

            // Asignar recipuntos al alumno
            $alumno->recipuntos += $recipuntosAsignados;
            $alumno->save();

            DB::commit();

            return redirect()->route('profesor.alumnos.index')
                ->with('success', "Se asignaron {$recipuntosAsignados} Recipuntos a {$alumno->nombre} {$alumno->apellido} por reciclar {$validated['cantidad']} unidades de {$material->nombre}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al asignar Recipuntos: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Mostrar formulario para crear nuevo alumno
     */
    public function create()
    {
        return view('profesor.alumnos.create');
    }

    /**
     * Guardar nuevo alumno
     */
    public function store(Request $request)
    {
        $profesor = Auth::user();

        $validated = $request->validate([
            'nick' => 'required|string|max:200|unique:usuario,nick',
            'correo' => 'required|email|max:200|unique:usuario,correo',
            'contrasena' => 'required|string|min:6',
            'nombre' => 'required|string|max:200',
            'apellido' => 'required|string|max:250',
            'telefono' => 'nullable|string|max:200',
        ], [
            'nick.required' => 'El nick es obligatorio',
            'nick.unique' => 'Este nick ya está en uso',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'correo.unique' => 'Este correo ya está registrado',
            'contrasena.required' => 'La contraseña es obligatoria',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
        ]);

        try {
            Usuario::create([
                'tipo' => 'alumno',
                'cod_profesor' => $profesor->cod_usuario,
                'nick' => $validated['nick'],
                'correo' => $validated['correo'],
                'contrasena' => $validated['contrasena'],
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'telefono' => $validated['telefono'] ?? null,
                'publicaciones' => 0,
                'recipuntos' => 0,
                'estado' => 'activo',
            ]);

            return redirect()->route('profesor.alumnos.index')
                ->with('success', "Alumno {$validated['nombre']} {$validated['apellido']} creado exitosamente");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear alumno: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Mostrar detalles de un alumno
     */
    public function show($id)
    {
        $profesor = Auth::user();

        $alumno = Usuario::select('cod_usuario', 'nombre', 'apellido', 'nick', 'correo', 'telefono', 'recipuntos', 'estado')
            ->where('cod_usuario', $id)
            ->where('cod_profesor', $profesor->cod_usuario)
            ->where('tipo', 'alumno')
            ->firstOrFail();

        // Obtener canjes del alumno con eager loading optimizado
        $canjes = Compra::select('cod_compra', 'cod_publicacion', 'cantidad', 'precio_t', 'fecha', 'status', 'created_at')
            ->where('cod_usuario', $id)
            ->with('publicacion:cod_util,nombre,precio,foto')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('profesor.alumnos.show', compact('alumno', 'canjes'));
    }

    /**
     * Mostrar formulario para registrar examen
     */
    public function registrarExamenForm($id)
    {
        $profesor = Auth::user();

        // Verificar que el alumno pertenece a este profesor
        $alumno = Usuario::where('cod_usuario', $id)
            ->where('cod_profesor', $profesor->cod_usuario)
            ->where('tipo', 'alumno')
            ->firstOrFail();

        return view('profesor.alumnos.registrar-examen', compact('alumno'));
    }

    /**
     * Procesar registro de examen
     */
    public function registrarExamen(Request $request, $id)
    {
        $profesor = Auth::user();

        $validated = $request->validate([
            'tipo_examen' => 'required|in:comunicacion,matematica,general',
            'preguntas_correctas' => 'required|integer|min:0|max:20',
            'fecha_examen' => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'tipo_examen.required' => 'Debe seleccionar el tipo de examen',
            'tipo_examen.in' => 'El tipo de examen no es válido',
            'preguntas_correctas.required' => 'Debe especificar el número de preguntas correctas',
            'preguntas_correctas.integer' => 'El número de preguntas debe ser un entero',
            'preguntas_correctas.min' => 'El mínimo es 0 preguntas correctas',
            'preguntas_correctas.max' => 'El máximo es 20 preguntas correctas',
            'fecha_examen.required' => 'Debe especificar la fecha del examen',
            'fecha_examen.date' => 'La fecha no es válida',
        ]);

        try {
            DB::beginTransaction();

            // Verificar que el alumno pertenece a este profesor
            $alumno = Usuario::where('cod_usuario', $id)
                ->where('cod_profesor', $profesor->cod_usuario)
                ->where('tipo', 'alumno')
                ->firstOrFail();

            // Calcular recipuntos (1 pregunta = 1 recipunto)
            $recipuntosObtenidos = $validated['preguntas_correctas'];

            // Asignar recipuntos al alumno
            $alumno->recipuntos += $recipuntosObtenidos;
            $alumno->save();

            // Registrar el examen
            Examen::create([
                'cod_alumno' => $alumno->cod_usuario,
                'cod_profesor' => $profesor->cod_usuario,
                'tipo_examen' => $validated['tipo_examen'],
                'preguntas_correctas' => $validated['preguntas_correctas'],
                'recipuntos_obtenidos' => $recipuntosObtenidos,
                'fecha_examen' => $validated['fecha_examen'],
                'observaciones' => $validated['observaciones'],
            ]);

            DB::commit();

            $nombreExamen = [
                'comunicacion' => 'Comunicación',
                'matematica' => 'Matemática',
                'general' => 'Conocimientos Generales',
            ][$validated['tipo_examen']];

            return redirect()->route('profesor.alumnos.index')
                ->with('success', "Examen de {$nombreExamen} registrado. Se asignaron {$recipuntosObtenidos} Recipuntos a {$alumno->nombre} {$alumno->apellido} ({$validated['preguntas_correctas']}/20 correctas)");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar examen: ' . $e->getMessage()])->withInput();
        }
    }
}
