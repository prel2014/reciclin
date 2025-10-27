<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    protected $primaryKey = 'cod_compra';
    public $timestamps = true;

    protected $fillable = [
        'cod_usuario',
        'cod_publicacion',
        'cantidad',
        'precio_v',
        'precio_t',
        'fecha',
        'status'
    ];

    protected $casts = [
        'cantidad' => 'double',
        'precio_v' => 'double',
        'precio_t' => 'double',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuario', 'cod_usuario');
    }

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'cod_publicacion', 'cod_util');
    }
}
