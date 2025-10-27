@extends('admin.layout')

@section('title', 'Materiales Reciclables - Admin')

@section('content')

<style>
    .materiales-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .materiales-header h1 {
        font-size: 28px;
        color: #1f2937;
        margin: 0;
    }
    .btn-primary {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
    }
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }
    .materiales-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    .material-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
    }
    .material-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .material-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 15px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
    }
    .material-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }
    .material-name {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }
    .material-description {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 12px;
        line-height: 1.5;
    }
    .material-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 10px;
        background: #f9fafb;
        border-radius: 6px;
    }
    .info-item {
        display: flex;
        flex-direction: column;
    }
    .info-label {
        font-size: 11px;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }
    .info-value {
        font-size: 14px;
        color: #1f2937;
        font-weight: 600;
    }
    .recipuntos-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 16px;
        font-weight: 700;
        display: inline-block;
        margin-top: 10px;
    }
    .material-status {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-activo {
        background: #d1fae5;
        color: #065f46;
    }
    .status-inactivo {
        background: #fee2e2;
        color: #991b1b;
    }
    .material-actions {
        display: flex;
        gap: 8px;
        margin-top: 15px;
    }
    .btn-edit, .btn-delete, .btn-toggle {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-align: center;
    }
    .btn-edit {
        background: #3b82f6;
        color: white;
    }
    .btn-edit:hover {
        background: #2563eb;
    }
    .btn-delete {
        background: #ef4444;
        color: white;
    }
    .btn-delete:hover {
        background: #dc2626;
    }
    .btn-toggle {
        background: #f59e0b;
        color: white;
    }
    .btn-toggle:hover {
        background: #d97706;
    }
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
        padding: 30px;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease;
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
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #8b5cf6;
    }
    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }
    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }
    .btn-submit {
        flex: 1;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
    }
    .btn-cancel {
        flex: 1;
        background: #6b7280;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-cancel:hover {
        background: #4b5563;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .empty-state-icon {
        font-size: 72px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    .empty-state-text {
        font-size: 18px;
        color: #6b7280;
        margin-bottom: 20px;
    }
</style>

<div class="materiales-header">
    <h1>Materiales Reciclables</h1>
    <button class="btn-primary" onclick="openCreateModal()">+ Nuevo Material</button>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($materiales->count() > 0)
    <div class="materiales-grid">
        @foreach($materiales as $material)
            <div class="material-card">
                <span class="material-status status-{{ $material->estado }}">
                    {{ ucfirst($material->estado) }}
                </span>

                <div class="material-image">
                    @if($material->imagen)
                        <img src="{{ asset('storage/' . $material->imagen) }}" alt="{{ $material->nombre }}">
                    @else
                        ♻️
                    @endif
                </div>

                <div class="material-name">{{ $material->nombre }}</div>
                <div class="material-description">{{ $material->descripcion ?? 'Sin descripción' }}</div>

                <div class="recipuntos-badge">
                    {{ $material->recipuntos_por_cantidad }} pts por cantidad
                </div>

                <div class="material-actions">
                    <button class="btn-edit" onclick="openEditModal({{ $material->cod_material }})">Editar</button>
                    <form action="{{ route('admin.materiales.toggleStatus', $material->cod_material) }}" method="POST" style="flex: 1;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-toggle" style="width: 100%;">
                            {{ $material->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.materiales.destroy', $material->cod_material) }}" method="POST" style="flex: 1;" onsubmit="if(confirm('¿Estás seguro de eliminar este material?')) { showLoading('Eliminando material...'); return true; } return false;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" style="width: 100%;">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-state-icon">♻️</div>
        <div class="empty-state-text">No hay materiales reciclables registrados</div>
        <button class="btn-primary" onclick="openCreateModal()">Crear Primer Material</button>
    </div>
@endif

<!-- Modal Crear Material -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Crear Material Reciclable</div>
        <form action="{{ route('admin.materiales.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nombre">Nombre del Material *</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Ej: Botella PET, Papel, Cartón">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" placeholder="Descripción del material reciclable"></textarea>
            </div>
            <div class="form-group">
                <label for="recipuntos_por_cantidad">Recipuntos por Cantidad *</label>
                <input type="number" id="recipuntos_por_cantidad" name="recipuntos_por_cantidad" step="0.01" min="0" required placeholder="0.00">
                <small style="color: #6b7280; font-size: 12px; display: block; margin-top: 5px;">Cuántos Recipuntos vale cada unidad del material reciclado</small>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen (Opcional)</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
            </div>
            <div class="form-group">
                <label for="estado">Estado *</label>
                <select id="estado" name="estado" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeCreateModal()">Cancelar</button>
                <button type="submit" class="btn-submit">Crear Material</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Material -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Editar Material Reciclable</div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit_nombre">Nombre del Material *</label>
                <input type="text" id="edit_nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="edit_descripcion">Descripción</label>
                <textarea id="edit_descripcion" name="descripcion"></textarea>
            </div>
            <div class="form-group">
                <label for="edit_recipuntos_por_cantidad">Recipuntos por Cantidad *</label>
                <input type="number" id="edit_recipuntos_por_cantidad" name="recipuntos_por_cantidad" step="0.01" min="0" required>
                <small style="color: #6b7280; font-size: 12px; display: block; margin-top: 5px;">Cuántos Recipuntos vale cada unidad del material reciclado</small>
            </div>
            <div class="form-group">
                <label for="edit_imagen">Imagen (Opcional)</label>
                <input type="file" id="edit_imagen" name="imagen" accept="image/*">
            </div>
            <div class="form-group">
                <label for="edit_estado">Estado *</label>
                <select id="edit_estado" name="estado" required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancelar</button>
                <button type="submit" class="btn-submit">Actualizar Material</button>
            </div>
        </form>
    </div>
</div>

<script>
    const materialesData = @json($materiales);

    function openCreateModal() {
        document.getElementById('createModal').classList.add('active');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.remove('active');
    }

    function openEditModal(id) {
        const material = materialesData.find(m => m.cod_material === id);
        if (!material) return;

        document.getElementById('edit_nombre').value = material.nombre;
        document.getElementById('edit_descripcion').value = material.descripcion || '';
        document.getElementById('edit_recipuntos_por_cantidad').value = material.recipuntos_por_cantidad;
        document.getElementById('edit_estado').value = material.estado;

        document.getElementById('editForm').action = `/admin/materiales/${id}`;
        document.getElementById('editModal').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('active');
        }
    }
</script>

@endsection
