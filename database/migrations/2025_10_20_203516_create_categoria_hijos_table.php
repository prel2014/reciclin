 <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration
  {
      public function up(): void
      {
          Schema::create('categoria_hijo', function (Blueprint $table) {
              $table->id('cod_categoria_hijo');
              $table->unsignedBigInteger('cod_categoria_padre')->nullable();
              $table->string('nombre_categoria_hijo', 200)->nullable();
              $table->string('foto_categoria_hijo', 200)->nullable();

              $table->foreign('cod_categoria_padre')
                  ->references('cod_categoria')
                  ->on('categoria')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('categoria_hijo');
      }
  };