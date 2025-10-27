<?php

namespace Database\Seeders;

use App\Models\MaterialReciclable;
use Illuminate\Database\Seeder;

class MaterialReciclableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materiales = [
            [
                'nombre' => 'Botellas PET',
                'descripcion' => 'Botellas de plástico PET limpias y vacías - 10 pts por unidad',
                'recipuntos_por_cantidad' => 10,
            ],
            [
                'nombre' => 'Papel Blanco (kg)',
                'descripcion' => 'Papel bond blanco usado, sin grapas - 5 pts por kg',
                'recipuntos_por_cantidad' => 5,
            ],
            [
                'nombre' => 'Cartón (kg)',
                'descripcion' => 'Cartón limpio y seco, compactado - 8 pts por kg',
                'recipuntos_por_cantidad' => 8,
            ],
            [
                'nombre' => 'Latas de Aluminio',
                'descripcion' => 'Latas de bebidas de aluminio limpias - 15 pts por unidad',
                'recipuntos_por_cantidad' => 15,
            ],
            [
                'nombre' => 'Botellas de Vidrio',
                'descripcion' => 'Botellas de vidrio transparente sin etiquetas - 12 pts por unidad',
                'recipuntos_por_cantidad' => 12,
            ],
            [
                'nombre' => 'Periódicos y Revistas (kg)',
                'descripcion' => 'Periódicos y revistas usados en buen estado - 3 pts por kg',
                'recipuntos_por_cantidad' => 3,
            ],
        ];

        foreach ($materiales as $material) {
            MaterialReciclable::create($material);
        }

        $this->command->info('Se crearon ' . count($materiales) . ' materiales reciclables');
    }
}
