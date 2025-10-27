<?php

namespace Database\Seeders;

use App\Models\Publicacion;
use App\Models\Usuario;
use App\Models\Categoria;
use Illuminate\Database\Seeder;

class PublicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // School supplies data for redemption with Recipuntos
        $utilesEscolares = [
            [
                'nombre' => 'Cuaderno Cuadriculado 100 hojas',
                'descripcion' => 'Cuaderno de 100 hojas cuadriculadas, tamaño A4. Ideal para matemáticas y ciencias.',
                'disponibilidad' => 50,
                'precio' => 80,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Pack de 12 Lápices HB',
                'descripcion' => 'Set de 12 lápices de grafito HB de excelente calidad para escritura.',
                'disponibilidad' => 100,
                'precio' => 50,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Mochila Escolar Resistente',
                'descripcion' => 'Mochila de tela resistente con múltiples compartimentos. Capacidad para libros y laptop.',
                'disponibilidad' => 20,
                'precio' => 500,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Set de Colores 24 unidades',
                'descripcion' => 'Caja de 24 colores de madera con colores vibrantes y duraderos.',
                'disponibilidad' => 60,
                'precio' => 120,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Regla Metálica 30cm',
                'descripcion' => 'Regla de aluminio de 30cm con medidas en centímetros y milímetros.',
                'disponibilidad' => 80,
                'precio' => 40,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Borrador Blanco de Goma',
                'descripcion' => 'Borrador de goma blanca que no deja residuos. Pack de 3 unidades.',
                'disponibilidad' => 150,
                'precio' => 25,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Tijeras Escolares Punta Roma',
                'descripcion' => 'Tijeras con punta roma para mayor seguridad. Ideal para niños.',
                'disponibilidad' => 45,
                'precio' => 60,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Pegamento en Barra 40g',
                'descripcion' => 'Pegamento en barra de 40g, no tóxico y lavable.',
                'disponibilidad' => 90,
                'precio' => 35,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Calculadora Científica',
                'descripcion' => 'Calculadora científica con funciones básicas y trigonométricas.',
                'disponibilidad' => 25,
                'precio' => 350,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Set de Plumones 12 colores',
                'descripcion' => 'Set de 12 plumones de colores con tinta lavable y punta fina.',
                'disponibilidad' => 70,
                'precio' => 90,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Archivador Tamaño Oficio',
                'descripcion' => 'Archivador de palanca para hojas tamaño oficio. Lomo ancho 7cm.',
                'disponibilidad' => 40,
                'precio' => 100,
                'status' => 'activo',
            ],
            [
                'nombre' => 'Lonchera Térmica',
                'descripcion' => 'Lonchera con aislamiento térmico, mantiene alimentos frescos.',
                'disponibilidad' => 30,
                'precio' => 280,
                'status' => 'activo',
            ],
        ];

        foreach ($utilesEscolares as $util) {
            Publicacion::create([
                'nombre' => $util['nombre'],
                'descripcion' => $util['descripcion'],
                'disponibilidad' => $util['disponibilidad'],
                'precio' => $util['precio'],
                'foto' => null,
                'status' => $util['status'],
            ]);
        }

        $this->command->info('Se crearon ' . count($utilesEscolares) . ' útiles escolares de prueba');
    }
}
