<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoCanjeController extends Controller
{
    /**
     * Ver historial completo de canjes
     */
    public function index(Request $request)
    {
        $alumno = Auth::user();

        // Filtros
        $estado = $request->get('estado');
        $desde = $request->get('desde');
        $hasta = $request->get('hasta');

        // Query base
        $query = Compra::where('cod_usuario', $alumno->cod_usuario)
            ->with('publicacion');

        // Filtrar por estado
        if ($estado && $estado != 'todos') {
            $query->where('status', $estado);
        }

        // Filtrar por fecha
        if ($desde) {
            $query->whereDate('created_at', '>=', $desde);
        }
        if ($hasta) {
            $query->whereDate('created_at', '<=', $hasta);
        }

        $canjes = $query->orderBy('created_at', 'desc')->paginate(15);

        // Estadísticas
        $totalCanjes = Compra::where('cod_usuario', $alumno->cod_usuario)->count();
        $totalGastado = Compra::where('cod_usuario', $alumno->cod_usuario)->sum('precio_t');
        $canjesPendientes = Compra::where('cod_usuario', $alumno->cod_usuario)
            ->where('status', 'pendiente')
            ->count();
        $canjesEntregados = Compra::where('cod_usuario', $alumno->cod_usuario)
            ->where('status', 'entregado')
            ->count();

        return view('alumno.canjes.index', compact(
            'canjes',
            'alumno',
            'totalCanjes',
            'totalGastado',
            'canjesPendientes',
            'canjesEntregados',
            'estado',
            'desde',
            'hasta'
        ));
    }
}
