<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuariosRecipuntosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Verificar si ya existe el admin
        $this->command->info('Verificando usuarios existentes...');

        // 2. Crear o encontrar Profesor 1
        $profesor1 = Usuario::firstOrCreate(
            ['correo' => 'garcia@recipuntos.com'],
            [
            'tipo' => 'profesor',
            'nick' => 'prof.garcia',
            'correo' => 'garcia@recipuntos.com',
            'contrasena' => Hash::make('profesor123'),
            'nombre' => 'María',
            'apellido' => 'García López',
            'telefono' => '987654321',
            'estado' => 'activo',
            'recipuntos' => 0,
        ]);

        // 3. Crear o encontrar Profesor 2
        $profesor2 = Usuario::firstOrCreate(
            ['correo' => 'rodriguez@recipuntos.com'],
            [
            'tipo' => 'profesor',
            'nick' => 'prof.rodriguez',
            'correo' => 'rodriguez@recipuntos.com',
            'contrasena' => Hash::make('profesor123'),
            'nombre' => 'Carlos',
            'apellido' => 'Rodríguez Pérez',
            'telefono' => '987654322',
            'estado' => 'activo',
            'recipuntos' => 0,
        ]);

        // 4. Crear o encontrar Alumnos del Profesor 1
        Usuario::firstOrCreate(
            ['correo' => 'juan.perez@alumno.com'],
            [
            'tipo' => 'alumno',
            'nick' => 'juan.perez',
            'contrasena' => Hash::make('alumno123'),
            'nombre' => 'Juan',
            'apellido' => 'Pérez Sánchez',
            'telefono' => '912345671',
            'estado' => 'activo',
            'recipuntos' => 150,
            'cod_profesor' => $profesor1->cod_usuario,
        ]);

        Usuario::firstOrCreate(
            ['correo' => 'ana.martinez@alumno.com'],
            [
            'tipo' => 'alumno',
            'nick' => 'ana.martinez',
            'contrasena' => Hash::make('alumno123'),
            'nombre' => 'Ana',
            'apellido' => 'Martínez Torres',
            'telefono' => '912345672',
            'estado' => 'activo',
            'recipuntos' => 230,
            'cod_profesor' => $profesor1->cod_usuario,
        ]);

        // 5. Crear o encontrar Alumnos del Profesor 2
        Usuario::firstOrCreate(
            ['correo' => 'pedro.gonzalez@alumno.com'],
            [
            'tipo' => 'alumno',
            'nick' => 'pedro.gonzalez',
            'contrasena' => Hash::make('alumno123'),
            'nombre' => 'Pedro',
            'apellido' => 'González Ruiz',
            'telefono' => '912345673',
            'estado' => 'activo',
            'recipuntos' => 180,
            'cod_profesor' => $profesor2->cod_usuario,
        ]);

        Usuario::firstOrCreate(
            ['correo' => 'lucia.fernandez@alumno.com'],
            [
            'tipo' => 'alumno',
            'nick' => 'lucia.fernandez',
            'contrasena' => Hash::make('alumno123'),
            'nombre' => 'Lucía',
            'apellido' => 'Fernández Díaz',
            'telefono' => '912345674',
            'estado' => 'activo',
            'recipuntos' => 320,
            'cod_profesor' => $profesor2->cod_usuario,
        ]);

        $this->command->info('');
        $this->command->info('✅ Usuarios de prueba creados/verificados exitosamente:');
        $this->command->info('');
        $this->command->info('   👨‍🏫 PROFESORES:');
        $this->command->info('   • garcia@recipuntos.com / profesor123 (Prof. María García)');
        $this->command->info('   • rodriguez@recipuntos.com / profesor123 (Prof. Carlos Rodríguez)');
        $this->command->info('');
        $this->command->info('   👨‍🎓 ALUMNOS:');
        $this->command->info('   • juan.perez@alumno.com / alumno123 (Juan Pérez - 150 pts)');
        $this->command->info('   • ana.martinez@alumno.com / alumno123 (Ana Martínez - 230 pts)');
        $this->command->info('   • pedro.gonzalez@alumno.com / alumno123 (Pedro González - 180 pts)');
        $this->command->info('   • lucia.fernandez@alumno.com / alumno123 (Lucía Fernández - 320 pts)');
        $this->command->info('');
    }
}
