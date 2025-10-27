@extends('profesor.layout')

@section('title', 'Mis Alumnos')
@section('page-title', 'Gestión de Alumnos - Recipuntos')

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
    .stat-card.gold { border-left: 4px solid #f59e0b; }

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

    .stat-subtitle {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 5px;
    }

    .alumnos-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .header-actions {
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

    .btn-new-alumno {
        padding: 12px 24px;
        background: #10b981;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-new-alumno:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .alumnos-table {
        width: 100%;
        border-collapse: collapse;
    }

    .alumnos-table thead th {
        background: #f9fafb;
        padding: 12px 16px;
        text-align: left;
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
    }

    .alumnos-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
    }

    .alumnos-table tbody tr:hover {
        background: #f9fafb;
    }

    .alumno-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alumno-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .alumno-details h4 {
        font-weight: 600;
        margin: 0;
        color: #111827;
    }

    .alumno-details p {
        margin: 0;
        font-size: 0.8rem;
        color: #6b7280;
    }

    .badge-recipuntos {
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 1rem;
        font-weight: 700;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: #10b981;
        color: white;
    }

    .btn-primary:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #3b82f6;
        color: white;
    }

    .btn-secondary:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .actions-group {
        display: flex;
        gap: 8px;
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
        margin-top: 25px;
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
        .header-actions {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .alumnos-table {
            font-size: 0.85rem;
        }

        .actions-group {
            flex-direction: column;
        }
    }
</style>

<!-- Estadísticas -->
<div class="stats-cards">
    <div class="stat-card green">
        <div class="stat-icon">👨‍🎓</div>
        <div class="stat-label">Total de Alumnos</div>
        <div class="stat-value">{{ number_format($totalAlumnos) }}</div>
    </div>

    <div class="stat-card blue">
        <div class="stat-icon">⭐</div>
        <div class="stat-label">Recipuntos Totales</div>
        <div class="stat-value">{{ number_format($totalRecipuntos) }}</div>
        <div class="stat-subtitle">Acumulados por todos</div>
    </div>

    <div class="stat-card purple">
        <div class="stat-icon">📊</div>
        <div class="stat-label">Promedio por Alumno</div>
        <div class="stat-value">{{ number_format($promedioRecipuntos, 1) }}</div>
        <div class="stat-subtitle">Recipuntos</div>
    </div>

    <div class="stat-card gold">
        <div class="stat-icon">🏆</div>
        <div class="stat-label">Alumno Destacado</div>
        <div class="stat-value">
            @if($alumnoDestacado)
                {{ $alumnoDestacado->nombre }}
            @else
                -
            @endif
        </div>
        @if($alumnoDestacado)
            <div class="stat-subtitle">⭐ {{ number_format($alumnoDestacado->recipuntos) }} pts</div>
        @endif
    </div>
</div>

<!-- Tabla de Alumnos -->
<div class="alumnos-container">
    <div class="header-actions">
        <h2 class="page-title">👨‍🎓 Mis Alumnos</h2>
        <a href="{{ route('profesor.alumnos.create') }}" class="btn-new-alumno">
            <span>➕</span>
            <span>Registrar Nuevo Alumno</span>
        </a>
    </div>

    @if($alumnos->count() > 0)
        <table class="alumnos-table">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Contacto</th>
                    <th>Recipuntos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alumnos as $alumno)
                    <tr>
                        <td>
                            <div class="alumno-info">
                                <div class="alumno-avatar">
                                    {{ strtoupper(substr($alumno->nombre, 0, 1)) }}
                                </div>
                                <div class="alumno-details">
                                    <h4>{{ $alumno->nombre }} {{ $alumno->apellido }}</h4>
                                    <p>@{{ $alumno->nick }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>📧 {{ $alumno->correo }}</div>
                            @if($alumno->telefono)
                                <div style="font-size: 0.85rem; color: #6b7280; margin-top: 4px;">
                                    📱 {{ $alumno->telefono }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge-recipuntos">
                                <span>⭐</span>
                                <span>{{ number_format($alumno->recipuntos) }}</span>
                            </span>
                        </td>
                        <td>
                            <div class="actions-group">
                                <a href="{{ route('profesor.alumnos.asignar-recipuntos', $alumno->cod_usuario) }}"
                                   class="btn btn-primary"
                                   title="Asignar por Reciclaje"
                                   style="background: #10b981;">
                                    ♻️ Reciclaje
                                </a>
                                <a href="{{ route('profesor.alumnos.registrar-examen', $alumno->cod_usuario) }}"
                                   class="btn btn-primary"
                                   title="Registrar Examen"
                                   style="background: #3b82f6;">
                                    📝 Examen
                                </a>
                                <a href="{{ route('profesor.alumnos.show', $alumno->cod_usuario) }}"
                                   class="btn btn-secondary"
                                   title="Ver detalles">
                                    👁️ Ver
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            {{ $alumnos->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">👨‍🎓</div>
            <h3>No tienes alumnos registrados</h3>
            <p>Los alumnos que registres aparecerán aquí</p>
            <a href="{{ route('profesor.alumnos.create') }}" class="btn-new-alumno" style="margin-top: 20px;">
                <span>➕</span>
                <span>Registrar Primer Alumno</span>
            </a>
        </div>
    @endif
</div>

@endsection
