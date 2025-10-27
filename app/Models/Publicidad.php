<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  class Publicidad extends Model
  {
      protected $table = 'publicidad';
      protected $primaryKey = 'cod_publicidad';
      public $timestamps = false;

      protected $fillable = [
          'tipo',
          'url'
      ];
  }