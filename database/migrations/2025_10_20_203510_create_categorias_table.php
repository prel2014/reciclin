<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration
  {
      public function up(): void
      {
          Schema::create('categoria', function (Blueprint $table) {
              $table->id('cod_categoria');
              $table->string('nombre_categoria', 200)->nullable();
              $table->string('foto_categoria', 200)->nullable();
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('categoria');
      }
  };