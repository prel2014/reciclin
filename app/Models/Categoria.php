<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Categoria extends Model
  {
      protected $table = 'categoria';
      protected $primaryKey = 'cod_categoria';
      public $timestamps = false;

      protected $fillable = [
          'nombre_categoria',
          'foto_categoria'
      ];

      // Relaciones
      public function categoriasHijas()
      {
          return $this->hasMany(CategoriaHijo::class, 'cod_categoria_padre', 'cod_categoria');
      }

      public function publicaciones()
      {
          return $this->hasMany(Publicacion::class, 'categoria', 'cod_categoria');
      }
  }