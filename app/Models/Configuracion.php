<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Configuracion extends Model
  {
      protected $table = 'configuracion';
      protected $primaryKey = 'cod_configuracion';
      public $timestamps = false;

      protected $fillable = [
          'precio'
      ];
  }