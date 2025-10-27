<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Publicacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfesorCanjeController extends Controller
{
    /**
     * Mostrar lista de canjes realizados
     */
    public function index(Request $request)
    {
        $profesor = Auth::user();

        // Obtener IDs de los alumnos del profesor
        $misAlumnosIds = Usuario::where('cod_profesor', $profesor->cod_usuario)
            ->where('tipo', 'alumno')
            ->pluck('cod_usuario');

        // Filtros
        $filtroEstado = $request->get('estado', 'todos');

        // Query base
        $query = Compra::select('cod_compra', 'cod_usuario', 'cod_publicacion', 'cantidad', 'precio_t', 'fecha', 'status', 'created_at')
            ->whereIn('cod_usuario', $misAlumnosIds)
            ->with([
                'usuario:cod_usuario,nombre,apellido,nick',
                'publicacion:cod_util,nombre,precio,foto'
            ]);

        // Aplicar filtro de estado
        if ($filtroEstado !== 'todos') {
            $query->where('status', $filtroEstado);
        }

        // Obtener canjes con paginación
        $canjes = $query->orderBy('created_at', 'desc')->paginate(20);

        // Estadísticas - Optimizado para solo mis alumnos
        $totalCanjes = Compra::whereIn('cod_usuario', $misAlumnosIds)->count();
        $canjesPendientes = Compra::whereIn('cod_usuario', $misAlumnosIds)->where('status', 'pendiente')->count();
        $canjesProcesando = Compra::whereIn('cod_usuario', $misAlumnosIds)->where('status', 'procesando')->count();
        $canjesEntregados = Compra::whereIn('cod_usuario', $misAlumnosIds)->where('status', 'entregado')->count();
        $canjesHoy = Compra::whereIn('cod_usuario', $misAlumnosIds)
            ->whereDate('created_at', today())
            ->count();
        $totalRecipuntosCanjeados = Compra::whereIn('cod_usuario', $misAlumnosIds)
            ->sum('precio_t');

        return view('profesor.canjes.index', compact(
            'canjes',
            'totalCanjes',
            'canjesPendientes',
            'canjesProcesando',
            'canjesEntregados',
            'canjesHoy',
            'totalRecipuntosCanjeados',
            'filtroEstado'
        ));
    }

    /**
     * Mostrar formulario para realizar nuevo canje
     */
    public function create()
    {
        $profesor = Auth::user();

        // Obtener solo mis alumnos con campos específicos
        $alumnos = Usuario::select('cod_usuario', 'nombre', 'apellido', 'nick', 'recipuntos')
            ->where('cod_profesor', $profesor->cod_usuario)
            ->where('tipo', 'alumno')
            ->where('estado', 'activo')
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        // Obtener productos disponibles con campos específicos
        $productos = Publicacion::select('cod_util', 'nombre', 'descripcion', 'precio', 'disponibilidad', 'foto')
            ->where('status', 'activo')
            ->where('disponibilidad', '>', 0)
            ->orderBy('nombre')
            ->get();

        return view('profesor.canjes.create', compact('alumnos', 'productos'));
    }

    /**
     * Procesar nuevo canje
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cod_usuario' => 'required|exists:usuario,cod_usuario',
            'cod_publicacion' => 'required|exists:utiles_escolares,cod_util',
            'cantidad' => 'required|numeric|min:1',
        ], [
            'cod_usuario.required' => 'Debe seleccionar un alumno',
            'cod_usuario.exists' => 'El alumno seleccionado no existe',
            'cod_publicacion.required' => 'Debe seleccionar un producto',
            'cod_publicacion.exists' => 'El producto seleccionado no existe',
            'cantidad.required' => 'Debe especificar la cantidad',
            'cantidad.numeric' => 'La cantidad debe ser un número válido',
            'cantidad.min' => 'La cantidad debe ser al menos 1',
        ]);

        try {
            DB::beginTransaction();

            // Obtener alumno y producto usando sus primary keys correctas
            $alumno = Usuario::findOrFail($validated['cod_usuario']);
            $producto = Publicacion::where('cod_util', $validated['cod_publicacion'])->firstOrFail();

            // Validar que el alumno sea tipo alumno
            if ($alumno->tipo !== 'alumno') {
                DB::rollBack();
                return back()->withErrors(['cod_usuario' => 'El usuario seleccionado no es un alumno'])->withInput();
            }

            // Validar que el producto esté activo
            if ($producto->status !== 'activo') {
                DB::rollBack();
                return back()->withErrors(['cod_publicacion' => 'El producto seleccionado no está disponible'])->withInput();
            }

            // Validar disponibilidad
            if ($producto->disponibilidad < $validated['cantidad']) {
                DB::rollBack();
                return back()->withErrors([
                    'cantidad' => "No hay suficiente stock disponible. Disponible: {$producto->disponibilidad}"
                ])->withInput();
            }

            // Calcular precio total
            $precioTotal = $producto->precio * $validated['cantidad'];

            // Validar que el alumno tenga suficientes recipuntos
            if ($alumno->recipuntos < $precioTotal) {
                DB::rollBack();
                return back()->withErrors([
                    'cod_usuario' => "El alumno no tiene suficientes Recipuntos. Tiene: {$alumno->recipuntos}, Necesita: {$precioTotal}"
                ])->withInput();
            }

            // Descontar recipuntos del alumno
            $alumno->recipuntos -= $precioTotal;
            $alumno->save();

            // Reducir disponibilidad del producto
            $producto->disponibilidad -= $validated['cantidad'];
            $producto->save();

            // Crear registro de canje - usando cod_util del producto
            Compra::create([
                'cod_usuario' => $alumno->cod_usuario,
                'cod_publicacion' => $producto->cod_util,
                'cantidad' => $validated['cantidad'],
                'precio_v' => $producto->precio,
                'precio_t' => $precioTotal,
                'fecha' => now()->format('Y-m-d H:i:s'),
                'status' => 'completado',
            ]);

            DB::commit();

            return redirect()->route('profesor.canjes.index')
                ->with('success', "Canje realizado exitosamente. Se descontaron {$precioTotal} Recipuntos a {$alumno->nombre} {$alumno->apellido}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar el canje: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Ver detalles de un canje específico
     */
    public function show($id)
    {
        $canje = Compra::with(['usuario', 'publicacion'])->findOrFail($id);

        return view('profesor.canjes.show', compact('canje'));
    }

    /**
     * Actualizar estado de un canje
     */
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pendiente,procesando,entregado,cancelado',
        ]);

        try {
            $canje = Compra::with(['usuario', 'publicacion'])->findOrFail($id);

            $estadoAnterior = $canje->status;
            $nuevoEstado = $request->status;

            // Actualizar el estado
            $canje->status = $nuevoEstado;
            $canje->save();

            $mensaje = "Estado del canje actualizado de '{$estadoAnterior}' a '{$nuevoEstado}' exitosamente.";

            return back()->with('success', $mensaje);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'No se encontró el canje especificado']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar el estado: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancelar un canje (revertir)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $canje = Compra::with(['usuario', 'publicacion'])->findOrFail($id);

            // Verificar que existan las relaciones
            if (!$canje->usuario) {
                DB::rollBack();
                return back()->withErrors(['error' => 'No se encontró el alumno asociado al canje']);
            }

            if (!$canje->publicacion) {
                DB::rollBack();
                return back()->withErrors(['error' => 'No se encontró el producto asociado al canje']);
            }

            $alumno = $canje->usuario;
            $producto = $canje->publicacion;

            // Revertir recipuntos al alumno
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

            return redirect()->route('profesor.canjes.index')
                ->with('success', "Canje cancelado exitosamente. Se devolvieron {$recipuntosDevueltos} Recipuntos a {$nombreAlumno}");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'No se encontró el canje especificado']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al cancelar el canje: ' . $e->getMessage()]);
        }
    }

    /**
     * API: Buscar alumnos para autocompletado
     */
    public function buscarAlumnos(Request $request)
    {
        $query = $request->get('q', '');

        $profesor = Auth::user();

        $alumnos = Usuario::where('tipo', 'alumno')
            ->where('cod_profesor', $profesor->cod_usuario)
            ->where('estado', 'activo')
            ->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('apellido', 'LIKE', "%{$query}%")
                  ->orWhere('nick', 'LIKE', "%{$query}%")
                  ->orWhere(DB::raw("CONCAT(nombre, ' ', apellido)"), 'LIKE', "%{$query}%");
            })
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->limit(10)
            ->get(['cod_usuario', 'nombre', 'apellido', 'nick', 'recipuntos']);

        return response()->json([
            'results' => $alumnos->map(function($alumno) {
                return [
                    'id' => $alumno->cod_usuario,
                    'text' => "{$alumno->nombre} {$alumno->apellido} (@{$alumno->nick})",
                    'nombre' => $alumno->nombre,
                    'apellido' => $alumno->apellido,
                    'nick' => $alumno->nick,
                    'recipuntos' => $alumno->recipuntos,
                ];
            })
        ]);
    }

    /**
     * API: Buscar productos para autocompletado
     */
    public function buscarProductos(Request $request)
    {
        $query = $request->get('q', '');

        $productos = Publicacion::where('status', 'activo')
            ->where('disponibilidad', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('descripcion', 'LIKE', "%{$query}%");
            })
            ->orderBy('nombre')
            ->limit(10)
            ->get(['cod_util', 'nombre', 'descripcion', 'precio', 'disponibilidad', 'foto']);

        return response()->json([
            'results' => $productos->map(function($producto) {
                return [
                    'id' => $producto->cod_util,
                    'text' => "{$producto->nombre} - ⭐ {$producto->precio} pts",
                    'nombre' => $producto->nombre,
                    'precio' => $producto->precio,
                    'disponibilidad' => $producto->disponibilidad,
                    'foto' => $producto->foto,
                ];
            })
        ]);
    }
}
