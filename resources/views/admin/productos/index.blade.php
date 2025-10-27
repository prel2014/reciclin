@extends('admin.layout')

@section('title', 'Gestión de Productos')
@section('page-title', 'Productos (Útiles Escolares)')

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

    .btn-success {
        background: #28a745;
        color: white;
        padding: 6px 12px;
        font-size: 0.8rem;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
        padding: 6px 12px;
        font-size: 0.8rem;
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

    .producto-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .producto-image-placeholder {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 2rem;
    }

    .badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-primary {
        background: #cfe2ff;
        color: #084298;
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
        max-width: 600px;
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

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-text {
        display: block;
        margin-top: 6px;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .preview-image {
        max-width: 200px;
        max-height: 200px;
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
        ➕ Nuevo Producto
    </button>
</div>

<div class="card">
    <div class="card-header">
        Listado de Productos (Útiles Escolares)
    </div>
    <div class="card-body">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th width="100" class="text-center">Foto</th>
                        <th>Útil Escolar</th>
                        <th width="140" class="text-center">Precio (Pts)</th>
                        <th width="100" class="text-center">Stock</th>
                        <th width="100" class="text-center">Estado</th>
                        <th width="200" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->cod_util }}</td>
                        <td class="text-center">
                            @if($producto->foto)
                                <img src="{{ asset('storage/' . $producto->foto) }}"
                                     alt="{{ $producto->nombre }}"
                                     class="producto-image">
                            @else
                                <div class="producto-image-placeholder">🎒</div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $producto->nombre }}</strong>
                            @if($producto->descripcion)
                                <div style="font-size: 0.85rem; color: #888; margin-top: 4px;">
                                    {{ Str::limit($producto->descripcion, 60) }}
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <strong style="color: #667eea; font-size: 1.1rem;">
                                ⭐ {{ number_format($producto->precio ?? 0) }}
                            </strong>
                        </td>
                        <td class="text-center">
                            @if(($producto->disponibilidad ?? 0) > 0)
                                <span class="badge badge-success">{{ number_format($producto->disponibilidad) }}</span>
                            @else
                                <span class="badge badge-danger">Agotado</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.productos.toggle-status', $producto->cod_util) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn {{ $producto->status === 'activo' ? 'btn-success' : 'btn-info' }}">
                                    {{ $producto->status === 'activo' ? '✓ Activo' : '✗ Inactivo' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <button type="button"
                                        class="btn btn-warning"
                                        onclick='openEditModal(@json($producto))'
                                        title="Editar">
                                    ✏️ Editar
                                </button>
                                <button type="button"
                                        class="btn btn-danger"
                                        onclick="confirmDelete({{ $producto->cod_util }}, '{{ addslashes($producto->nombre) }}')"
                                        title="Eliminar">
                                    🗑️ Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">🎒</div>
                                <p><strong>No hay útiles escolares registrados</strong></p>
                                <p>Crea un nuevo útil escolar para comenzar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear Producto -->
<div class="modal" id="createModal">
    <div class="modal-dialog">
        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Producto (Útil Escolar)</h5>
                <button type="button" class="modal-close" onclick="closeModal('createModal')">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre del Útil Escolar *</label>
                    <input type="text"
                           class="form-control"
                           name="nombre"
                           value="{{ old('nombre') }}"
                           required
                           maxlength="200"
                           placeholder="Ej: Cuaderno A4, Lapicero azul, Mochila escolar">
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control"
                              name="descripcion"
                              placeholder="Descripción del producto...">{{ old('descripcion') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Precio en Recipuntos *</label>
                    <input type="number"
                           class="form-control"
                           name="precio"
                           value="{{ old('precio') }}"
                           required
                           min="0"
                           step="1"
                           placeholder="Ej: 100">
                    <span class="form-text">Cantidad de Recipuntos necesarios para canjear</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Disponibilidad (Stock) *</label>
                    <input type="number"
                           class="form-control"
                           name="disponibilidad"
                           value="{{ old('disponibilidad') }}"
                           required
                           min="0"
                           step="1"
                           placeholder="Ej: 50">
                    <span class="form-text">Cantidad de unidades disponibles</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto del Producto</label>
                    <input type="file"
                           class="form-control"
                           name="foto1"
                           accept="image/*"
                           onchange="previewImage(this, 'createPreview')">
                    <span class="form-text">Formatos: JPG, PNG, GIF (máx. 2MB)</span>
                    <img id="createPreview" class="preview-image" style="display: none;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Producto</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Producto -->
<div class="modal" id="editModal">
    <div class="modal-dialog">
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar Producto</h5>
                <button type="button" class="modal-close" onclick="closeModal('editModal')">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre del Útil Escolar *</label>
                    <input type="text"
                           class="form-control"
                           id="edit_nombre"
                           name="nombre"
                           required
                           maxlength="200">
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" id="edit_descripcion" name="descripcion"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Precio en Recipuntos *</label>
                    <input type="number"
                           class="form-control"
                           id="edit_precio"
                           name="precio"
                           required
                           min="0"
                           step="1">
                </div>

                <div class="form-group">
                    <label class="form-label">Disponibilidad (Stock) *</label>
                    <input type="number"
                           class="form-control"
                           id="edit_disponibilidad"
                           name="disponibilidad"
                           required
                           min="0"
                           step="1">
                </div>

                <div class="form-group">
                    <label class="form-label">Foto del Producto</label>
                    <img id="currentImage" class="preview-image" style="display: none; margin-bottom: 10px;">
                    <input type="file"
                           class="form-control"
                           name="foto1"
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
function openEditModal(producto) {
    document.getElementById('edit_nombre').value = producto.nombre || '';
    document.getElementById('edit_descripcion').value = producto.descripcion || '';
    document.getElementById('edit_precio').value = producto.precio || 0;
    document.getElementById('edit_disponibilidad').value = producto.disponibilidad || 0;
    document.getElementById('editForm').action = `/admin/productos/${producto.cod_util}`;

    const currentImage = document.getElementById('currentImage');
    if (producto.foto) {
        currentImage.src = `/storage/${producto.foto}`;
        currentImage.style.display = 'block';
    } else {
        currentImage.style.display = 'none';
    }

    document.getElementById('editPreview').style.display = 'none';
    openModal('editModal');
}

// Confirmar eliminación
function confirmDelete(id, nombre) {
    if (confirm(`¿Estás seguro de eliminar el producto "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
        showLoading('Eliminando producto...');
        const form = document.getElementById('deleteForm');
        form.action = `/admin/productos/${id}`;
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
