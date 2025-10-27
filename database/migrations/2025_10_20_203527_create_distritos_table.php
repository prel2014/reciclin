<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration
  {
      public function up(): void
      {
          Schema::create('distrito', function (Blueprint $table) {
              $table->id();
              $table->string('region', 200)->nullable();
              $table->string('provincia', 200)->nullable();
              $table->string('distrito', 200)->nullable();
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('distrito');
      }
  };