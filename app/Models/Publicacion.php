<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Publicacion extends Model
  {
      protected $table = 'utiles_escolares';
      protected $primaryKey = 'cod_util';

      protected $fillable = [
          'nombre',
          'descripcion',
          'disponibilidad',
          'precio',
          'foto',
          'status',
      ];

      protected $casts = [
          'disponibilidad' => 'double',
          'precio' => 'double',
      ];

      // Relaciones
      public function compras()
      {
          return $this->hasMany(Compra::class, 'cod_publicacion', 'cod_util');
      }

      public function canjes()
      {
          return $this->hasMany(Compra::class, 'cod_publicacion', 'cod_util');
      }
  }