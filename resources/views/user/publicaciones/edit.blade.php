@extends('user.layout')

@section('title', 'Editar Publicación')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        max-width: 900px;
        margin: 0 auto;
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
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
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

    .image-upload-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .image-upload-box {
        border: 2px dashed #e0e0e0;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .image-upload-box:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }

    .image-upload-box.has-image {
        border-style: solid;
        border-color: #667eea;
    }

    .upload-icon {
        font-size: 3rem;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    .upload-text {
        color: #888;
        font-size: 0.9rem;
    }

    .upload-input {
        display: none;
    }

    .preview-image {
        max-width: 100%;
        max-height: 180px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .image-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-replace,
    .btn-remove {
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-replace {
        background: #ffc107;
        color: #333;
    }

    .btn-remove {
        background: #dc3545;
        color: white;
    }

    .btn-replace:hover,
    .btn-remove:hover {
        opacity: 0.9;
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

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .image-upload-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Editar Publicación</h1>
</div>

<div class="form-card">
    <form action="{{ route('user.publicaciones.update', $publicacion->cod_publicacion) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group full-width">
                <label for="nombre" class="form-label">
                    Nombre del Producto <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="form-input"
                    value="{{ old('nombre', $publicacion->nombre) }}"
                    required
                >
                @error('nombre')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full-width">
                <label for="descripcion" class="form-label">
                    Descripción <span class="required">*</span>
                </label>
                <textarea
                    id="descripcion"
                    name="descripcion"
                    class="form-textarea"
                    required
                >{{ old('descripcion', $publicacion->descripcion) }}</textarea>
                @error('descripcion')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoria" class="form-label">
                    Categoría <span class="required">*</span>
                </label>
                <select id="categoria" name="categoria" class="form-select" required>
                    <option value="">Seleccionar...</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->cod_categoria }}"
                            {{ old('categoria', $publicacion->categoria) == $cat->cod_categoria ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="calidad" class="form-label">
                    Calidad <span class="required">*</span>
                </label>
                <select id="calidad" name="calidad" class="form-select" required>
                    <option value="baja" {{ old('calidad', $publicacion->calidad) == 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="media" {{ old('calidad', $publicacion->calidad) == 'media' ? 'selected' : '' }}>Media</option>
                    <option value="alta" {{ old('calidad', $publicacion->calidad) == 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
                @error('calidad')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="disponibilidad" class="form-label">
                    Stock Disponible <span class="required">*</span>
                </label>
                <input
                    type="number"
                    id="disponibilidad"
                    name="disponibilidad"
                    class="form-input"
                    value="{{ old('disponibilidad', $publicacion->disponibilidad) }}"
                    min="0"
                    required
                >
                @error('disponibilidad')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="precio" class="form-label">
                    Precio (S/) <span class="required">*</span>
                </label>
                <input
                    type="number"
                    id="precio"
                    name="precio"
                    class="form-input"
                    value="{{ old('precio', $publicacion->precio) }}"
                    min="0.01"
                    step="0.01"
                    required
                >
                @error('precio')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">
                    Estado <span class="required">*</span>
                </label>
                <select id="status" name="status" class="form-select" required>
                    <option value="activo" {{ old('status', $publicacion->status) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('status', $publicacion->status) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('status')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="region" class="form-label">
                    Región <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="region"
                    name="region"
                    class="form-input"
                    value="{{ old('region', $publicacion->region) }}"
                    required
                >
                @error('region')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="provincia" class="form-label">
                    Provincia <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="provincia"
                    name="provincia"
                    class="form-input"
                    value="{{ old('provincia', $publicacion->provincia) }}"
                    required
                >
                @error('provincia')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="distrito" class="form-label">
                    Distrito <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="distrito"
                    name="distrito"
                    class="form-input"
                    value="{{ old('distrito', $publicacion->distrito) }}"
                    required
                >
                @error('distrito')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Image Management -->
        <div class="form-group full-width" style="margin-top: 30px;">
            <label class="form-label">Fotos del Producto</label>
            <span class="form-hint" style="display: block; margin-bottom: 15px;">
                Puedes reemplazar o eliminar las fotos existentes. Tamaño máximo: 2MB por imagen.
            </span>

            <div class="image-upload-container">
                <!-- Foto 1 -->
                <div class="image-upload-box {{ $publicacion->foto1 ? 'has-image' : '' }}" id="box1">
                    <input type="file" id="foto1" name="foto1" class="upload-input" accept="image/*" onchange="previewImage(this, 1)">
                    <input type="hidden" name="eliminar_foto1" id="eliminar_foto1" value="0">
                    @if($publicacion->foto1)
                        <img src="{{ asset('storage/' . $publicacion->foto1) }}" class="preview-image" alt="Foto 1">
                        <div class="image-actions">
                            <button type="button" class="btn-replace" onclick="replaceImage(1)">Reemplazar</button>
                            <button type="button" class="btn-remove" onclick="removeExistingImage(1)">Eliminar</button>
                        </div>
                    @else
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">Click para subir<br>Foto Principal</div>
                    @endif
                </div>

                <!-- Foto 2 -->
                <div class="image-upload-box {{ $publicacion->foto2 ? 'has-image' : '' }}" id="box2">
                    <input type="file" id="foto2" name="foto2" class="upload-input" accept="image/*" onchange="previewImage(this, 2)">
                    <input type="hidden" name="eliminar_foto2" id="eliminar_foto2" value="0">
                    @if($publicacion->foto2)
                        <img src="{{ asset('storage/' . $publicacion->foto2) }}" class="preview-image" alt="Foto 2">
                        <div class="image-actions">
                            <button type="button" class="btn-replace" onclick="replaceImage(2)">Reemplazar</button>
                            <button type="button" class="btn-remove" onclick="removeExistingImage(2)">Eliminar</button>
                        </div>
                    @else
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">Click para subir<br>Foto 2</div>
                    @endif
                </div>

                <!-- Foto 3 -->
                <div class="image-upload-box {{ $publicacion->foto3 ? 'has-image' : '' }}" id="box3">
                    <input type="file" id="foto3" name="foto3" class="upload-input" accept="image/*" onchange="previewImage(this, 3)">
                    <input type="hidden" name="eliminar_foto3" id="eliminar_foto3" value="0">
                    @if($publicacion->foto3)
                        <img src="{{ asset('storage/' . $publicacion->foto3) }}" class="preview-image" alt="Foto 3">
                        <div class="image-actions">
                            <button type="button" class="btn-replace" onclick="replaceImage(3)">Reemplazar</button>
                            <button type="button" class="btn-remove" onclick="removeExistingImage(3)">Eliminar</button>
                        </div>
                    @else
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">Click para subir<br>Foto 3</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Guardar Cambios
            </button>
            <a href="{{ route('user.publicaciones.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function replaceImage(number) {
    document.getElementById('foto' + number).click();
}

function previewImage(input, number) {
    const box = document.getElementById('box' + number);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            box.classList.add('has-image');
            box.innerHTML = `
                <img src="${e.target.result}" class="preview-image" alt="Preview">
                <div class="image-actions">
                    <button type="button" class="btn-replace" onclick="replaceImage(${number})">Cambiar</button>
                    <button type="button" class="btn-remove" onclick="cancelNewImage(${number})">Cancelar</button>
                </div>
            `;
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function cancelNewImage(number) {
    const input = document.getElementById('foto' + number);
    input.value = '';
    location.reload(); // Recargar para mostrar la imagen original
}

function removeExistingImage(number) {
    if (confirm('¿Estás seguro de eliminar esta imagen?')) {
        const box = document.getElementById('box' + number);
        const eliminarInput = document.getElementById('eliminar_foto' + number);

        eliminarInput.value = '1';
        box.classList.remove('has-image');
        box.innerHTML = `
            <div class="upload-icon">📷</div>
            <div class="upload-text">Imagen marcada para eliminar<br>Click para subir nueva</div>
        `;
        box.onclick = function() {
            document.getElementById('foto' + number).click();
        };
    }
}
</script>
@endsection
