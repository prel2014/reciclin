<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $primaryKey = 'cod_pago';
    public $timestamps = false;

    protected $fillable = [
        'cod_publicacion',
        'precio_p',
        'fecha_pago'
    ];

    // Relaciones
    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'cod_publicacion', 'cod_publicacion');
    }
}
