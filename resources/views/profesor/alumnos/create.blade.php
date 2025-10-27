@extends('profesor.layout')

@section('title', 'Registrar Nuevo Alumno')
@section('page-title', 'Registrar Nuevo Alumno')

@section('content')
<style>
    .crear-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e5e7eb;
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
    }

    .card-title h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 5px 0;
    }

    .card-title p {
        font-size: 0.9rem;
        color: #6b7280;
        margin: 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .required {
        color: #ef4444;
    }

    .form-input {
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .form-help {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #e5e7eb;
    }

    .btn {
        padding: 14px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .error-message {
        background: #fee2e2;
        border: 2px solid #ef4444;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="crear-container">
    <!-- Errores -->
    @if($errors->any())
        <div class="error-message">
            <strong>⚠ Errores:</strong>
            <ul style="margin: 8px 0 0 20px; padding: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario -->
    <div class="card">
        <div class="card-header">
            <div class="card-icon">👨‍🎓</div>
            <div class="card-title">
                <h2>Registrar Nuevo Alumno</h2>
                <p>Completa los datos del alumno para registrarlo en el sistema</p>
            </div>
        </div>

        <form action="{{ route('profesor.alumnos.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <!-- Nombre -->
                <div class="form-group">
                    <label for="nombre" class="form-label">
                        Nombre <span class="required">*</span>
                    </label>
                    <input type="text"
                           name="nombre"
                           id="nombre"
                           class="form-input"
                           value="{{ old('nombre') }}"
                           required
                           placeholder="Juan">
                </div>

                <!-- Apellido -->
                <div class="form-group">
                    <label for="apellido" class="form-label">
                        Apellido <span class="required">*</span>
                    </label>
                    <input type="text"
                           name="apellido"
                           id="apellido"
                           class="form-input"
                           value="{{ old('apellido') }}"
                           required
                           placeholder="Pérez">
                </div>

                <!-- Nick/Usuario -->
                <div class="form-group">
                    <label for="nick" class="form-label">
                        Usuario (Nick) <span class="required">*</span>
                    </label>
                    <input type="text"
                           name="nick"
                           id="nick"
                           class="form-input"
                           value="{{ old('nick') }}"
                           required
                           placeholder="juan.perez">
                    <span class="form-help">Usuario único para login</span>
                </div>

                <!-- Teléfono -->
                <div class="form-group">
                    <label for="telefono" class="form-label">
                        Teléfono
                    </label>
                    <input type="tel"
                           name="telefono"
                           id="telefono"
                           class="form-input"
                           value="{{ old('telefono') }}"
                           placeholder="999999999">
                </div>

                <!-- Correo -->
                <div class="form-group full-width">
                    <label for="correo" class="form-label">
                        Correo Electrónico <span class="required">*</span>
                    </label>
                    <input type="email"
                           name="correo"
                           id="correo"
                           class="form-input"
                           value="{{ old('correo') }}"
                           required
                           placeholder="alumno@colegio.edu.pe">
                    <span class="form-help">El correo será usado para notificaciones</span>
                </div>

                <!-- Contraseña -->
                <div class="form-group full-width">
                    <label for="contrasena" class="form-label">
                        Contraseña <span class="required">*</span>
                    </label>
                    <input type="password"
                           name="contrasena"
                           id="contrasena"
                           class="form-input"
                           required
                           minlength="6"
                           placeholder="Mínimo 6 caracteres">
                    <span class="form-help">Contraseña inicial del alumno (mínimo 6 caracteres)</span>
                </div>
            </div>

            <!-- Botones -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span>✓</span>
                    <span>Registrar Alumno</span>
                </button>
                <a href="{{ route('profesor.alumnos.index') }}" class="btn btn-secondary">
                    <span>✖</span>
                    <span>Cancelar</span>
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
