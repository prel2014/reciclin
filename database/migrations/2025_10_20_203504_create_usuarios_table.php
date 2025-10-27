 <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration
  {
      public function up(): void
      {
          Schema::create('usuario', function (Blueprint $table) {
              $table->id('cod_usuario');
              $table->string('tipo', 200)->nullable();
              $table->string('nick', 200)->unique();
              $table->string('correo', 200)->unique();
              $table->string('contrasena', 200)->nullable();
              $table->string('nombre', 200)->nullable();
              $table->string('apellido', 250)->nullable();
              $table->string('telefono', 200)->nullable();
              $table->double('publicaciones')->default(0);
              $table->string('estado', 200)->default('activo');
              $table->rememberToken();
              $table->timestamp('email_verified_at')->nullable();
              $table->timestamps();
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('usuario');
      }
  };