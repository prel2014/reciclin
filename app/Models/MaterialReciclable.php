<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialReciclable extends Model
{
    protected $table = 'materiales_reciclables';
    protected $primaryKey = 'cod_material';

    protected $fillable = [
        'nombre',
        'descripcion',
        'recipuntos_por_cantidad',
        'imagen',
        'estado'
    ];

    protected $casts = [
        'recipuntos_por_cantidad' => 'double',
    ];

    // Relación: registros de reciclaje
    public function registros()
    {
        return $this->hasMany(RegistroReciclaje::class, 'cod_material', 'cod_material');
    }
}
