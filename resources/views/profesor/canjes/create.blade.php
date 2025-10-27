@extends('profesor.layout')

@section('title', 'Realizar Nuevo Canje')
@section('page-title', 'Realizar Nuevo Canje - Sistema Recipuntos')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .canje-container {
        max-width: 900px;
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
        gap: 12px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
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
        background: white;
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

    /* Select2 Customization */
    .select2-container--default .select2-selection--single {
        height: 48px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 44px;
        padding-left: 16px;
        color: #111827;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px;
        right: 10px;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .select2-dropdown {
        border: 2px solid #10b981;
        border-radius: 8px;
        margin-top: 4px;
    }

    .select2-results__option {
        padding: 12px;
    }

    .info-box {
        background: #ecfdf5;
        border: 2px solid #10b981;
        border-radius: 8px;
        padding: 20px;
        margin-top: 25px;
        display: none;
    }

    .info-box.active {
        display: block;
    }

    .info-box h3 {
        color: #047857;
        font-size: 1.1rem;
        margin: 0 0 15px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .info-item {
        background: white;
        padding: 12px;
        border-radius: 6px;
    }

    .info-label {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #047857;
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

    .btn-primary:hover:not(:disabled) {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
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

    .warning-box {
        background: #fef3c7;
        border: 2px solid #f59e0b;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
        display: none;
    }

    .warning-box.active {
        display: block;
    }

    .warning-text {
        color: #92400e;
        font-weight: 600;
        margin: 0;
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

<div class="canje-container">
    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div style="background: #d1fae5; border: 2px solid #10b981; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
            ✓ {{ session('success') }}
        </div>
    @endif

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
            <span style="font-size: 1.8rem;">🔄</span>
            <h2 class="card-title">Realizar Nuevo Canje</h2>
        </div>

        <form action="{{ route('profesor.canjes.store') }}" method="POST" id="canjeForm">
            @csrf

            <div class="form-grid">
                <!-- Buscar Alumno -->
                <div class="form-group">
                    <label for="cod_usuario" class="form-label">
                        Buscar Alumno <span class="required">*</span>
                    </label>
                    <select name="cod_usuario" id="cod_usuario" class="form-select" required style="width: 100%;">
                        <option value="">-- Escribe para buscar alumno --</option>
                    </select>
                    <span class="form-help">Busca por nombre, apellido o usuario del alumno</span>
                </div>

                <!-- Buscar Producto -->
                <div class="form-group">
                    <label for="cod_publicacion" class="form-label">
                        Buscar Producto <span class="required">*</span>
                    </label>
                    <select name="cod_publicacion" id="cod_publicacion" class="form-select" required style="width: 100%;">
                        <option value="">-- Escribe para buscar producto --</option>
                    </select>
                    <span class="form-help">Busca el producto que el alumno desea canjear</span>
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
                           min="1"
                           value="1"
                           required
                           onchange="calculateTotal()">
                    <span class="form-help">Cantidad de unidades a canjear</span>
                </div>
            </div>

            <!-- Resumen del Canje -->
            <div class="info-box" id="resumenBox">
                <h3>📊 Resumen del Canje</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Alumno</div>
                        <div class="info-value" id="resumen-alumno">-</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Recipuntos Actuales</div>
                        <div class="info-value" id="resumen-recipuntos-actual">⭐ -</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Costo Total</div>
                        <div class="info-value" style="color: #dc2626;" id="resumen-costo">⭐ -</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Recipuntos Restantes</div>
                        <div class="info-value" id="resumen-recipuntos-restantes">⭐ -</div>
                    </div>
                </div>

                <!-- Advertencia si no tiene suficientes puntos -->
                <div class="warning-box" id="warningBox">
                    <p class="warning-text">⚠ El alumno no tiene suficientes Recipuntos para este canje</p>
                </div>
            </div>

            <!-- Botones -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span>✓</span>
                    <span>Realizar Canje</span>
                </button>
                <a href="{{ route('profesor.canjes.index') }}" class="btn btn-secondary">
                    <span>✖</span>
                    <span>Cancelar</span>
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let alumnoRecipuntos = 0;
    let productoPrecio = 0;
    let productoDisponibilidad = 0;
    let alumnoNombre = '';
    let productoNombre = '';

    $(document).ready(function() {
        // Inicializar Select2 para búsqueda de alumnos
        $('#cod_usuario').select2({
            placeholder: 'Escribe para buscar alumno...',
            ajax: {
                url: '{{ route("profesor.api.buscar-alumnos") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
            allowClear: true
        }).on('select2:select', function (e) {
            const data = e.params.data;
            alumnoRecipuntos = parseFloat(data.recipuntos) || 0;
            alumnoNombre = data.text;
            calculateTotal();
        }).on('select2:clear', function () {
            alumnoRecipuntos = 0;
            alumnoNombre = '';
            calculateTotal();
        });

        // Inicializar Select2 para búsqueda de productos
        $('#cod_publicacion').select2({
            placeholder: 'Escribe para buscar producto...',
            ajax: {
                url: '{{ route("profesor.api.buscar-productos") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
            allowClear: true
        }).on('select2:select', function (e) {
            const data = e.params.data;
            productoPrecio = parseFloat(data.precio) || 0;
            productoDisponibilidad = parseInt(data.disponibilidad) || 0;
            productoNombre = data.nombre;

            // Actualizar el max de cantidad según disponibilidad
            $('#cantidad').attr('max', productoDisponibilidad);

            calculateTotal();
        }).on('select2:clear', function () {
            productoPrecio = 0;
            productoDisponibilidad = 0;
            productoNombre = '';
            calculateTotal();
        });
    });

    function calculateTotal() {
        const cantidad = parseInt($('#cantidad').val()) || 0;

        if (!$('#cod_usuario').val() || !$('#cod_publicacion').val() || cantidad === 0) {
            $('#resumenBox').removeClass('active');
            return;
        }

        const costoTotal = productoPrecio * cantidad;
        const recipuntosRestantes = alumnoRecipuntos - costoTotal;

        // Actualizar resumen
        $('#resumen-alumno').text(alumnoNombre);
        $('#resumen-recipuntos-actual').text('⭐ ' + alumnoRecipuntos.toLocaleString());
        $('#resumen-costo').text('⭐ ' + costoTotal.toLocaleString());
        $('#resumen-recipuntos-restantes').text('⭐ ' + recipuntosRestantes.toLocaleString());

        // Mostrar resumen
        $('#resumenBox').addClass('active');

        // Validar si tiene suficientes puntos
        if (recipuntosRestantes < 0 || cantidad > productoDisponibilidad) {
            $('#warningBox').addClass('active');
            $('#submitBtn').prop('disabled', true);
        } else {
            $('#warningBox').removeClass('active');
            $('#submitBtn').prop('disabled', false);
        }
    }
</script>

@endsection
