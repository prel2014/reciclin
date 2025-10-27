<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration
  {
      public function up(): void
      {
          Schema::create('publicacion', function (Blueprint $table) {
              $table->id('cod_publicacion');
              $table->unsignedBigInteger('cod_usuario')->nullable();
              $table->string('nombre', 200)->nullable();
              $table->unsignedBigInteger('categoria')->nullable();
              $table->unsignedBigInteger('sub_categoria')->nullable();
              $table->text('descripcion')->nullable();
              $table->double('disponibilidad')->nullable();
              $table->double('precio')->nullable();
              $table->string('moneda', 200)->nullable();
              $table->string('calidad', 200)->nullable();
              $table->string('region', 200)->nullable();
              $table->string('provincia', 200)->nullable();
              $table->string('distrito', 200)->nullable();
              $table->string('foto1', 250)->nullable();
              $table->string('foto2', 250)->nullable();
              $table->string('foto3', 250)->nullable();
              $table->string('fecha_p', 200)->nullable();
              $table->string('fecha_u', 200)->nullable();
              $table->string('status', 200)->default('activo');
              $table->double('vistas')->default(0);
              $table->timestamps();

              $table->foreign('cod_usuario')
                  ->references('cod_usuario')
                  ->on('usuario')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

              $table->foreign('categoria')
                  ->references('cod_categoria')
                  ->on('categoria')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

              $table->foreign('sub_categoria')
                  ->references('cod_categoria_hijo')
                  ->on('categoria_hijo')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('publicacion');
      }
  };