 <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration
  {
      public function up(): void
      {
          Schema::create('imagen', function (Blueprint $table) {
              $table->id();
              $table->string('medida', 200)->nullable();
              $table->string('nombre', 200)->nullable();
              $table->string('foto', 200)->nullable();
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('imagen');
      }
  };