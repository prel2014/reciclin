<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre_categoria' => 'Plástico', 'foto_categoria' => null],
            ['nombre_categoria' => 'Papel y Cartón', 'foto_categoria' => null],
            ['nombre_categoria' => 'Vidrio', 'foto_categoria' => null],
            ['nombre_categoria' => 'Metal', 'foto_categoria' => null],
            ['nombre_categoria' => 'Electrónicos', 'foto_categoria' => null],
            ['nombre_categoria' => 'Textiles', 'foto_categoria' => null],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
