<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultimediaHomepage extends Model
{
    protected $table = 'multimedia_homepage';
    protected $primaryKey = 'cod_multimedia';

    protected $fillable = [
        'tipo',
        'titulo',
        'descripcion',
        'archivo',
        'archivo_video',
        'url_video',
        'orden',
        'estado',
        'seccion',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    /**
     * Scope para obtener solo elementos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para ordenar por campo 'orden'
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Scope para filtrar por sección
     */
    public function scopeSeccion($query, $seccion)
    {
        return $query->where('seccion', $seccion);
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Accessor para obtener la URL completa del archivo (imagen)
     */
    public function getArchivoUrlAttribute()
    {
        if ($this->archivo) {
            return asset('storage/' . $this->archivo);
        }
        return null;
    }

    /**
     * Accessor para obtener la URL completa del archivo de video
     */
    public function getArchivoVideoUrlAttribute()
    {
        if ($this->archivo_video) {
            return asset('storage/' . $this->archivo_video);
        }
        return null;
    }

    /**
     * Accessor para verificar si tiene video local
     */
    public function getTieneVideoLocalAttribute()
    {
        return !empty($this->archivo_video);
    }

    /**
     * Accessor para verificar si usa URL de video externa
     */
    public function getUsaUrlExternaAttribute()
    {
        return !empty($this->url_video) && empty($this->archivo_video);
    }

    /**
     * Accessor para verificar si es una imagen
     */
    public function getEsImagenAttribute()
    {
        return $this->tipo === 'imagen';
    }

    /**
     * Accessor para verificar si es un video
     */
    public function getEsVideoAttribute()
    {
        return $this->tipo === 'video';
    }

    /**
     * Accessor para obtener el embed code de YouTube
     */
    public function getYoutubeEmbedUrlAttribute()
    {
        if (!$this->url_video) {
            return null;
        }

        // Extraer ID de YouTube de diferentes formatos
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $this->url_video, $matches);

        if (isset($matches[1])) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return $this->url_video;
    }

    /**
     * Accessor para nombre de la sección
     */
    public function getSeccionNombreAttribute()
    {
        $nombres = [
            'banner' => 'Banner Principal',
            'galeria' => 'Galería',
            'destacado' => 'Contenido Destacado',
        ];

        return $nombres[$this->seccion] ?? $this->seccion;
    }
}
