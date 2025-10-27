@extends('admin.layout')

@section('title', 'Gestión de Categorías de Productos')
@section('page-title', 'Categorías de Productos')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-warning {
        background: #f39c12;
        color: white;
        padding: 8px 12px;
        font-size: 0.85rem;
    }

    .btn-warning:hover {
        background: #e67e22;
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
        padding: 8px 12px;
        font-size: 0.85rem;
    }

    .btn-danger:hover {
        background: #c0392b;
    }

    .btn-secondary {
        background: #95a5a6;
        color: white;
    }

    .btn-secondary:hover {
        background: #7f8c8d;
    }

    .card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        padding: 20px 25px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .card-body {
        padding: 0;
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8f9fa;
        padding: 15px 20px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e9ecef;
        font-size: 0.9rem;
    }

    th.text-center,
    td.text-center {
        text-align: center;
    }

    td {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    tbody tr:hover {
        background: #f8f9fa;
    }

    .category-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .category-image-placeholder {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 1.8rem;
    }

    .badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.2s;
    }

    .modal.active {
        display: flex;
    }

    .modal-dialog {
        background: white;
        border-radius: 15px;
        width: 90%;
        max-width: 550px;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideIn 0.3s;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        color: #999;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
        line-height: 1;
    }

    .modal-close:hover {
        background: #f0f0f0;
        color: #333;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        padding: 20px 25px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-text {
        display: block;
        margin-top: 6px;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .preview-image {
        max-width: 150px;
        max-height: 150px;
        margin-top: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px) scale(0.95);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }
</style>

<div class="page-header">
    <button type="button" class="btn btn-primary" onclick="openModal('createModal')">
        ➕ Nueva Categoría
    </button>
</div>

<div class="card">
    <div class="card-header">
        Listado de Categorías
    </div>
    <div class="card-body">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th width="100" class="text-center">Foto</th>
                        <th>Nombre Categoría</th>
                        <th width="150" class="text-center">Productos</th>
                        <th width="180" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->cod_categoria }}</td>
                        <td class="text-center">
                            @if($categoria->foto_categoria)
                                <img src="{{ asset('storage/' . $categoria->foto_categoria) }}"
                                     alt="{{ $categoria->nombre_categoria }}"
                                     class="category-image">
                            @else
                                <div class="category-image-placeholder">📁</div>
                            @endif
                        </td>
                        <td><strong>{{ $categoria->nombre_categoria }}</strong></td>
                        <td class="text-center">
                            <span class="badge badge-info">
                                {{ $categoria->publicaciones_count ?? 0 }} producto(s)
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <button type="button"
                                        class="btn btn-warning"
                                        onclick="openEditModal({{ $categoria->cod_categoria }}, '{{ addslashes($categoria->nombre_categoria) }}', '{{ $categoria->foto_categoria }}')"
                                        title="Editar">
                                    ✏️ Editar
                                </button>
                                <button type="button"
                                        class="btn btn-danger"
                                        onclick="confirmDelete({{ $categoria->cod_categoria }}, '{{ addslashes($categoria->nombre_categoria) }}')"
                                        title="Eliminar">
                                    🗑️ Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">📦</div>
                                <p><strong>No hay categorías registradas</strong></p>
                                <p>Crea una nueva categoría para comenzar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear Categoría -->
<div class="modal" id="createModal">
    <div class="modal-dialog">
        <form action="{{ route('admin.categorias.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Nueva Categoría de Producto</h5>
                <button type="button" class="modal-close" onclick="closeModal('createModal')">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre de la Categoría *</label>
                    <input type="text"
                           class="form-control"
                           name="nombre_categoria"
                           value="{{ old('nombre_categoria') }}"
                           required
                           maxlength="200"
                           placeholder="Ej: Útiles Escolares, Tecnología, Alimentos, etc.">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto de Categoría</label>
                    <input type="file"
                           class="form-control"
                           name="foto_categoria"
                           accept="image/*"
                           onchange="previewImage(this, 'createPreview')">
                    <span class="form-text">Formatos: JPG, PNG, GIF (máx. 2MB)</span>
                    <img id="createPreview" class="preview-image" style="display: none;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Categoría</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Categoría -->
<div class="modal" id="editModal">
    <div class="modal-dialog">
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar Categoría</h5>
                <button type="button" class="modal-close" onclick="closeModal('editModal')">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre de la Categoría *</label>
                    <input type="text"
                           class="form-control"
                           id="edit_nombre"
                           name="nombre_categoria"
                           required
                           maxlength="200">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto de Categoría</label>
                    <img id="currentImage" class="preview-image" style="display: none; margin-bottom: 10px;">
                    <input type="file"
                           class="form-control"
                           name="foto_categoria"
                           accept="image/*"
                           onchange="previewImage(this, 'editPreview')">
                    <span class="form-text">Formatos: JPG, PNG, GIF (máx. 2MB)</span>
                    <img id="editPreview" class="preview-image" style="display: none;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Form para eliminar (oculto) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
// Funciones para modales
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Cerrar modal al hacer clic fuera
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});

// Preview de imagen
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Abrir modal de edición
function openEditModal(id, nombre, foto) {
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('editForm').action = `/admin/categorias/${id}`;

    const currentImage = document.getElementById('currentImage');
    if (foto) {
        currentImage.src = `/storage/${foto}`;
        currentImage.style.display = 'block';
    } else {
        currentImage.style.display = 'none';
    }

    document.getElementById('editPreview').style.display = 'none';
    openModal('editModal');
}

// Confirmar eliminación
function confirmDelete(id, nombre) {
    if (confirm(`¿Estás seguro de eliminar la categoría "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/categorias/${id}`;
        form.submit();
    }
}

// Reabrir modal en caso de errores de validación
@if($errors->any() && !old('_method'))
    window.addEventListener('DOMContentLoaded', function() {
        openModal('createModal');
    });
@endif
</script>
@endsection
