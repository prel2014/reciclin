@extends('alumno.layout')

@section('title', 'Historial de Recipuntos')
@section('page-title', 'Historial de Recipuntos')

@section('content')
<style>
    .resumen-banner {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(16, 185, 129, 0.3);
    }

    .resumen-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .resumen-item {
        text-align: center;
        padding: 20px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .resumen-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .resumen-value {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 8px;
        line-height: 1;
    }

    .resumen-label {
        font-size: 0.9rem;
        opacity: 0.9;
        font-weight: 600;
    }

    .info-card {
        background: #e0f2fe;
        border: 2px solid #0ea5e9;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .info-icon {
        font-size: 2rem;
        flex-shrink: 0;
    }

    .info-text {
        flex: 1;
        color: #0c4a6e;
        font-weight: 600;
    }

    .historial-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #333;
    }

    .movimientos-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .movimiento-item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s;
    }

    .movimiento-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .movimiento-item.ingreso {
        border-left: 4px solid #10b981;
    }

    .movimiento-item.gasto {
        border-left: 4px solid #ef4444;
    }

    .movimiento-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
    }

    .movimiento-item.ingreso .movimiento-icon {
        background: rgba(16, 185, 129, 0.1);
    }

    .movimiento-item.gasto .movimiento-icon {
        background: rgba(239, 68, 68, 0.1);
    }

    .movimiento-info {
        flex: 1;
    }

    .movimiento-concepto {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }

    .movimiento-detalle {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 3px;
    }

    .movimiento-fecha {
        font-size: 0.85rem;
        color: #888;
    }

    .movimiento-cantidad {
        text-align: right;
        flex-shrink: 0;
    }

    .movimiento-recipuntos {
        font-size: 2rem;
        font-weight: 900;
        line-height: 1;
        margin-bottom: 5px;
    }

    .movimiento-item.ingreso .movimiento-recipuntos {
        color: #10b981;
    }

    .movimiento-item.gasto .movimiento-recipuntos {
        color: #ef4444;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    }

    .badge-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .badge-procesando {
        background: #cfe2ff;
        color: #084298;
    }

    .badge-entregado {
        background: #d4edda;
        color: #155724;
    }

    .badge-cancelado {
        background: #f8d7da;
        color: #721c24;
    }

    .empty-state {
        text-align: center;
        padding: 60px 30px;
        color: #999;
    }

    .empty-icon {
        font-size: 5rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .empty-text {
        font-size: 1rem;
        margin-bottom: 25px;
    }

    .pagination-simple {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin-top: 25px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
    }

    .btn-disabled {
        background: #e0e0e0;
        color: #888;
        cursor: not-allowed;
        pointer-events: none;
    }

    .page-info {
        font-weight: 600;
        color: #666;
    }

    @media (max-width: 768px) {
        .resumen-grid {
            grid-template-columns: 1fr;
        }

        .movimiento-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .movimiento-cantidad {
            text-align: left;
            width: 100%;
        }

        .movimiento-recipuntos {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Resumen de Recipuntos -->
<div class="resumen-banner">
    <div class="resumen-grid">
        <div class="resumen-item">
            <div class="resumen-icon">⭐</div>
            <div class="resumen-value">{{ number_format($recipuntosActuales) }}</div>
            <div class="resumen-label">Recipuntos Actuales</div>
        </div>
        <div class="resumen-item">
            <div class="resumen-icon">📈</div>
            <div class="resumen-value">{{ number_format($totalGanado) }}</div>
            <div class="resumen-label">Total Ganado</div>
        </div>
        <div class="resumen-item">
            <div class="resumen-icon">📉</div>
            <div class="resumen-value">{{ number_format($totalGastado) }}</div>
            <div class="resumen-label">Total Gastado</div>
        </div>
        <div class="resumen-item">
            <div class="resumen-icon">🔄</div>
            <div class="resumen-value">{{ $totalCanjes }}</div>
            <div class="resumen-label">Total Canjes</div>
        </div>
    </div>
</div>

<!-- Información -->
<div class="info-card">
    <div class="info-icon">💡</div>
    <div class="info-text">
        <strong>¿Cómo gano Recipuntos?</strong> Tu profesor te asigna puntos por reciclar y por obtener buenas calificaciones en los exámenes. ¡Sigue esforzándote!
    </div>
</div>

<!-- Historial de Movimientos -->
<div class="historial-card">
    <div class="card-header">
        <h2 class="card-title">📊 Historial de Movimientos</h2>
    </div>

    @if(count($movimientosPaginados) > 0)
        <div class="movimientos-list">
            @foreach($movimientosPaginados as $movimiento)
                <div class="movimiento-item {{ $movimiento['tipo'] }}">
                    <div class="movimiento-icon">
                        @if($movimiento['tipo'] == 'ingreso')
                            ➕
                        @else
                            🎒
                        @endif
                    </div>
                    <div class="movimiento-info">
                        <div class="movimiento-concepto">{{ $movimiento['concepto'] }}</div>
                        <div class="movimiento-detalle">
                            @if($movimiento['tipo'] == 'gasto')
                                <strong>Producto:</strong> {{ $movimiento['producto'] }}
                                @if($movimiento['cantidad'] > 1)
                                    ({{ $movimiento['cantidad'] }}x)
                                @endif
                                •
                                <span class="badge badge-{{ $movimiento['estado'] }}">
                                    {{ ucfirst($movimiento['estado']) }}
                                </span>
                            @endif
                        </div>
                        <div class="movimiento-fecha">
                            📅 {{ \Carbon\Carbon::parse($movimiento['fecha'])->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="movimiento-cantidad">
                        <div class="movimiento-recipuntos">
                            @if($movimiento['recipuntos'] > 0)
                                +{{ number_format($movimiento['recipuntos']) }}
                            @else
                                {{ number_format($movimiento['recipuntos']) }}
                            @endif
                        </div>
                        <div class="resumen-label">recipuntos</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación Simple -->
        @if($total > $perPage)
            <div class="pagination-simple">
                @if($page > 1)
                    <a href="?page={{ $page - 1 }}" class="btn btn-primary">← Anterior</a>
                @else
                    <span class="btn btn-disabled">← Anterior</span>
                @endif

                <span class="page-info">
                    Página {{ $page }} de {{ ceil($total / $perPage) }}
                </span>

                @if($page < ceil($total / $perPage))
                    <a href="?page={{ $page + 1 }}" class="btn btn-primary">Siguiente →</a>
                @else
                    <span class="btn btn-disabled">Siguiente →</span>
                @endif
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">📊</div>
            <h3 class="empty-title">Sin movimientos registrados</h3>
            <p class="empty-text">Aún no tienes movimientos de Recipuntos en tu historial.</p>
            <p style="font-size: 0.9rem; color: #888;">
                Los movimientos aparecerán cuando tu profesor te asigne puntos o cuando realices canjes de productos.
            </p>
        </div>
    @endif
</div>

@endsection
