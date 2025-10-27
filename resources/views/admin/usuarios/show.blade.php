@extends('admin.layout')

@section('title', 'Detalles del Usuario')
@section('page-title', 'Detalles del Usuario')

@section('content')
<style>
    .user-header {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .user-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 2.5rem;
    }

    .user-header-info {
        flex: 1;
    }

    .user-header-name {
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }

    .user-header-nick {
        font-size: 1.1rem;
        color: #888;
        margin-bottom: 10px;
    }

    .user-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2 px 10px rgba(0, 0, 0, 0.05);
    }

    .info-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #888;
        font-weight: 500;
    }

    .info-value {
        color: #333;
        font-weight: 600;
        text-align: right;
    }

    .badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-admin {
        background: #764ba2;
        color: white;
    }

    .badge-user {
        background: #667eea;
        color: white;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .actions-bar {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-warning {
        background: #ffc107;
        color: #333;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        text-align: left;
        padding: 12px;
        background: #f8f9fa;
        color: #666;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .table td {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
    }

    .empty-state {
        text-align: center;
        color: #888;
        padding: 30px;
    }
</style>

<div class="user-header">
    <div class="user-avatar-large">{{ strtoupper(substr($usuario->nick, 0, 1)) }}</div>
    <div class="user-header-info">
        <h1 class="user-header-name">{{ $usuario->nombre }} {{ $usuario->apellido }}</h1>
        <div class="user-header-nick">@{{ $usuario->nick }}</div>
        <div class="user-badges">
            @if($usuario->tipo === 'administrador')
                <span class="badge badge-admin">Administrador</span>
            @else
                <span class="badge badge-user">Usuario</span>
            @endif

            @if($usuario->estado === 'activo')
                <span class="badge badge-active">Activo</span>
            @else
                <span class="badge badge-inactive">Inactivo</span>
            @endif
        </div>
        <div class="actions-bar">
            <a href="{{ route('admin.usuarios.edit', $usuario->cod_usuario) }}" class="btn btn-warning">
                Editar Usuario
            </a>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
                Volver al Listado
            </a>
        </div>
    </div>
</div>

<div class="info-grid">
    <div class="info-card">
        <h2 class="info-card-title">Información Personal</h2>
        <div class="info-row">
            <span class="info-label">ID</span>
            <span class="info-value">#{{ $usuario->cod_usuario }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Correo Electrónico</span>
            <span class="info-value">{{ $usuario->correo }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Teléfono</span>
            <span class="info-value">{{ $usuario->telefono ?? 'No especificado' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Registro</span>
            <span class="info-value">{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Última Actualización</span>
            <span class="info-value">{{ $usuario->updated_at ? $usuario->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
        </div>
    </div>

    <div class="info-card">
        <h2 class="info-card-title">Estadísticas</h2>
        <div class="info-row">
            <span class="info-label">Total de Publicaciones</span>
            <span class="info-value">{{ number_format($usuario->publicaciones ?? 0) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total de Compras</span>
            <span class="info-value">{{ number_format($usuario->compras->count() ?? 0) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Publicaciones Activas</span>
            <span class="info-value">{{ number_format($usuario->publicaciones()->count() ?? 0) }}</span>
        </div>
    </div>
</div>

<div class="info-card">
    <h2 class="info-card-title">Publicaciones Recientes</h2>
    @if($usuario->publicaciones && $usuario->publicaciones->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuario->publicaciones->take(5) as $pub)
                    <tr>
                        <td>#{{ $pub->cod_publicacion }}</td>
                        <td>{{ $pub->titulo ?? 'Sin título' }}</td>
                        <td>{{ $pub->categoria->nombre ?? 'N/A' }}</td>
                        <td>S/ {{ number_format($pub->precio ?? 0, 2) }}</td>
                        <td>{{ $pub->created_at ? $pub->created_at->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            Este usuario no tiene publicaciones registradas
        </div>
    @endif
</div>

<div class="info-card">
    <h2 class="info-card-title">Compras Recientes</h2>
    @if($usuario->compras && $usuario->compras->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Publicación</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuario->compras->sortByDesc('fecha')->take(5) as $compra)
                    <tr>
                        <td>#{{ $compra->cod_compra }}</td>
                        <td>{{ $compra->publicacion->titulo ?? 'N/A' }}</td>
                        <td>{{ $compra->cantidad }}</td>
                        <td>S/ {{ number_format($compra->precio_t, 2) }}</td>
                        <td>{{ $compra->fecha }}</td>
                        <td>
                            @if($compra->status === 'completado')
                                <span class="badge badge-active">Completado</span>
                            @else
                                <span class="badge badge-inactive">{{ ucfirst($compra->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            Este usuario no ha realizado compras
        </div>
    @endif
</div>
@endsection
