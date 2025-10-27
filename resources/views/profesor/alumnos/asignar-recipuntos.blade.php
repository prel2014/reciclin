@extends('profesor.layout')

@section('title', 'Asignar Recipuntos')
@section('page-title', 'Asignar Recipuntos por Reciclaje')

@section('content')
<style>
    .asignacion-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
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
        font-weight: 700;
    }

    .card-title {
        flex: 1;
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

    .alumno-info-card {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 2px solid #10b981;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .alumno-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.8rem;
        color: #065f46;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 1.1rem;
        color: #111827;
        font-weight: 700;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.95rem;
        display: block;
    }

    .required {
        color: #ef4444;
    }

    .form-select,
    .form-input,
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s;
        background: white;
    }

    .form-select:focus,
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .form-help {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 5px;
    }

    .material-option {
        padding: 10px;
    }

    .resumen-box {
        background: #fef3c7;
        border: 2px solid #f59e0b;
        border-radius: 12px;
        padding: 20px;
        margin-top: 25px;
        display: none;
    }

    .resumen-box.active {
        display: block;
    }

    .resumen-box h3 {
        color: #92400e;
        font-size: 1.1rem;
        margin: 0 0 15px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .resumen-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
    }

    .resumen-item {
        background: white;
        padding: 12px;
        border-radius: 6px;
    }

    .resumen-label {
        font-size: 0.8rem;
        color: #92400e;
        margin-bottom: 4px;
    }

    .resumen-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #111827;
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
        .card {
            padding: 20px;
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

<div class="asignacion-container">
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

    <!-- Información del Alumno -->
    <div class="alumno-info-card">
        <h3 style="margin: 0 0 15px 0; color: #065f46; font-size: 1.1rem;">
            👨‍🎓 Asignando Recipuntos a:
        </h3>
        <div class="alumno-info-grid">
            <div class="info-item">
                <span class="info-label">Nombre Completo</span>
                <span class="info-value">{{ $alumno->nombre }} {{ $alumno->apellido }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Usuario</span>
                <span class="info-value">@{{ $alumno->nick }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Recipuntos Actuales</span>
                <span class="info-value">⭐ {{ number_format($alumno->recipuntos) }}</span>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="card">
        <div class="card-header">
            <div class="card-icon">♻️</div>
            <div class="card-title">
                <h2>Registrar Material Reciclado</h2>
                <p>Asigna Recipuntos por el material reciclado por el alumno</p>
            </div>
        </div>

        <form action="{{ route('profesor.alumnos.asignar-recipuntos.store', $alumno->cod_usuario) }}" method="POST" id="asignacionForm">
            @csrf

            <!-- Seleccionar Material -->
            <div class="form-group">
                <label for="cod_material" class="form-label">
                    Material Reciclable <span class="required">*</span>
                </label>
                <select name="cod_material" id="cod_material" class="form-select" required onchange="updateCalculation()">
                    <option value="">-- Seleccionar material --</option>
                    @foreach($materiales as $material)
                        <option value="{{ $material->cod_material }}"
                                data-recipuntos="{{ $material->recipuntos_por_cantidad }}"
                                data-nombre="{{ $material->nombre }}"
                                {{ old('cod_material') == $material->cod_material ? 'selected' : '' }}>
                            {{ $material->nombre }} - ⭐ {{ number_format($material->recipuntos_por_cantidad) }} pts por unidad
                        </option>
                    @endforeach
                </select>
                <span class="form-help">Selecciona el tipo de material que el alumno recicló</span>
            </div>

            <!-- Cantidad -->
            <div class="form-group">
                <label for="cantidad" class="form-label">
                    Cantidad <span class="required">*</span>
                </label>
                <input type="number"
                       name="cantidad"
                       id="cantidad"
                       class="form-input"
                       step="0.01"
                       min="0.01"
                       value="{{ old('cantidad', 1) }}"
                       required
                       onchange="updateCalculation()">
                <span class="form-help" id="unidadHelp">Cantidad de material reciclado</span>
            </div>

            <!-- Descripción (opcional) -->
            <div class="form-group">
                <label for="descripcion" class="form-label">
                    Observaciones (opcional)
                </label>
                <textarea name="descripcion"
                          id="descripcion"
                          class="form-textarea"
                          rows="3"
                          placeholder="Agregar observaciones sobre el reciclaje...">{{ old('descripcion') }}</textarea>
                <span class="form-help">Notas adicionales sobre la actividad de reciclaje</span>
            </div>

            <!-- Resumen del Cálculo -->
            <div class="resumen-box" id="resumenBox">
                <h3>📊 Resumen de Asignación</h3>
                <div class="resumen-grid">
                    <div class="resumen-item">
                        <div class="resumen-label">Material</div>
                        <div class="resumen-value" id="resumen-material">-</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-label">Cantidad</div>
                        <div class="resumen-value" id="resumen-cantidad">-</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-label">Recipuntos a Asignar</div>
                        <div class="resumen-value" style="color: #10b981;" id="resumen-recipuntos">⭐ -</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-label">Nuevo Total</div>
                        <div class="resumen-value" style="color: #f59e0b;" id="resumen-nuevo-total">⭐ -</div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span>✓</span>
                    <span>Asignar Recipuntos</span>
                </button>
                <a href="{{ route('profesor.alumnos.index') }}" class="btn btn-secondary">
                    <span>✖</span>
                    <span>Cancelar</span>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const alumnoRecipuntosActuales = {{ $alumno->recipuntos }};
    let recipuntosPorCantidad = 0;
    let nombreMaterial = '';

    function updateCalculation() {
        const materialSelect = document.getElementById('cod_material');
        const cantidadInput = document.getElementById('cantidad');
        const cantidad = parseFloat(cantidadInput.value) || 0;

        if (!materialSelect.value || cantidad === 0) {
            document.getElementById('resumenBox').classList.remove('active');
            return;
        }

        const option = materialSelect.options[materialSelect.selectedIndex];
        recipuntosPorCantidad = parseFloat(option.dataset.recipuntos) || 0;
        nombreMaterial = option.dataset.nombre || '';

        // Actualizar ayuda de unidad
        document.getElementById('unidadHelp').textContent = `Cantidad de ${nombreMaterial}`;

        // Calcular recipuntos
        const recipuntosAsignados = cantidad * recipuntosPorCantidad;
        const nuevoTotal = alumnoRecipuntosActuales + recipuntosAsignados;

        // Actualizar resumen
        document.getElementById('resumen-material').textContent = nombreMaterial;
        document.getElementById('resumen-cantidad').textContent = cantidad + ' unidades';
        document.getElementById('resumen-recipuntos').textContent = '⭐ ' + recipuntosAsignados.toLocaleString('es-PE', {minimumFractionDigits: 0, maximumFractionDigits: 2});
        document.getElementById('resumen-nuevo-total').textContent = '⭐ ' + nuevoTotal.toLocaleString('es-PE', {minimumFractionDigits: 0, maximumFractionDigits: 2});

        // Mostrar resumen
        document.getElementById('resumenBox').classList.add('active');
    }

    // Inicializar si hay valores pre-seleccionados
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('cod_material').value) {
            updateCalculation();
        }
    });
</script>

@endsection
