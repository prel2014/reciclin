<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publicacion;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Cuaderno Cuadriculado A4',
                'descripcion' => 'Cuaderno universitario de 100 hojas, cuadriculado',
                'precio' => 50,
                'disponibilidad' => 20,
                'foto' => null,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Lapiceros Azul x 12',
                'descripcion' => 'Caja de 12 lapiceros de tinta azul',
                'precio' => 30,
                'disponibilidad' => 15,
                'foto' => null,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Borrador Blanco',
                'descripcion' => 'Borrador blanco de alta calidad',
                'precio' => 10,
                'disponibilidad' => 50,
                'foto' => null,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Lápiz 2B x 6',
                'descripcion' => 'Paquete de 6 lápices 2B',
                'precio' => 25,
                'disponibilidad' => 30,
                'foto' => null,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Mochila Escolar Verde',
                'descripcion' => 'Mochila resistente con compartimentos',
                'precio' => 200,
                'disponibilidad' => 5,
                'foto' => null,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Regla de 30cm',
                'descripcion' => 'Regla transparente de plástico',
                'precio' => 15,
                'disponibilidad' => 25,
                'foto' => null,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Tijeras Escolares',
                'descripcion' => 'Tijeras de punta roma para niños',
                'precio' => 20,
                'disponibilidad' => 18,
                'foto' => null,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Colores x 12',
                'descripcion' => 'Caja de 12 colores de madera',
                'precio' => 35,
                'disponibilidad' => 22,
                'foto' => null,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Pegamento en Barra',
                'descripcion' => 'Pegamento en barra 40g',
                'precio' => 12,
                'disponibilidad' => 40,
                'foto' => null,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Botellas de Agua Reutilizables',
                'descripcion' => 'Botella de agua ecológica 500ml',
                'precio' => 45,
                'disponibilidad' => 12,
                'foto' => null,
                'status' => 'activo',
            ],
        ];

        foreach ($productos as $producto) {
            Publicacion::create($producto);
        }

        $this->command->info('Se crearon ' . count($productos) . ' productos escolares');
    }
}
