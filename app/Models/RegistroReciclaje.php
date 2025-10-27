<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroReciclaje extends Model
{
    protected $table = 'registro_reciclaje';
    protected $primaryKey = 'cod_registro';

    protected $fillable = [
        'cod_alumno',
        'cod_material',
        'cod_profesor',
        'cantidad',
        'recipuntos_ganados',
        'observaciones',
        'fecha_registro'
    ];

    protected $casts = [
        'cantidad' => 'double',
        'recipuntos_ganados' => 'double',
        'fecha_registro' => 'date',
    ];

    // Relación: alumno que recicló
    public function alumno()
    {
        return $this->belongsTo(Usuario::class, 'cod_alumno', 'cod_usuario');
    }

    // Relación: material reciclado
    public function material()
    {
        return $this->belongsTo(MaterialReciclable::class, 'cod_material', 'cod_material');
    }

    // Relación: profesor que registró
    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'cod_profesor', 'cod_usuario');
    }
}
