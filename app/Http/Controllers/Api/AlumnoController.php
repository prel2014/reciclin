<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\MaterialReciclable;
use App\Models\Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AlumnoController extends Controller
{
    /**
     * Registrar un nuevo alumno (solo profesores autenticados)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $profesor = $request->user();

            // Validar que el usuario autenticado sea un profesor
            if ($profesor->tipo !== 'profesor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo profesores pueden registrar alumnos'
                ], 403);
            }

            $validated = $request->validate([
                'nick' => 'required|string|max:200|unique:usuario,nick',
                'correo' => 'required|email|max:200|unique:usuario,correo',
                'contrasena' => 'required|string|min:6',
                'nombre' => 'required|string|max:200',
                'apellido' => 'required|string|max:250',
                'telefono' => 'nullable|string|max:200',
            ]);

            // Crear el alumno con cod_profesor auto-asignado del profesor autenticado
            $alumno = Usuario::create([
                'tipo' => 'alumno',
                'cod_profesor' => $profesor->cod_usuario,  // Auto-asignado desde el usuario autenticado
                'nick' => $validated['nick'],
                'correo' => $validated['correo'],
                'contrasena' => $validated['contrasena'],  // El mutator hará el hash
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'telefono' => $validated['telefono'] ?? null,
                'publicaciones' => 0,
                'recipuntos' => 0,
                'estado' => 'activo',
            ]);

            return response()->json([
                'success' => true,
                'message' => "Alumno {$validated['nombre']} {$validated['apellido']} creado exitosamente",
                'data' => [
                    'alumno' => [
                        'cod_usuario' => $alumno->cod_usuario,
                        'tipo' => $alumno->tipo,
                        'nick' => $alumno->nick,
                        'correo' => $alumno->correo,
                        'nombre' => $alumno->nombre,
                        'apellido' => $alumno->apellido,
                        'telefono' => $alumno->telefono,
                        'recipuntos' => $alumno->recipuntos,
                        'estado' => $alumno->estado,
                        'cod_profesor' => $alumno->cod_profesor,
                    ]
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar alumno',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Asignar recipuntos por reciclaje (solo profesores autenticados)
     *
     * @param Request $request
     * @param int $id ID del alumno
     * @return \Illuminate\Http\JsonResponse
     */
    public function asignarRecipuntos(Request $request, $id)
    {
        try {
            $profesor = $request->user();

            // Validar que el usuario autenticado sea un profesor
            if ($profesor->tipo !== 'profesor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo profesores pueden asignar recipuntos'
                ], 403);
            }

            $validated = $request->validate([
                'cod_material' => 'required|exists:materiales_reciclables,cod_material',
                'cantidad' => 'required|numeric|min:0.01',
                'descripcion' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            // Verificar que el alumno pertenece a este profesor
            $alumno = Usuario::where('cod_usuario', $id)
                ->where('cod_profesor', $profesor->cod_usuario)
                ->where('tipo', 'alumno')
                ->first();

            if (!$alumno) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Alumno no encontrado o no pertenece a este profesor'
                ], 404);
            }

            // Obtener el material
            $material = MaterialReciclable::findOrFail($validated['cod_material']);

            // Calcular recipuntos a asignar
            $recipuntosAsignados = $validated['cantidad'] * $material->recipuntos_por_cantidad;

            // Guardar recipuntos anteriores
            $recipuntosAnteriores = $alumno->recipuntos;

            // Asignar recipuntos al alumno
            $alumno->recipuntos += $recipuntosAsignados;
            $alumno->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Se asignaron {$recipuntosAsignados} Recipuntos a {$alumno->nombre} {$alumno->apellido} por reciclar {$validated['cantidad']} unidades de {$material->nombre}",
                'data' => [
                    'alumno' => [
                        'cod_usuario' => $alumno->cod_usuario,
                        'nombre' => $alumno->nombre,
                        'apellido' => $alumno->apellido,
                        'recipuntos_anteriores' => $recipuntosAnteriores,
                        'recipuntos_asignados' => $recipuntosAsignados,
                        'recipuntos_actuales' => $alumno->recipuntos,
                    ],
                    'material' => [
                        'cod_material' => $material->cod_material,
                        'nombre' => $material->nombre,
                        'cantidad' => $validated['cantidad'],
                        'recipuntos_por_cantidad' => $material->recipuntos_por_cantidad,
                    ]
                ]
            ], 200);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar recipuntos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar examen y asignar recipuntos por notas (solo profesores autenticados)
     *
     * @param Request $request
     * @param int $id ID del alumno
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrarExamen(Request $request, $id)
    {
        try {
            $profesor = $request->user();

            // Validar que el usuario autenticado sea un profesor
            if ($profesor->tipo !== 'profesor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo profesores pueden registrar exámenes'
                ], 403);
            }

            $validated = $request->validate([
                'tipo_examen' => 'required|in:comunicacion,matematica,general',
                'preguntas_correctas' => 'required|integer|min:0|max:20',
                'fecha_examen' => 'required|date',
                'observaciones' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            // Verificar que el alumno pertenece a este profesor
            $alumno = Usuario::where('cod_usuario', $id)
                ->where('cod_profesor', $profesor->cod_usuario)
                ->where('tipo', 'alumno')
                ->first();

            if (!$alumno) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Alumno no encontrado o no pertenece a este profesor'
                ], 404);
            }

            // Calcular recipuntos (1 pregunta correcta = 1 recipunto)
            $recipuntosObtenidos = $validated['preguntas_correctas'];

            // Guardar recipuntos anteriores
            $recipuntosAnteriores = $alumno->recipuntos;

            // Asignar recipuntos al alumno
            $alumno->recipuntos += $recipuntosObtenidos;
            $alumno->save();

            // Registrar el examen
            $examen = Examen::create([
                'cod_alumno' => $alumno->cod_usuario,
                'cod_profesor' => $profesor->cod_usuario,
                'tipo_examen' => $validated['tipo_examen'],
                'preguntas_correctas' => $validated['preguntas_correctas'],
                'recipuntos_obtenidos' => $recipuntosObtenidos,
                'fecha_examen' => $validated['fecha_examen'],
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            DB::commit();

            $nombresExamenes = [
                'comunicacion' => 'Comunicación',
                'matematica' => 'Matemática',
                'general' => 'Conocimientos Generales',
            ];

            return response()->json([
                'success' => true,
                'message' => "Examen de {$nombresExamenes[$validated['tipo_examen']]} registrado. Se asignaron {$recipuntosObtenidos} Recipuntos a {$alumno->nombre} {$alumno->apellido} ({$validated['preguntas_correctas']}/20 correctas)",
                'data' => [
                    'examen' => [
                        'cod_examen' => $examen->cod_examen,
                        'tipo_examen' => $examen->tipo_examen,
                        'tipo_examen_nombre' => $nombresExamenes[$validated['tipo_examen']],
                        'preguntas_correctas' => $examen->preguntas_correctas,
                        'recipuntos_obtenidos' => $examen->recipuntos_obtenidos,
                        'fecha_examen' => $examen->fecha_examen,
                        'observaciones' => $examen->observaciones,
                    ],
                    'alumno' => [
                        'cod_usuario' => $alumno->cod_usuario,
                        'nombre' => $alumno->nombre,
                        'apellido' => $alumno->apellido,
                        'recipuntos_anteriores' => $recipuntosAnteriores,
                        'recipuntos_obtenidos' => $recipuntosObtenidos,
                        'recipuntos_actuales' => $alumno->recipuntos,
                    ]
                ]
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar examen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
