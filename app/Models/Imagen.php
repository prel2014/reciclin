<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Imagen extends Model
  {
      protected $table = 'imagen';
      public $timestamps = false;

      protected $fillable = [
          'medida',
          'nombre',
          'foto'
      ];
  }