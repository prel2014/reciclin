<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlumnoRecipuntoController extends Controller
{
    /**
     * Ver historial de movimientos de recipuntos
     */
    public function historial(Request $request)
    {
        $alumno = Auth::user();

        // Obtener canjes (gastos) - ordenados por fecha
        $canjes = Compra::where('cod_usuario', $alumno->cod_usuario)
            ->with('publicacion')
            ->orderBy('created_at', 'desc')
            ->get();

        // Crear un array unificado de movimientos
        $movimientos = [];

        // Agregar canjes como movimientos negativos
        foreach ($canjes as $canje) {
            $movimientos[] = [
                'tipo' => 'gasto',
                'concepto' => 'Canje de producto',
                'producto' => $canje->publicacion->nombre ?? 'Producto eliminado',
                'cantidad' => $canje->cantidad ?? 1,
                'recipuntos' => -($canje->precio_t ?? 0),
                'fecha' => $canje->created_at,
                'estado' => $canje->status ?? 'pendiente',
            ];
        }

        // Nota: Los ingresos de recipuntos normalmente se registrarían en una tabla de historial
        // Por ahora solo mostramos los gastos (canjes)
        // En una implementación completa, tendrías una tabla 'historial_recipuntos' que registre:
        // - Asignaciones del profesor por reciclaje
        // - Asignaciones del profesor por exámenes
        // - Gastos por canjes

        // Ordenar todos los movimientos por fecha
        usort($movimientos, function($a, $b) {
            return $b['fecha'] <=> $a['fecha'];
        });

        // Paginación manual
        $perPage = 15;
        $page = $request->get('page', 1);
        $total = count($movimientos);
        $movimientosPaginados = array_slice($movimientos, ($page - 1) * $perPage, $perPage);

        // Estadísticas
        $recipuntosActuales = $alumno->recipuntos ?? 0;
        $totalGastado = Compra::where('cod_usuario', $alumno->cod_usuario)->sum('precio_t');
        $totalCanjes = Compra::where('cod_usuario', $alumno->cod_usuario)->count();

        // Estimación de recipuntos ganados (actual + gastado)
        $totalGanado = $recipuntosActuales + $totalGastado;

        return view('alumno.historial-puntos.index', compact(
            'alumno',
            'movimientosPaginados',
            'recipuntosActuales',
            'totalGastado',
            'totalGanado',
            'totalCanjes',
            'page',
            'total',
            'perPage'
        ));
    }
}
