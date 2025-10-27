<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = 'examenes';
    protected $primaryKey = 'cod_examen';

    protected $fillable = [
        'cod_alumno',
        'cod_profesor',
        'tipo_examen',
        'preguntas_correctas',
        'recipuntos_obtenidos',
        'fecha_examen',
        'observaciones',
    ];

    protected $casts = [
        'fecha_examen' => 'date',
        'preguntas_correctas' => 'integer',
        'recipuntos_obtenidos' => 'integer',
    ];

    // Relaciones
    public function alumno()
    {
        return $this->belongsTo(Usuario::class, 'cod_alumno', 'cod_usuario');
    }

    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'cod_profesor', 'cod_usuario');
    }

    // Accessor para nombre del examen
    public function getNombreExamenAttribute()
    {
        $nombres = [
            'comunicacion' => 'Comunicación',
            'matematica' => 'Matemática',
            'general' => 'Conocimientos Generales',
        ];

        return $nombres[$this->tipo_examen] ?? $this->tipo_examen;
    }

    // Accessor para emoji del examen
    public function getEmojiExamenAttribute()
    {
        $emojis = [
            'comunicacion' => '📖',
            'matematica' => '🔢',
            'general' => '🌍',
        ];

        return $emojis[$this->tipo_examen] ?? '📝';
    }
}
