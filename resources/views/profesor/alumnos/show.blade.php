@extends('profesor.layout')

@section('title', 'Detalles del Alumno')

@section('content')
<style>
    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        color: #1f2937;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        text-decoration: none;
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .back-link:hover {
        color: #3b82f6;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .info-card h3 {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0 0 1rem 0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }

    .info-item {
        margin-bottom: 1rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1.125rem;
        color: #1f2937;
        font-weight: 500;
    }

    .recipuntos-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-activo {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactivo {
        background: #fee2e2;
        color: #991b1b;
    }

    .section-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f3f4f6;
    }

    .section-title {
        font-size: 1.25rem;
        color: #1f2937;
        font-weight: 600;
        margin: 0;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f9fafb;
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e5e7eb;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        color: #1f2937;
    }

    tr:hover {
        background: #f9fafb;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .product-image {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        object-fit: cover;
        background: #f3f4f6;
    }

    .product-name {
        font-weight: 500;
        color: #1f2937;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
    }

    .date-badge {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .quantity-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1e40af;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .price-badge {
        font-weight: 600;
        color: #f59e0b;
    }
</style>

<div class="detail-container">
    <a href="{{ route('profesor.alumnos.index') }}" class="back-link">
        <span>←</span> Volver a Mis Alumnos
    </a>

    <div class="page-header">
        <h1>
            👤 {{ $alumno->nombre }} {{ $alumno->apellido }}
        </h1>
    </div>

    <!-- Botones de Acción -->
    <div class="action-buttons">
        <a href="{{ route('profesor.alumnos.asignar-recipuntos', $alumno->cod_usuario) }}"
           class="btn btn-success">
            ♻️ Asignar por Reciclaje
        </a>
        <a href="{{ route('profesor.alumnos.registrar-examen', $alumno->cod_usuario) }}"
           class="btn btn-primary">
            📝 Registrar Examen
        </a>
    </div>

    <!-- Tarjetas de Información -->
    <div class="info-grid">
        <!-- Información Personal -->
        <div class="info-card">
            <h3>Información Personal</h3>
            <div class="info-item">
                <div class="info-label">Nombre Completo</div>
                <div class="info-value">{{ $alumno->nombre }} {{ $alumno->apellido }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Usuario (Nick)</div>
                <div class="info-value">{{ $alumno->nick }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Correo Electrónico</div>
                <div class="info-value">{{ $alumno->correo }}</div>
            </div>
            @if($alumno->telefono)
            <div class="info-item">
                <div class="info-label">Teléfono</div>
                <div class="info-value">{{ $alumno->telefono }}</div>
            </div>
            @endif
            <div class="info-item">
                <div class="info-label">Estado</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $alumno->estado }}">
                        {{ ucfirst($alumno->estado) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Recipuntos -->
        <div class="info-card">
            <h3>Recipuntos Acumulados</h3>
            <div class="recipuntos-badge">
                ⭐ {{ number_format($alumno->recipuntos) }} Recipuntos
            </div>
        </div>
    </div>

    <!-- Historial de Canjes -->
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">🎒 Historial de Canjes</h2>
        </div>

        @if($canjes->count() > 0)
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Recipuntos</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($canjes as $canje)
                        <tr>
                            <td>
                                <div class="product-info">
                                    @if($canje->publicacion && $canje->publicacion->foto)
                                        <img src="{{ asset('storage/' . $canje->publicacion->foto) }}"
                                             alt="{{ $canje->publicacion->nombre }}"
                                             class="product-image">
                                    @else
                                        <div class="product-image"></div>
                                    @endif
                                    <span class="product-name">
                                        {{ $canje->publicacion ? $canje->publicacion->nombre : 'Producto no disponible' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="quantity-badge">{{ $canje->cantidad }}</span>
                            </td>
                            <td>
                                <span class="price-badge">⭐ {{ number_format($canje->precio_t) }}</span>
                            </td>
                            <td>
                                <span class="date-badge">
                                    {{ \Carbon\Carbon::parse($canje->fecha)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $canje->estado }}">
                                    {{ ucfirst($canje->estado) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🎒</div>
                <p>Este alumno aún no ha realizado canjes</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Auto-dismiss success messages
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endpush
@endsection
