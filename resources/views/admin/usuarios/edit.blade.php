@extends('admin.layout')

@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        max-width: 800px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #333;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-label .required {
        color: #dc3545;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-error {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    .form-hint {
        color: #888;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #667eea;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-card">
    <div class="info-box">
        <strong>Editando usuario:</strong> {{ $usuario->nick }} (#{{ $usuario->cod_usuario }})
    </div>

    <form action="{{ route('admin.usuarios.update', $usuario->cod_usuario) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label for="tipo" class="form-label">
                    Tipo de Usuario <span class="required">*</span>
                </label>
                <select id="tipo" name="tipo" class="form-select" required>
                    <option value="usuario" {{ old('tipo', $usuario->tipo) == 'usuario' ? 'selected' : '' }}>Usuario</option>
                    <option value="administrador" {{ old('tipo', $usuario->tipo) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                </select>
                @error('tipo')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="estado" class="form-label">
                    Estado <span class="required">*</span>
                </label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="activo" {{ old('estado', $usuario->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $usuario->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nick" class="form-label">
                    Nick de Usuario <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="nick"
                    name="nick"
                    class="form-input"
                    value="{{ old('nick', $usuario->nick) }}"
                    required
                >
                @error('nick')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="correo" class="form-label">
                    Correo Electrónico <span class="required">*</span>
                </label>
                <input
                    type="email"
                    id="correo"
                    name="correo"
                    class="form-input"
                    value="{{ old('correo', $usuario->correo) }}"
                    required
                >
                @error('correo')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nombre" class="form-label">
                    Nombre <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="form-input"
                    value="{{ old('nombre', $usuario->nombre) }}"
                    required
                >
                @error('nombre')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="apellido" class="form-label">
                    Apellido <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="apellido"
                    name="apellido"
                    class="form-input"
                    value="{{ old('apellido', $usuario->apellido) }}"
                    required
                >
                @error('apellido')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="telefono" class="form-label">
                    Teléfono
                </label>
                <input
                    type="tel"
                    id="telefono"
                    name="telefono"
                    class="form-input"
                    value="{{ old('telefono', $usuario->telefono) }}"
                >
                @error('telefono')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="contrasena" class="form-label">
                    Nueva Contraseña
                </label>
                <input
                    type="password"
                    id="contrasena"
                    name="contrasena"
                    class="form-input"
                    placeholder="Dejar en blanco para no cambiar"
                >
                <span class="form-hint">Solo completa si deseas cambiar la contraseña</span>
                @error('contrasena')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Actualizar Usuario
            </button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
