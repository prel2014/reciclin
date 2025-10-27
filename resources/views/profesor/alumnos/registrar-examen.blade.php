@extends('profesor.layout')

@section('title', 'Registrar Examen')
@section('page-title', 'Registrar Examen y Asignar Recipuntos')

@section('content')
<style>
    .examen-container {
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
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e5e7eb;
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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

    .alumno-info-card {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: 2px solid #3b82f6;
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
        color: #1e40af;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 1.1rem;
        color: #111827;
        font-weight: 700;
    }

    .info-box {
        background: #eff6ff;
        border: 2px solid #60a5fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .info-box h3 {
        color: #1e40af;
        font-size: 1rem;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-list {
        margin: 0;
        padding-left: 20px;
        color: #374151;
    }

    .info-list li {
        margin-bottom: 5px;
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

    .form-select,
    .form-input,
    .form-textarea {
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .form-select:focus,
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-help {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 5px;
    }

    .exam-type-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .exam-card {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .exam-card input[type="radio"] {
        display: none;
    }

    .exam-card:hover {
        border-color: #3b82f6;
        background: #f0f9ff;
    }

    .exam-card.selected {
        border-color: #3b82f6;
        background: #dbeafe;
    }

    .exam-icon {
        font-size: 2.5rem;
        margin-bottom: 8px;
    }

    .exam-name {
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }

    .exam-points {
        font-size: 0.85rem;
        color: #6b7280;
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
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
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
        .exam-type-cards {
            grid-template-columns: 1fr;
        }

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

<div class="examen-container">
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
        <h3 style="margin: 0 0 15px 0; color: #1e40af; font-size: 1.1rem;">
            👨‍🎓 Registrando examen para:
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

    <!-- Información del Sistema de Exámenes -->
    <div class="info-box">
        <h3>📚 Sistema de Exámenes - Recipuntos</h3>
        <ul class="info-list">
            <li><strong>3 tipos de exámenes:</strong> Comunicación, Matemática y Conocimientos Generales</li>
            <li><strong>Cada examen tiene 20 preguntas</strong></li>
            <li><strong>1 pregunta correcta = 1 Recipunto</strong></li>
            <li>Puntaje máximo por examen: <strong>20 Recipuntos</strong></li>
            <li>Total posible en los 3 exámenes: <strong>60 Recipuntos</strong></li>
        </ul>
    </div>

    <!-- Formulario -->
    <div class="card">
        <div class="card-header">
            <div class="card-icon">📝</div>
            <div class="card-title">
                <h2>Registrar Resultado de Examen</h2>
                <p>Ingresa los resultados del examen rendido por el alumno</p>
            </div>
        </div>

        <form action="{{ route('profesor.alumnos.registrar-examen.store', $alumno->cod_usuario) }}" method="POST" id="examenForm">
            @csrf

            <!-- Tipo de Examen -->
            <div class="form-group full-width">
                <label class="form-label">
                    Tipo de Examen <span class="required">*</span>
                </label>
                <div class="exam-type-cards">
                    <label class="exam-card" data-exam="comunicacion">
                        <input type="radio" name="tipo_examen" value="comunicacion" required {{ old('tipo_examen') == 'comunicacion' ? 'checked' : '' }}>
                        <div class="exam-icon">📖</div>
                        <div class="exam-name">Comunicación</div>
                        <div class="exam-points">Máx. 20 pts</div>
                    </label>

                    <label class="exam-card" data-exam="matematica">
                        <input type="radio" name="tipo_examen" value="matematica" required {{ old('tipo_examen') == 'matematica' ? 'checked' : '' }}>
                        <div class="exam-icon">🔢</div>
                        <div class="exam-name">Matemática</div>
                        <div class="exam-points">Máx. 20 pts</div>
                    </label>

                    <label class="exam-card" data-exam="general">
                        <input type="radio" name="tipo_examen" value="general" required {{ old('tipo_examen') == 'general' ? 'checked' : '' }}>
                        <div class="exam-icon">🌍</div>
                        <div class="exam-name">Conocimientos Generales</div>
                        <div class="exam-points">Máx. 20 pts</div>
                    </label>
                </div>
            </div>

            <div class="form-grid">
                <!-- Preguntas Correctas -->
                <div class="form-group">
                    <label for="preguntas_correctas" class="form-label">
                        Preguntas Correctas <span class="required">*</span>
                    </label>
                    <input type="number"
                           name="preguntas_correctas"
                           id="preguntas_correctas"
                           class="form-input"
                           min="0"
                           max="20"
                           value="{{ old('preguntas_correctas', 0) }}"
                           required
                           onchange="updateResumen()">
                    <span class="form-help">De 0 a 20 preguntas correctas</span>
                </div>

                <!-- Fecha del Examen -->
                <div class="form-group">
                    <label for="fecha_examen" class="form-label">
                        Fecha del Examen <span class="required">*</span>
                    </label>
                    <input type="date"
                           name="fecha_examen"
                           id="fecha_examen"
                           class="form-input"
                           value="{{ old('fecha_examen', date('Y-m-d')) }}"
                           required>
                    <span class="form-help">Fecha en que se rindió el examen</span>
                </div>

                <!-- Observaciones -->
                <div class="form-group full-width">
                    <label for="observaciones" class="form-label">
                        Observaciones (opcional)
                    </label>
                    <textarea name="observaciones"
                              id="observaciones"
                              class="form-textarea"
                              rows="3"
                              placeholder="Comentarios sobre el desempeño del alumno...">{{ old('observaciones') }}</textarea>
                </div>
            </div>

            <!-- Resumen -->
            <div class="resumen-box" id="resumenBox">
                <h3>📊 Resumen del Examen</h3>
                <div class="resumen-grid">
                    <div class="resumen-item">
                        <div class="resumen-label">Tipo de Examen</div>
                        <div class="resumen-value" id="resumen-tipo">-</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-label">Preguntas Correctas</div>
                        <div class="resumen-value" id="resumen-correctas">0 / 20</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-label">Recipuntos a Asignar</div>
                        <div class="resumen-value" style="color: #3b82f6;" id="resumen-recipuntos">⭐ 0</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-label">Nuevo Total</div>
                        <div class="resumen-value" style="color: #f59e0b;" id="resumen-nuevo-total">⭐ {{ number_format($alumno->recipuntos) }}</div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span>✓</span>
                    <span>Registrar Examen</span>
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

    // Manejo de selección de tipo de examen
    document.querySelectorAll('.exam-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.exam-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            updateResumen();
        });
    });

    // Marcar el examen seleccionado al cargar
    document.addEventListener('DOMContentLoaded', function() {
        const selectedRadio = document.querySelector('input[name="tipo_examen"]:checked');
        if (selectedRadio) {
            selectedRadio.closest('.exam-card').classList.add('selected');
            updateResumen();
        }
    });

    function updateResumen() {
        const tipoExamen = document.querySelector('input[name="tipo_examen"]:checked');
        const preguntasCorrectas = parseInt(document.getElementById('preguntas_correctas').value) || 0;

        if (!tipoExamen) {
            document.getElementById('resumenBox').classList.remove('active');
            return;
        }

        const nombres = {
            'comunicacion': '📖 Comunicación',
            'matematica': '🔢 Matemática',
            'general': '🌍 Conocimientos Generales'
        };

        const recipuntosObtenidos = preguntasCorrectas; // 1 pregunta = 1 recipunto
        const nuevoTotal = alumnoRecipuntosActuales + recipuntosObtenidos;

        // Actualizar resumen
        document.getElementById('resumen-tipo').textContent = nombres[tipoExamen.value];
        document.getElementById('resumen-correctas').textContent = preguntasCorrectas + ' / 20';
        document.getElementById('resumen-recipuntos').textContent = '⭐ ' + recipuntosObtenidos;
        document.getElementById('resumen-nuevo-total').textContent = '⭐ ' + nuevoTotal.toLocaleString();

        // Mostrar resumen
        document.getElementById('resumenBox').classList.add('active');
    }
</script>

@endsection
