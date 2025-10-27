@extends('profesor.layout')

@section('title', 'Catálogo de Productos')
@section('page-title', 'Catálogo de Útiles Escolares - Vista de Productos')

@section('content')
<style>
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .stat-card.green { border-left: 4px solid #10b981; }
    .stat-card.blue { border-left: 4px solid #3b82f6; }
    .stat-card.purple { border-left: 4px solid #8b5cf6; }
    .stat-card.orange { border-left: 4px solid #f59e0b; }

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

    .productos-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
    }

    .info-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .productos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .producto-card {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .producto-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #10b981;
    }

    .producto-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: #9ca3af;
    }

    .producto-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .producto-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .producto-nombre {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .producto-descripcion {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 15px;
        line-height: 1.5;
        flex: 1;
    }

    .producto-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #e5e7eb;
    }

    .producto-precio {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 1.3rem;
        font-weight: 800;
        color: #10b981;
    }

    .producto-stock {
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    .stock-disponible {
        background: #d1fae5;
        color: #065f46;
    }

    .stock-bajo {
        background: #fef3c7;
        color: #92400e;
    }

    .stock-agotado {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-status {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-activo {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactivo {
        background: #fee2e2;
        color: #991b1b;
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

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        color: #6b7280;
        background: #f9fafb;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: #10b981;
        color: white;
    }

    .pagination .active {
        background: #10b981;
        color: white;
    }

    @media (max-width: 768px) {
        .productos-grid {
            grid-template-columns: 1fr;
        }

        .stats-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<!-- Estadísticas -->
<div class="stats-cards">
    <div class="stat-card green">
        <div class="stat-icon">🎒</div>
        <div class="stat-label">Total de Productos</div>
        <div class="stat-value">{{ number_format($totalProductos) }}</div>
    </div>

    <div class="stat-card blue">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Productos Activos</div>
        <div class="stat-value">{{ number_format($productosActivos) }}</div>
    </div>

    <div class="stat-card purple">
        <div class="stat-icon">📦</div>
        <div class="stat-label">Con Stock Disponible</div>
        <div class="stat-value">{{ number_format($productosDisponibles) }}</div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon">📊</div>
        <div class="stat-label">Stock Total</div>
        <div class="stat-value">{{ number_format($stockTotal) }}</div>
    </div>
</div>

<!-- Catálogo de Productos -->
<div class="productos-container">
    <div class="header-section">
        <h2 class="page-title">🛍️ Catálogo de Productos</h2>
        <span class="info-badge">📖 Solo lectura</span>
    </div>

    @if($productos->count() > 0)
        <div class="productos-grid">
            @foreach($productos as $producto)
                <div class="producto-card">
                    <div class="producto-image" style="position: relative;">
                        @if($producto->foto)
                            <img src="{{ asset('storage/' . $producto->foto) }}" alt="{{ $producto->nombre }}">
                        @else
                            🎒
                        @endif
                        <span class="badge-status badge-{{ $producto->status === 'activo' ? 'activo' : 'inactivo' }}">
                            {{ $producto->status }}
                        </span>
                    </div>

                    <div class="producto-body">
                        <h3 class="producto-nombre">{{ $producto->nombre }}</h3>
                        <p class="producto-descripcion">
                            {{ Str::limit($producto->descripcion, 100) }}
                        </p>

                        <div class="producto-footer">
                            <div class="producto-precio">
                                <span>⭐</span>
                                <span>{{ number_format($producto->precio) }}</span>
                            </div>

                            <div class="producto-stock
                                @if($producto->disponibilidad > 20) stock-disponible
                                @elseif($producto->disponibilidad > 0) stock-bajo
                                @else stock-agotado
                                @endif">
                                @if($producto->disponibilidad > 0)
                                    {{ $producto->disponibilidad }} disponibles
                                @else
                                    Agotado
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="pagination">
            {{ $productos->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">🎒</div>
            <h3>No hay productos registrados</h3>
            <p>Los productos aparecerán aquí cuando el administrador los registre</p>
        </div>
    @endif
</div>

@endsection
