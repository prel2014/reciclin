<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FixAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔍 Buscando usuarios admin existentes...');

        // Buscar usuario admin existente
        $adminExistente = Usuario::where('nick', 'admin')->first();

        if ($adminExistente) {
            $this->command->info("✅ Usuario admin encontrado: {$adminExistente->correo}");

            // Actualizar el usuario existente
            $adminExistente->update([
                'tipo' => 'admin',
                'correo' => 'admin@recipuntos.com',
                'nombre' => 'Administrador',
                'apellido' => 'Sistema',
                'telefono' => '999999999',
                'estado' => 'activo',
            ]);

            // Actualizar contraseña manualmente para evitar el mutator
            DB::table('usuario')
                ->where('cod_usuario', $adminExistente->cod_usuario)
                ->update([
                    'contrasena' => Hash::make('admin123')
                ]);

            $this->command->info('✅ Usuario admin actualizado correctamente');
        } else {
            $this->command->info('❌ No se encontró usuario admin, creando uno nuevo...');

            // Crear nuevo usuario admin
            $admin = new Usuario();
            $admin->tipo = 'admin';
            $admin->nick = 'admin';
            $admin->correo = 'admin@recipuntos.com';
            $admin->nombre = 'Administrador';
            $admin->apellido = 'Sistema';
            $admin->telefono = '999999999';
            $admin->publicaciones = 0;
            $admin->recipuntos = 0;
            $admin->estado = 'activo';
            $admin->save();

            // Actualizar contraseña
            DB::table('usuario')
                ->where('cod_usuario', $admin->cod_usuario)
                ->update([
                    'contrasena' => Hash::make('admin123')
                ]);

            $this->command->info('✅ Usuario admin creado correctamente');
        }

        $this->command->info('');
        $this->command->info('📋 CREDENCIALES DE ADMIN:');
        $this->command->info('   Email: admin@recipuntos.com');
        $this->command->info('   Contraseña: admin123');
        $this->command->info('   Tipo: admin');
        $this->command->info('');
    }
}
