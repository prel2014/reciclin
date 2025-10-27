<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MultimediaHomepage;

class MultimediaHomepageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tabla antes de insertar
        MultimediaHomepage::truncate();

        $contenido = [
            // ============================================
            // BANNERS (3 items para el slider principal)
            // ============================================
            [
                'tipo' => 'video',
                'titulo' => 'Bienvenidos a Reciclin',
                'descripcion' => 'Únete a nuestra misión de crear un mundo más sostenible a través del reciclaje educativo',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'orden' => 0,
                'estado' => 'activo',
                'seccion' => 'banner',
            ],
            [
                'tipo' => 'video',
                'titulo' => 'Aprende a Reciclar Jugando',
                'descripcion' => 'Descubre cómo el reciclaje puede ser divertido y educativo para todos',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=8jPQjjsBbIc',
                'orden' => 1,
                'estado' => 'activo',
                'seccion' => 'banner',
            ],
            [
                'tipo' => 'video',
                'titulo' => 'El Poder de los Recipuntos',
                'descripcion' => 'Gana puntos, aprende y ayuda al planeta. ¡Tu participación hace la diferencia!',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                'orden' => 2,
                'estado' => 'activo',
                'seccion' => 'banner',
            ],

            // ============================================
            // GALERÍA (6 items variados)
            // ============================================
            [
                'tipo' => 'video',
                'titulo' => 'Cómo Funciona el Robot Tacho Inteligente',
                'descripcion' => 'Conoce la tecnología detrás de nuestro sistema de reciclaje automatizado',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=X_8Nh5XfRw0',
                'orden' => 0,
                'estado' => 'activo',
                'seccion' => 'galeria',
            ],
            [
                'tipo' => 'video',
                'titulo' => 'Estudiantes en Acción',
                'descripcion' => 'Mira cómo nuestros estudiantes están transformando el colegio con sus acciones ecológicas',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
                'orden' => 1,
                'estado' => 'activo',
                'seccion' => 'galeria',
            ],
            [
                'tipo' => 'video',
                'titulo' => 'La Importancia del Reciclaje',
                'descripcion' => 'Un viaje educativo sobre el impacto positivo del reciclaje en nuestro planeta',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=6jQ7y_qQYUA',
                'orden' => 2,
                'estado' => 'activo',
                'seccion' => 'galeria',
            ],
            [
                'tipo' => 'video',
                'titulo' => 'Tutorial: Separación de Residuos',
                'descripcion' => 'Aprende la forma correcta de separar tus residuos para un reciclaje efectivo',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=VBp0QT-1bvg',
                'orden' => 3,
                'estado' => 'activo',
                'seccion' => 'galeria',
            ],
            [
                'tipo' => 'video',
                'titulo' => 'Ganadores del Mes',
                'descripcion' => 'Celebramos a nuestros estudiantes destacados y sus logros en reciclaje',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=Me-yrGQUAH8',
                'orden' => 4,
                'estado' => 'activo',
                'seccion' => 'galeria',
            ],
            [
                'tipo' => 'video',
                'titulo' => 'Eco-Proyectos Estudiantiles',
                'descripcion' => 'Proyectos creativos desarrollados por nuestros alumnos para cuidar el medio ambiente',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=Vn0BWqRpvjE',
                'orden' => 5,
                'estado' => 'activo',
                'seccion' => 'galeria',
            ],

            // ============================================
            // DESTACADOS (3 items especiales)
            // ============================================
            [
                'tipo' => 'video',
                'titulo' => 'El Impacto de Reciclin en Nuestra Comunidad',
                'descripcion' => 'Descubre cómo nuestro programa ha transformado la cultura del reciclaje en el colegio Enrique Pelach y Feliu',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=9bZkp7q19f0',
                'orden' => 0,
                'estado' => 'activo',
                'seccion' => 'destacado',
            ],
            [
                'tipo' => 'video',
                'titulo' => '¿Qué son los Recipuntos?',
                'descripcion' => 'El sistema de recompensas que motiva a los estudiantes a reciclar y cuidar el planeta mientras aprenden',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=PSh_Ufr_YFQ',
                'orden' => 1,
                'estado' => 'activo',
                'seccion' => 'destacado',
            ],
            [
                'tipo' => 'video',
                'titulo' => 'Canjea tus Recipuntos en la Recitienda',
                'descripcion' => 'Conoce los útiles escolares y premios que puedes obtener con tus Recipuntos acumulados',
                'archivo' => null,
                'url_video' => 'https://www.youtube.com/watch?v=v1PBptSDIh8',
                'orden' => 2,
                'estado' => 'activo',
                'seccion' => 'destacado',
            ],
        ];

        foreach ($contenido as $item) {
            MultimediaHomepage::create($item);
        }

        $this->command->info('✅ Contenido multimedia de prueba creado exitosamente:');
        $this->command->info('   📹 3 Banners para el slider principal');
        $this->command->info('   🎬 6 Videos para la galería');
        $this->command->info('   ⭐ 3 Videos destacados');
        $this->command->info('');
        $this->command->info('💡 Nota: Los videos usan URLs de YouTube de demostración.');
        $this->command->info('   Puedes reemplazarlos desde el panel de administración.');
    }
}
