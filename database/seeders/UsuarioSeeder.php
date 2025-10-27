<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ===== ADMINISTRADOR =====
        Usuario::create([
            'tipo' => 'administrador',
            'nick' => 'admin',
            'correo' => 'admin@recipuntos.com',
            'contrasena' => 'admin123',
            'nombre' => 'Administrador',
            'apellido' => 'Sistema',
            'telefono' => '999999999',
            'publicaciones' => 0,
            'recipuntos' => 0,
            'estado' => 'activo',
        ]);

        // ===== PROFESORES =====
        Usuario::create([
            'tipo' => 'profesor',
            'nick' => 'prof.garcia',
            'correo' => 'profesor@recipuntos.com',
            'contrasena' => 'profe123',
            'nombre' => 'Carlos',
            'apellido' => 'García',
            'telefono' => '987654321',
            'publicaciones' => 0,
            'recipuntos' => 0,
            'estado' => 'activo',
        ]);

        Usuario::create([
            'tipo' => 'profesor',
            'nick' => 'prof.lopez',
            'correo' => 'mlopez@recipuntos.com',
            'contrasena' => 'profe123',
            'nombre' => 'María',
            'apellido' => 'López',
            'telefono' => '987654322',
            'publicaciones' => 0,
            'recipuntos' => 0,
            'estado' => 'activo',
        ]);

        // ===== ALUMNOS =====
        Usuario::create([
            'tipo' => 'alumno',
            'nick' => 'juan.perez',
            'correo' => 'jperez@alumno.com',
            'contrasena' => 'alumno123',
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'telefono' => '912345678',
            'publicaciones' => 0,
            'recipuntos' => 250,
            'estado' => 'activo',
        ]);

        Usuario::create([
            'tipo' => 'alumno',
            'nick' => 'ana.martinez',
            'correo' => 'amartinez@alumno.com',
            'contrasena' => 'alumno123',
            'nombre' => 'Ana',
            'apellido' => 'Martínez',
            'telefono' => '912345679',
            'publicaciones' => 0,
            'recipuntos' => 180,
            'estado' => 'activo',
        ]);

        Usuario::create([
            'tipo' => 'alumno',
            'nick' => 'pedro.gomez',
            'correo' => 'pgomez@alumno.com',
            'contrasena' => 'alumno123',
            'nombre' => 'Pedro',
            'apellido' => 'Gómez',
            'telefono' => '912345680',
            'publicaciones' => 0,
            'recipuntos' => 320,
            'estado' => 'activo',
        ]);

        Usuario::create([
            'tipo' => 'alumno',
            'nick' => 'lucia.rodriguez',
            'correo' => 'lrodriguez@alumno.com',
            'contrasena' => 'alumno123',
            'nombre' => 'Lucía',
            'apellido' => 'Rodríguez',
            'telefono' => '912345681',
            'publicaciones' => 0,
            'recipuntos' => 150,
            'estado' => 'activo',
        ]);

        Usuario::create([
            'tipo' => 'alumno',
            'nick' => 'diego.sanchez',
            'correo' => 'dsanchez@alumno.com',
            'contrasena' => 'alumno123',
            'nombre' => 'Diego',
            'apellido' => 'Sánchez',
            'telefono' => '912345682',
            'publicaciones' => 0,
            'recipuntos' => 420,
            'estado' => 'activo',
        ]);

        Usuario::create([
            'tipo' => 'alumno',
            'nick' => 'sofia.torres',
            'correo' => 'storres@alumno.com',
            'contrasena' => 'alumno123',
            'nombre' => 'Sofía',
            'apellido' => 'Torres',
            'telefono' => '912345683',
            'publicaciones' => 0,
            'recipuntos' => 95,
            'estado' => 'activo',
        ]);
    }
}
