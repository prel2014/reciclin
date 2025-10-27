<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Publicacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CanjeController extends Controller
{
    /**
     * Listar canjes (Profesor: solo de sus alumnos, Admin: todos, Alumno: solo suyos)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $query = Compra::select('cod_compra', 'cod_usuario', 'cod_publicacion', 'cantidad', 'precio_v', 'precio_t', 'fecha', 'status', 'created_at')
                ->with([
                    'usuario:cod_usuario,nombre,apellido,nick',
                    'publicacion:cod_util,nombre,precio,foto'
                ]);

            // Filtrar según el tipo de usuario
            if ($user->tipo === 'profesor') {
                // Profesor: solo canjes de sus alumnos
                $misAlumnosIds = Usuario::where('cod_profesor', $user->cod_usuario)
                    ->where('tipo', 'alumno')
                    ->pluck('cod_usuario');

                $query->whereIn('cod_usuario', $misAlumnosIds);
            } elseif ($user->tipo === 'alumno') {
                // Alumno: solo sus propios canjes
                $query->where('cod_usuario', $user->cod_usuario);
            }
            // Admin: todos los canjes (sin filtro)

            $canjes = $query->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $canjes
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar canjes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear canje (Profesor para alumno, o Alumno para sí mismo)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'cod_usuario' => 'required|exists:usuario,cod_usuario',
                'cod_publicacion' => 'required|exists:utiles_escolares,cod_util',
                'cantidad' => 'required|numeric|min:1',
            ]);

            DB::beginTransaction();

            // Obtener alumno y producto
            $alumno = Usuario::findOrFail($validated['cod_usuario']);
            $producto = Publicacion::where('cod_util', $validated['cod_publicacion'])->firstOrFail();

            // Validaciones según tipo de usuario
            if ($user->tipo === 'alumno') {
                // Alumno solo puede canjear para sí mismo
                if ($alumno->cod_usuario !== $user->cod_usuario) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Solo puedes realizar canjes para ti mismo'
                    ], 403);
                }
            } elseif ($user->tipo === 'profesor') {
                // Profesor solo puede canjear para sus alumnos
                if ($alumno->cod_profesor !== $user->cod_usuario) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Solo puedes realizar canjes para tus alumnos'
                    ], 403);
                }
            }
            // Admin puede canjear para cualquiera

            // Validar que el usuario sea tipo alumno
            if ($alumno->tipo !== 'alumno') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario seleccionado no es un alumno'
                ], 422);
            }

            // Validar que el producto esté activo
            if ($producto->status !== 'activo') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'El producto seleccionado no está disponible'
                ], 422);
            }

            // Validar disponibilidad
            if ($producto->disponibilidad < $validated['cantidad']) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "No hay suficiente stock disponible. Disponible: {$producto->disponibilidad}"
                ], 422);
            }

            // Calcular precio total
            $precioTotal = $producto->precio * $validated['cantidad'];

            // Validar que el alumno tenga suficientes recipuntos
            if ($alumno->recipuntos < $precioTotal) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Recipuntos insuficientes. Tiene: {$alumno->recipuntos}, Necesita: {$precioTotal}"
                ], 422);
            }

            // Descontar recipuntos del alumno
            $recipuntosAnteriores = $alumno->recipuntos;
            $alumno->recipuntos -= $precioTotal;
            $alumno->save();

            // Reducir disponibilidad del producto
            $producto->disponibilidad -= $validated['cantidad'];
            $producto->save();

            // Crear registro de canje
            $canje = Compra::create([
                'cod_usuario' => $alumno->cod_usuario,
                'cod_publicacion' => $producto->cod_util,
                'cantidad' => $validated['cantidad'],
                'precio_v' => $producto->precio,
                'precio_t' => $precioTotal,
                'fecha' => now()->format('Y-m-d H:i:s'),
                'status' => 'completado',
            ]);

            // Cargar relaciones para la respuesta
            $canje->load(['usuario:cod_usuario,nombre,apellido,nick', 'publicacion:cod_util,nombre,precio,foto']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Canje realizado exitosamente. Se descontaron {$precioTotal} Recipuntos a {$alumno->nombre} {$alumno->apellido}",
                'data' => [
                    'canje' => [
                        'cod_compra' => $canje->cod_compra,
                        'cantidad' => $canje->cantidad,
                        'precio_unitario' => $canje->precio_v,
                        'precio_total' => $canje->precio_t,
                        'fecha' => $canje->fecha,
                        'status' => $canje->status,
                        'alumno' => [
                            'cod_usuario' => $canje->usuario->cod_usuario,
                            'nombre' => $canje->usuario->nombre,
                            'apellido' => $canje->usuario->apellido,
                            'nick' => $canje->usuario->nick,
                            'recipuntos_anteriores' => $recipuntosAnteriores,
                            'recipuntos_actuales' => $alumno->recipuntos,
                        ],
                        'producto' => [
                            'cod_util' => $canje->publicacion->cod_util,
                            'nombre' => $canje->publicacion->nombre,
                            'precio' => $canje->publicacion->precio,
                            'foto' => $canje->publicacion->foto,
                        ]
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
                'message' => 'Error al procesar el canje',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver detalle de un canje específico
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();

            $canje = Compra::with(['usuario:cod_usuario,nombre,apellido,nick,recipuntos', 'publicacion:cod_util,nombre,descripcion,precio,foto'])
                ->findOrFail($id);

            // Validar permisos según tipo de usuario
            if ($user->tipo === 'alumno') {
                // Alumno solo puede ver sus propios canjes
                if ($canje->cod_usuario !== $user->cod_usuario) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permiso para ver este canje'
                    ], 403);
                }
            } elseif ($user->tipo === 'profesor') {
                // Profesor solo puede ver canjes de sus alumnos
                $alumno = Usuario::find($canje->cod_usuario);
                if ($alumno && $alumno->cod_profesor !== $user->cod_usuario) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permiso para ver este canje'
                    ], 403);
                }
            }
            // Admin puede ver todo

            return response()->json([
                'success' => true,
                'data' => [
                    'canje' => $canje
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Canje no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el canje',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancelar/eliminar un canje (revertir recipuntos y stock)
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();

            DB::beginTransaction();

            $canje = Compra::with(['usuario', 'publicacion'])->findOrFail($id);

            // Validar permisos (solo profesor y admin pueden cancelar)
            if ($user->tipo === 'alumno') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Los alumnos no pueden cancelar canjes'
                ], 403);
            } elseif ($user->tipo === 'profesor') {
                // Profesor solo puede cancelar canjes de sus alumnos
                $alumno = $canje->usuario;
                if ($alumno && $alumno->cod_profesor !== $user->cod_usuario) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Solo puedes cancelar canjes de tus alumnos'
                    ], 403);
                }
            }
            // Admin puede cancelar cualquier canje

            // Verificar que existan las relaciones
            if (!$canje->usuario) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el alumno asociado al canje'
                ], 404);
            }

            if (!$canje->publicacion) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el producto asociado al canje'
                ], 404);
            }

            $alumno = $canje->usuario;
            $producto = $canje->publicacion;

            // Revertir recipuntos al alumno
            $recipuntosAnteriores = $alumno->recipuntos;
            $alumno->recipuntos += $canje->precio_t;
            $alumno->save();

            // Revertir disponibilidad del producto
            $producto->disponibilidad += $canje->cantidad;
            $producto->save();

            // Guardar información para el mensaje
            $recipuntosDevueltos = $canje->precio_t;
            $nombreAlumno = "{$alumno->nombre} {$alumno->apellido}";

            // Eliminar el canje
            $canje->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Canje cancelado exitosamente. Se devolvieron {$recipuntosDevueltos} Recipuntos a {$nombreAlumno}",
                'data' => [
                    'alumno' => [
                        'cod_usuario' => $alumno->cod_usuario,
                        'nombre' => $alumno->nombre,
                        'apellido' => $alumno->apellido,
                        'recipuntos_anteriores' => $recipuntosAnteriores,
                        'recipuntos_actuales' => $alumno->recipuntos,
                        'recipuntos_devueltos' => $recipuntosDevueltos
                    ]
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Canje no encontrado'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar el canje',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estadísticas de canjes (según rol del usuario)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        try {
            $user = $request->user();

            $query = Compra::query();

            // Filtrar según tipo de usuario
            if ($user->tipo === 'profesor') {
                $misAlumnosIds = Usuario::where('cod_profesor', $user->cod_usuario)
                    ->where('tipo', 'alumno')
                    ->pluck('cod_usuario');
                $query->whereIn('cod_usuario', $misAlumnosIds);
            } elseif ($user->tipo === 'alumno') {
                $query->where('cod_usuario', $user->cod_usuario);
            }
            // Admin: todas las estadísticas

            $totalCanjes = (clone $query)->count();
            $canjesHoy = (clone $query)->whereDate('created_at', today())->count();
            $canjesMes = (clone $query)->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $totalRecipuntosCanjeados = (clone $query)->sum('precio_t');

            return response()->json([
                'success' => true,
                'data' => [
                    'total_canjes' => $totalCanjes,
                    'canjes_hoy' => $canjesHoy,
                    'canjes_mes' => $canjesMes,
                    'total_recipuntos_canjeados' => $totalRecipuntosCanjeados
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
