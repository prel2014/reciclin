@extends('admin.layout')

@section('title', 'Nuevo Contenido Multimedia')
@section('page-title', 'Agregar Imagen o Video')

@section('content')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .form-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e5e7eb;
    }

    .form-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .form-header p {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .required {
        color: #ef4444;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-help {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 5px;
        display: block;
    }

    .tipo-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .tipo-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tipo-card:hover {
        border-color: #667eea;
        background: #f9fafb;
    }

    .tipo-card.selected {
        border-color: #667eea;
        background: #eff6ff;
    }

    .tipo-card input[type="radio"] {
        display: none;
    }

    .tipo-icon {
        font-size: 3rem;
        margin-bottom: 10px;
    }

    .tipo-name {
        font-weight: 700;
        color: #111827;
        font-size: 1.1rem;
    }

    .conditional-field {
        display: none;
    }

    .conditional-field.active {
        display: block;
    }

    .file-upload {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f9fafb;
    }

    .file-upload-label:hover {
        border-color: #667eea;
        background: #eff6ff;
    }

    .file-upload-icon {
        font-size: 3rem;
        margin-bottom: 10px;
        color: #667eea;
    }

    .file-upload input[type="file"] {
        display: none;
    }

    .image-preview {
        margin-top: 15px;
        display: none;
    }

    .image-preview img {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
        flex: 1;
        justify-content: center;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h2>📸 Nuevo Contenido Multimedia</h2>
        <p>Agrega imágenes o videos para mostrar en la página principal</p>
    </div>

    <form action="{{ route('admin.multimedia.store') }}" method="POST" enctype="multipart/form-data" id="multimediaForm">
        @csrf

        <!-- Selector de Tipo -->
        <div class="form-group">
            <label class="form-label">Tipo de Contenido <span class="required">*</span></label>
            <div class="tipo-selector">
                <label class="tipo-card" data-tipo="imagen">
                    <input type="radio" name="tipo" value="imagen" {{ old('tipo') == 'imagen' ? 'checked' : '' }} required>
                    <div class="tipo-icon">🖼️</div>
                    <div class="tipo-name">Imagen</div>
                </label>
                <label class="tipo-card" data-tipo="video">
                    <input type="radio" name="tipo" value="video" {{ old('tipo') == 'video' ? 'checked' : '' }} required>
                    <div class="tipo-icon">🎥</div>
                    <div class="tipo-name">Video</div>
                </label>
            </div>
        </div>

        <!-- Campo de Imagen (condicional) -->
        <div class="form-group conditional-field" id="campo-imagen">
            <label for="archivo" class="form-label">
                Imagen <span class="required">*</span>
            </label>
            <div class="file-upload">
                <label for="archivo" class="file-upload-label">
                    <div class="file-upload-icon">📤</div>
                    <div style="font-weight: 600; color: #374151;">Haz clic para subir una imagen</div>
                    <div style="font-size: 0.85rem; color: #6b7280; margin-top: 5px;">
                        JPG, PNG, GIF, WEBP (máx. 5MB)
                    </div>
                </label>
                <input type="file" id="archivo" name="archivo" accept="image/*" onchange="previewImage(event)">
            </div>
            <div class="image-preview" id="imagePreview">
                <img id="previewImg" src="" alt="Vista previa">
            </div>
        </div>

        <!-- Campo de Video (condicional) -->
        <div class="form-group conditional-field" id="campo-video">
            <label class="form-label">
                Video <span class="required">*</span>
            </label>

            <!-- Tabs para elegir entre archivo o URL -->
            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <button type="button" class="video-tab active" data-tab="archivo"
                        style="flex: 1; padding: 10px; border: 2px solid #667eea; border-radius: 8px; background: #eff6ff; color: #667eea; font-weight: 600; cursor: pointer;">
                    📁 Subir Archivo
                </button>
                <button type="button" class="video-tab" data-tab="url"
                        style="flex: 1; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; background: white; color: #374151; font-weight: 600; cursor: pointer;">
                    🔗 URL Externa
                </button>
            </div>

            <!-- Upload de archivo de video -->
            <div id="video-archivo-section" class="video-section">
                <div class="file-upload">
                    <label for="archivo_video" class="file-upload-label">
                        <div class="file-upload-icon">🎥</div>
                        <div style="font-weight: 600; color: #374151;">Haz clic para subir un video</div>
                        <div style="font-size: 0.85rem; color: #6b7280; margin-top: 5px;">
                            MP4, MOV, AVI, WMV, FLV, WEBM (máx. 50MB)
                        </div>
                    </label>
                    <input type="file" id="archivo_video" name="archivo_video" accept="video/*" onchange="previewVideo(event)">
                </div>
                <div class="image-preview" id="videoPreview" style="display: none;">
                    <video id="previewVideo" controls style="max-width: 100%; border-radius: 8px;">
                        Tu navegador no soporta la etiqueta de video.
                    </video>
                </div>
            </div>

            <!-- URL externa -->
            <div id="video-url-section" class="video-section" style="display: none;">
                <input type="url"
                       id="url_video"
                       name="url_video"
                       class="form-input"
                       placeholder="https://www.youtube.com/watch?v=..."
                       value="{{ old('url_video') }}">
                <span class="form-help">Ingresa la URL completa de YouTube, Vimeo u otra plataforma</span>
            </div>
        </div>

        <!-- Título -->
        <div class="form-group">
            <label for="titulo" class="form-label">
                Título <span class="required">*</span>
            </label>
            <input type="text"
                   id="titulo"
                   name="titulo"
                   class="form-input"
                   placeholder="Título descriptivo del contenido"
                   value="{{ old('titulo') }}"
                   required>
        </div>

        <!-- Descripción -->
        <div class="form-group">
            <label for="descripcion" class="form-label">
                Descripción (opcional)
            </label>
            <textarea id="descripcion"
                      name="descripcion"
                      class="form-textarea"
                      rows="3"
                      placeholder="Descripción del contenido...">{{ old('descripcion') }}</textarea>
        </div>

        <!-- Sección -->
        <div class="form-group">
            <label for="seccion" class="form-label">
                Sección <span class="required">*</span>
            </label>
            <select id="seccion" name="seccion" class="form-select" required>
                <option value="">-- Seleccionar sección --</option>
                <option value="banner" {{ old('seccion') == 'banner' ? 'selected' : '' }}>Banner Principal</option>
                <option value="galeria" {{ old('seccion') == 'galeria' ? 'selected' : '' }}>Galería</option>
                <option value="destacado" {{ old('seccion') == 'destacado' ? 'selected' : '' }}>Contenido Destacado</option>
            </select>
            <span class="form-help">Selecciona dónde aparecerá este contenido en la página principal</span>
        </div>

        <!-- Orden -->
        <div class="form-group">
            <label for="orden" class="form-label">
                Orden de Visualización <span class="required">*</span>
            </label>
            <input type="number"
                   id="orden"
                   name="orden"
                   class="form-input"
                   min="0"
                   value="{{ old('orden', 0) }}"
                   required>
            <span class="form-help">Número que determina el orden (0 = primero)</span>
        </div>

        <!-- Estado -->
        <div class="form-group">
            <label for="estado" class="form-label">
                Estado <span class="required">*</span>
            </label>
            <select id="estado" name="estado" class="form-select" required>
                <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <!-- Botones -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                ✓ Guardar Contenido
            </button>
            <a href="{{ route('admin.multimedia.index') }}" class="btn btn-secondary">
                ✖ Cancelar
            </a>
        </div>
    </form>
</div>

<script>
// Manejo de selección de tipo
document.querySelectorAll('.tipo-card').forEach(card => {
    card.addEventListener('click', function() {
        const tipo = this.dataset.tipo;

        // Actualizar UI
        document.querySelectorAll('.tipo-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        this.querySelector('input[type="radio"]').checked = true;

        // Mostrar/ocultar campos condicionales
        document.querySelectorAll('.conditional-field').forEach(field => {
            field.classList.remove('active');
        });

        if (tipo === 'imagen') {
            document.getElementById('campo-imagen').classList.add('active');
        } else if (tipo === 'video') {
            document.getElementById('campo-video').classList.add('active');
        }
    });
});

// Inicializar estado al cargar
document.addEventListener('DOMContentLoaded', function() {
    const selectedRadio = document.querySelector('input[name="tipo"]:checked');
    if (selectedRadio) {
        selectedRadio.closest('.tipo-card').click();
    }
});

// Vista previa de imagen
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

// Vista previa de video
function previewVideo(event) {
    const file = event.target.files[0];
    if (file) {
        const video = document.getElementById('previewVideo');
        video.src = URL.createObjectURL(file);
        document.getElementById('videoPreview').style.display = 'block';
    }
}

// Manejo de tabs de video
document.querySelectorAll('.video-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        const targetTab = this.dataset.tab;

        // Actualizar tabs
        document.querySelectorAll('.video-tab').forEach(t => {
            t.classList.remove('active');
            t.style.borderColor = '#e5e7eb';
            t.style.background = 'white';
            t.style.color = '#374151';
        });

        this.classList.add('active');
        this.style.borderColor = '#667eea';
        this.style.background = '#eff6ff';
        this.style.color = '#667eea';

        // Mostrar/ocultar secciones
        if (targetTab === 'archivo') {
            document.getElementById('video-archivo-section').style.display = 'block';
            document.getElementById('video-url-section').style.display = 'none';
            // Limpiar URL si cambia a archivo
            document.getElementById('url_video').value = '';
        } else {
            document.getElementById('video-archivo-section').style.display = 'none';
            document.getElementById('video-url-section').style.display = 'block';
            // Limpiar archivo si cambia a URL
            document.getElementById('archivo_video').value = '';
            document.getElementById('videoPreview').style.display = 'none';
        }
    });
});
</script>

@endsection
