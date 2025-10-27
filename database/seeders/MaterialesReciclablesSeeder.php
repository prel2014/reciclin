<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialReciclable;

class MaterialesReciclablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materiales = [
            [
                'nombre' => 'Botella PET (Plástico)',
                'descripcion' => 'Botellas de plástico transparente (agua, gaseosas)',
                'recipuntos_por_cantidad' => 5,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Botella de Vidrio',
                'descripcion' => 'Botellas de vidrio de cualquier color',
                'recipuntos_por_cantidad' => 8,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Papel Blanco',
                'descripcion' => 'Hojas de papel bond, cuadernos usados (por paquete)',
                'recipuntos_por_cantidad' => 15,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Cartón',
                'descripcion' => 'Cajas de cartón aplastadas (por unidad)',
                'recipuntos_por_cantidad' => 10,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Latas de Aluminio',
                'descripcion' => 'Latas de bebidas (gaseosas, cerveza)',
                'recipuntos_por_cantidad' => 3,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Periódico/Revista',
                'descripcion' => 'Periódicos y revistas usados (por unidad)',
                'recipuntos_por_cantidad' => 12,
                'estado' => 'activo',
            ],
        ];

        foreach ($materiales as $material) {
            MaterialReciclable::create($material);
        }

        $this->command->info('✅ Materiales reciclables creados exitosamente:');
        $this->command->info('   • Botella PET: 5 recipuntos/cantidad');
        $this->command->info('   • Botella de Vidrio: 8 recipuntos/cantidad');
        $this->command->info('   • Papel Blanco: 15 recipuntos/cantidad');
        $this->command->info('   • Cartón: 10 recipuntos/cantidad');
        $this->command->info('   • Latas de Aluminio: 3 recipuntos/cantidad');
        $this->command->info('   • Periódico/Revista: 12 recipuntos/cantidad');
    }
}
