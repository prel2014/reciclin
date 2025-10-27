@extends('admin.layout')

@section('title', 'Gestión de Multimedia')
@section('page-title', 'Multimedia - Página Principal')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #667eea;
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #111827;
    }

    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filters {
        display: flex;
        gap: 10px;
        flex: 1;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 10px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.9rem;
        background: white;
        cursor: pointer;
    }

    .btn-primary {
        padding: 10px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .content-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .multimedia-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .multimedia-card {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
    }

    .multimedia-card:hover {
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    .multimedia-preview {
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .multimedia-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .multimedia-preview .video-icon {
        font-size: 4rem;
        color: #667eea;
    }

    .multimedia-info {
        padding: 15px;
    }

    .multimedia-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: #111827;
        margin-bottom: 8px;
    }

    .multimedia-description {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .multimedia-meta {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-imagen {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-video {
        background: #fce7f3;
        color: #9f1239;
    }

    .badge-activo {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactivo {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-banner {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-galeria {
        background: #e0e7ff;
        color: #3730a3;
    }

    .badge-destacado {
        background: #fce7f3;
        color: #831843;
    }

    .multimedia-actions {
        display: flex;
        gap: 8px;
        padding: 0 15px 15px;
    }

    .btn {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        flex: 1;
        justify-content: center;
    }

    .btn-edit {
        background: #fbbf24;
        color: #78350f;
    }

    .btn-edit:hover {
        background: #f59e0b;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .btn-toggle {
        background: #10b981;
        color: white;
    }

    .btn-toggle:hover {
        background: #059669;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
</style>

<!-- Estadísticas -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-label">Total Multimedia</div>
        <div class="stat-value">{{ number_format($totalMultimedia) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🖼️</div>
        <div class="stat-label">Imágenes</div>
        <div class="stat-value">{{ number_format($totalImagenes) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🎥</div>
        <div class="stat-label">Videos</div>
        <div class="stat-value">{{ number_format($totalVideos) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Activos</div>
        <div class="stat-value">{{ number_format($totalActivos) }}</div>
    </div>
</div>

<!-- Toolbar -->
<div class="toolbar">
    <form method="GET" class="filters">
        <select name="tipo" class="filter-select">
            <option value="">Todos los tipos</option>
            <option value="imagen" {{ request('tipo') == 'imagen' ? 'selected' : '' }}>Imágenes</option>
            <option value="video" {{ request('tipo') == 'video' ? 'selected' : '' }}>Videos</option>
        </select>

        <select name="seccion" class="filter-select">
            <option value="">Todas las secciones</option>
            <option value="banner" {{ request('seccion') == 'banner' ? 'selected' : '' }}>Banner</option>
            <option value="galeria" {{ request('seccion') == 'galeria' ? 'selected' : '' }}>Galería</option>
            <option value="destacado" {{ request('seccion') == 'destacado' ? 'selected' : '' }}>Destacado</option>
        </select>

        <select name="estado" class="filter-select">
            <option value="">Todos los estados</option>
            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>

        <button type="submit" class="btn-primary" style="padding: 10px 15px;">Filtrar</button>
    </form>

    <a href="{{ route('admin.multimedia.create') }}" class="btn-primary">
        + Nuevo Contenido
    </a>
</div>

<!-- Grid de Multimedia -->
<div class="content-container">
    @if($multimedia->count() > 0)
        <div class="multimedia-grid">
            @foreach($multimedia as $item)
                <div class="multimedia-card">
                    <div class="multimedia-preview">
                        @if($item->tipo === 'imagen')
                            <img src="{{ $item->archivo_url }}" alt="{{ $item->titulo }}">
                        @else
                            <div class="video-icon">🎥</div>
                        @endif
                    </div>
                    <div class="multimedia-info">
                        <h3 class="multimedia-title">{{ $item->titulo }}</h3>
                        @if($item->descripcion)
                            <p class="multimedia-description">{{ $item->descripcion }}</p>
                        @endif
                        <div class="multimedia-meta">
                            <span class="badge badge-{{ $item->tipo }}">
                                {{ $item->tipo === 'imagen' ? '🖼️ Imagen' : '🎥 Video' }}
                            </span>
                            <span class="badge badge-{{ $item->estado }}">
                                {{ ucfirst($item->estado) }}
                            </span>
                            <span class="badge badge-{{ $item->seccion }}">
                                {{ $item->seccion_nombre }}
                            </span>
                            <span class="badge" style="background: #f3f4f6; color: #374151;">
                                Orden: {{ $item->orden }}
                            </span>
                        </div>
                    </div>
                    <div class="multimedia-actions">
                        <a href="{{ route('admin.multimedia.edit', $item->cod_multimedia) }}" class="btn btn-edit">
                            ✏️ Editar
                        </a>
                        <form action="{{ route('admin.multimedia.destroy', $item->cod_multimedia) }}"
                              method="POST"
                              style="flex: 1;"
                              onsubmit="return confirm('¿Estás seguro de eliminar este contenido?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" style="width: 100%;">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div style="margin-top: 25px; display: flex; justify-content: center;">
            {{ $multimedia->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📸</div>
            <h3>No hay contenido multimedia</h3>
            <p>Agrega imágenes y videos para mostrar en la página principal</p>
            <a href="{{ route('admin.multimedia.create') }}" class="btn-primary" style="margin-top: 20px;">
                + Agregar Primer Contenido
            </a>
        </div>
    @endif
</div>

@endsection
