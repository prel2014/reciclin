@extends('admin.layout')

@section('title', 'Gestión de Usuarios')
@section('page-title', 'Gestión de Usuarios')

@section('content')
<style>
    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .search-filter-group {
        display: flex;
        gap: 10px;
        flex: 1;
        flex-wrap: wrap;
    }

    .search-input {
        padding: 10px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.9rem;
        min-width: 250px;
        flex: 1;
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

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .btn-success {
        background: #28a745;
    }

    .btn-success:hover {
        background: #218838;
    }

    .btn-warning {
        background: #ffc107;
        color: #333;
    }

    .btn-warning:hover {
        background: #e0a800;
    }

    .btn-danger {
        background: #dc3545;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .table-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .table th {
        text-align: left;
        padding: 15px 12px;
        background: #f8f9fa;
        color: #666;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e0e0e0;
    }

    .table td {
        padding: 15px 12px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
    }

    .table tr:hover {
        background: #f8f9fa;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
    }

    .user-info {
        display: flex;
        flex-direction: column;
    }

    .user-nick {
        font-weight: 600;
        color: #333;
    }

    .user-email {
        font-size: 0.85rem;
        color: #888;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-admin {
        background: #764ba2;
        color: white;
    }

    .badge-profesor {
        background: #10b981;
        color: white;
    }

    .badge-alumno {
        background: #3b82f6;
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

    .actions {
        display: flex;
        gap: 8px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        color: #667eea;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .pagination .active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination a:hover {
        background: #f8f9fa;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 0;
        max-width: 700px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 25px 30px;
        border-bottom: 2px solid #f0f0f0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px 16px 0 0;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .modal-close {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 30px;
    }

    .form-grid-modal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group-modal {
        margin-bottom: 0;
    }

    .form-group-modal.full-width {
        grid-column: 1 / -1;
    }

    .form-label-modal {
        display: block;
        margin-bottom: 8px;
        color: #333;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-label-modal .required {
        color: #dc3545;
    }

    .form-input-modal,
    .form-select-modal {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-input-modal:focus,
    .form-select-modal:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-error-modal {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    .form-hint-modal {
        color: #888;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    .modal-footer {
        display: flex;
        gap: 15px;
        padding: 20px 30px;
        border-top: 2px solid #f0f0f0;
        background: #f8f9fa;
        border-radius: 0 0 16px 16px;
    }

    .btn-modal {
        flex: 1;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.95rem;
    }

    .btn-submit-modal {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-submit-modal:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-cancel-modal {
        background: #6c757d;
        color: white;
    }

    .btn-cancel-modal:hover {
        background: #5a6268;
    }

    @media (max-width: 768px) {
        .form-grid-modal {
            grid-template-columns: 1fr;
        }

        .modal-content {
            width: 95%;
            max-height: 95vh;
        }
    }
</style>

<div class="toolbar">
    <form method="GET" class="search-filter-group">
        <input
            type="text"
            name="search"
            class="search-input"
            placeholder="Buscar por nick, correo, nombre..."
            value="{{ request('search') }}"
        >

        <select name="tipo" class="filter-select">
            <option value="">Todos los tipos</option>
            <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="profesor" {{ request('tipo') == 'profesor' ? 'selected' : '' }}>Profesor</option>
            <option value="alumno" {{ request('tipo') == 'alumno' ? 'selected' : '' }}>Alumno</option>
        </select>

        <select name="estado" class="filter-select">
            <option value="">Todos los estados</option>
            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>

        <button type="submit" class="btn-primary btn-sm">Filtrar</button>
        @if(request('search') || request('tipo') || request('estado'))
            <a href="{{ route('admin.usuarios.index') }}" class="btn-warning btn-sm">Limpiar</a>
        @endif
    </form>

    <button type="button" onclick="openCreateModal()" class="btn-primary">
        + Nuevo Usuario
    </button>
</div>

<div class="table-container">
    @if($usuarios->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombre Completo</th>
                    <th>Tipo</th>
                    <th>Publicaciones</th>
                    <th>Estado</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                    <tr>
                        <td><strong>#{{ $usuario->cod_usuario }}</strong></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">{{ strtoupper(substr($usuario->nick, 0, 1)) }}</div>
                                <div class="user-info">
                                    <span class="user-nick">{{ $usuario->nick }}</span>
                                    <span class="user-email">{{ $usuario->correo }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                        <td>
                            @if($usuario->tipo === 'administrador' || $usuario->tipo === 'admin')
                                <span class="badge badge-admin">Admin</span>
                            @elseif($usuario->tipo === 'profesor')
                                <span class="badge badge-profesor">Profesor</span>
                            @elseif($usuario->tipo === 'alumno')
                                <span class="badge badge-alumno">Alumno</span>
                            @else
                                <span class="badge badge-user">{{ ucfirst($usuario->tipo) }}</span>
                            @endif
                        </td>
                        <td><strong>{{ number_format($usuario->publicaciones ?? 0) }}</strong></td>
                        <td>
                            @if($usuario->estado === 'activo')
                                <span class="badge badge-active">Activo</span>
                            @else
                                <span class="badge badge-inactive">Inactivo</span>
                            @endif
                        </td>
                        <td>{{ $usuario->telefono ?? '-' }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.usuarios.show', $usuario->cod_usuario) }}"
                                   class="btn-primary btn-sm"
                                   title="Ver detalles">
                                    👁️
                                </a>
                                <a href="{{ route('admin.usuarios.edit', $usuario->cod_usuario) }}"
                                   class="btn-warning btn-sm"
                                   title="Editar">
                                    ✏️
                                </a>
                                <form action="{{ route('admin.usuarios.destroy', $usuario->cod_usuario) }}"
                                      method="POST"
                                      style="display: inline;"
                                      onsubmit="showLoading('Eliminando usuario...'); return true;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm" title="Eliminar">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $usuarios->links() }}
        </div>
    @else
        <p style="text-align: center; color: #888; padding: 40px;">
            No se encontraron usuarios
        </p>
    @endif
</div>

<!-- Modal Crear Usuario -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Nuevo Usuario</h3>
            <button type="button" class="modal-close" onclick="closeCreateModal()">×</button>
        </div>
        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-grid-modal">
                    <div class="form-group-modal">
                        <label for="tipo" class="form-label-modal">
                            Tipo de Usuario <span class="required">*</span>
                        </label>
                        <select id="tipo" name="tipo" class="form-select-modal" required>
                            <option value="">Seleccionar...</option>
                            <option value="admin">Admin</option>
                            <option value="profesor">Profesor</option>
                            <option value="alumno">Alumno</option>
                        </select>
                    </div>

                    <div class="form-group-modal">
                        <label for="estado" class="form-label-modal">
                            Estado <span class="required">*</span>
                        </label>
                        <select id="estado" name="estado" class="form-select-modal" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group-modal">
                        <label for="nick" class="form-label-modal">
                            Nick de Usuario <span class="required">*</span>
                        </label>
                        <input type="text" id="nick" name="nick" class="form-input-modal" placeholder="usuario123" required>
                    </div>

                    <div class="form-group-modal">
                        <label for="correo" class="form-label-modal">
                            Correo Electrónico <span class="required">*</span>
                        </label>
                        <input type="email" id="correo" name="correo" class="form-input-modal" placeholder="correo@ejemplo.com" required>
                    </div>

                    <div class="form-group-modal">
                        <label for="nombre" class="form-label-modal">
                            Nombre <span class="required">*</span>
                        </label>
                        <input type="text" id="nombre" name="nombre" class="form-input-modal" placeholder="Juan" required>
                    </div>

                    <div class="form-group-modal">
                        <label for="apellido" class="form-label-modal">
                            Apellido <span class="required">*</span>
                        </label>
                        <input type="text" id="apellido" name="apellido" class="form-input-modal" placeholder="Pérez" required>
                    </div>

                    <div class="form-group-modal">
                        <label for="telefono" class="form-label-modal">
                            Teléfono
                        </label>
                        <input type="tel" id="telefono" name="telefono" class="form-input-modal" placeholder="+51 999 999 999">
                    </div>

                    <div class="form-group-modal">
                        <label for="contrasena" class="form-label-modal">
                            Contraseña <span class="required">*</span>
                        </label>
                        <input type="password" id="contrasena" name="contrasena" class="form-input-modal" placeholder="Mínimo 6 caracteres" required>
                        <span class="form-hint-modal">Mínimo 6 caracteres</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-cancel-modal" onclick="closeCreateModal()">Cancelar</button>
                <button type="submit" class="btn-modal btn-submit-modal">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.add('active');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.remove('active');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const createModal = document.getElementById('createModal');
    if (event.target === createModal) {
        closeCreateModal();
    }
}
</script>
@endsection
