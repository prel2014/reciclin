<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoRankingController extends Controller
{
    /**
     * Ver el ranking completo de alumnos
     */
    public function index()
    {
        $alumno = Auth::user();

        // Obtener todos los alumnos ordenados por recipuntos
        $alumnos = Usuario::where('tipo', 'alumno')
            ->with('profesor')
            ->orderBy('recipuntos', 'desc')
            ->get();

        // Encontrar mi posición
        $miPosicion = $alumnos->search(function($user) use ($alumno) {
            return $user->cod_usuario === $alumno->cod_usuario;
        }) + 1;

        // Estadísticas
        $totalAlumnos = $alumnos->count();
        $promedioRecipuntos = round($alumnos->avg('recipuntos') ?? 0);

        // Top 3
        $top3 = $alumnos->take(3);

        // Alumnos cercanos a mi posición (3 antes y 3 después)
        $cercanos = collect();
        if ($miPosicion > 1) {
            $inicio = max(0, $miPosicion - 4);
            $fin = min($totalAlumnos, $miPosicion + 3);
            $cercanos = $alumnos->slice($inicio, $fin - $inicio);
        }

        return view('alumno.ranking.index', compact(
            'alumnos',
            'alumno',
            'miPosicion',
            'totalAlumnos',
            'promedioRecipuntos',
            'top3',
            'cercanos'
        ));
    }
}
