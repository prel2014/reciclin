<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class CategoriaHijo extends Model
  {
      protected $table = 'categoria_hijo';
      protected $primaryKey = 'cod_categoria_hijo';
      public $timestamps = false;

      protected $fillable = [
          'cod_categoria_padre',
          'nombre_categoria_hijo',
          'foto_categoria_hijo'
      ];

      // Relaciones
      public function categoriaPadre()
      {
          return $this->belongsTo(Categoria::class, 'cod_categoria_padre', 'cod_categoria');
      }

      public function publicaciones()
      {
          return $this->hasMany(Publicacion::class, 'sub_categoria', 'cod_categoria_hijo');
      }
  }