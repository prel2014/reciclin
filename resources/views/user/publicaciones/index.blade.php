@extends('user.layout')

@section('title', 'Mis Publicaciones')

@section('content')
<style>
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .btn-primary {
        padding: 12px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
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

    .publications-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .pub-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
    }

    .pub-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .pub-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        position: relative;
    }

    .pub-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pub-status {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-activo {
        background: #28a745;
        color: white;
    }

    .status-inactivo {
        background: #dc3545;
        color: white;
    }

    .pub-body {
        padding: 20px;
    }

    .pub-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pub-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .pub-price {
        font-size: 1.3rem;
        font-weight: 800;
        color: #667eea;
    }

    .pub-stock {
        font-size: 0.85rem;
        color: #888;
    }

    .pub-actions {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
        flex: 1;
        text-align: center;
    }

    .btn-view {
        background: #667eea;
        color: white;
    }

    .btn-edit {
        background: #ffc107;
        color: #333;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-sm:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 5rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .empty-message {
        color: #888;
        margin-bottom: 20px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 30px;
    }

    .pagination a,
    .pagination span {
        padding: 10px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .pagination .active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination a:hover {
        background: #f8f9fa;
        border-color: #667eea;
    }
</style>

<div class="content-card">
    <div class="header-actions">
        <h1 class="page-title">Mis Publicaciones</h1>
        <a href="{{ route('user.publicaciones.create') }}" class="btn-primary">
            + Nueva Publicación
        </a>
    </div>

    @if($publicaciones->count() > 0)
        <div class="publications-grid">
            @foreach($publicaciones as $pub)
                <div class="pub-card">
                    <div class="pub-image">
                        @if($pub->foto1)
                            <img src="{{ asset('storage/' . $pub->foto1) }}" alt="{{ $pub->nombre }}">
                        @else
                            ♻️
                        @endif
                        <span class="pub-status status-{{ $pub->status }}">
                            {{ ucfirst($pub->status) }}
                        </span>
                    </div>

                    <div class="pub-body">
                        <h3 class="pub-title">{{ $pub->nombre }}</h3>

                        <div class="pub-meta">
                            <div>
                                <div class="pub-price">S/ {{ number_format($pub->precio, 2) }}</div>
                                <div class="pub-stock">{{ $pub->disponibilidad }} disponibles</div>
                            </div>
                            <div style="text-align: right; font-size: 0.85rem; color: #888;">
                                <div>{{ $pub->vistas }} visitas</div>
                                <div>{{ $pub->created_at ? $pub->created_at->diffForHumans() : 'Hace poco' }}</div>
                            </div>
                        </div>

                        <div class="pub-actions">
                            <a href="{{ route('marketplace.show', $pub->cod_publicacion) }}"
                               class="btn-sm btn-view"
                               title="Ver">
                                👁️
                            </a>
                            <a href="{{ route('user.publicaciones.edit', $pub->cod_publicacion) }}"
                               class="btn-sm btn-edit"
                               title="Editar">
                                ✏️
                            </a>
                            <form action="{{ route('user.publicaciones.destroy', $pub->cod_publicacion) }}"
                                  method="POST"
                                  style="flex: 1;"
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta publicación?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-delete" style="width: 100%;" title="Eliminar">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $publicaciones->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📝</div>
            <h2 class="empty-title">No tienes publicaciones</h2>
            <p class="empty-message">Comienza a publicar tus materiales reciclables para que otros puedan comprarlos</p>
            <a href="{{ route('user.publicaciones.create') }}" class="btn-primary">
                Crear Mi Primera Publicación
            </a>
        </div>
    @endif
</div>
@endsection
