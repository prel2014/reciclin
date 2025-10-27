# Sistema de Upload de Imágenes - Reciclin.com

## 📸 Descripción General

Sistema completo de upload, gestión y visualización de imágenes para las publicaciones de materiales reciclables en Reciclin.com.

---

## ✅ Características Implementadas

### 1. **Upload de Imágenes**
- Hasta 3 imágenes por publicación
- Formatos permitidos: JPG, JPEG, PNG, WEBP
- Tamaño máximo: 2MB por imagen
- Preview en tiempo real antes de subir
- Validación en cliente y servidor

### 2. **Almacenamiento**
- Almacenamiento en `storage/app/public/publicaciones/`
- Nombres únicos generados automáticamente
- Enlace simbólico para acceso público vía `/storage`

### 3. **Gestión de Imágenes**
- Ver imágenes existentes al editar
- Reemplazar imágenes individuales
- Eliminar imágenes selectivamente
- Limpieza automática al eliminar publicación

### 4. **Visualización**
- Galería en detalle de producto
- Miniaturas navegables
- Placeholders cuando no hay imagen
- Responsive en todos los tamaños de pantalla

---

## 🚀 Instalación y Configuración

### Paso 1: Crear Enlace Simbólico

Este paso es **OBLIGATORIO** para que las imágenes sean accesibles públicamente.

#### Opción A: Usando Script Automático (Windows)
```bash
setup-storage.bat
```

#### Opción B: Usando Script Automático (Linux/Mac)
```bash
chmod +x setup-storage.sh
./setup-storage.sh
```

#### Opción C: Manual
```bash
docker exec -it reciclin-app php artisan storage:link
```

Esto creará un enlace simbólico de:
- `storage/app/public` → `public/storage`

### Paso 2: Verificar Permisos

```bash
docker exec -it reciclin-app chmod -R 775 storage
docker exec -it reciclin-app chown -R www-data:www-data storage
```

### Paso 3: Verificar Configuración

Accede a `http://localhost` y verifica que la carpeta `/storage` sea accesible.

---

## 📁 Estructura de Directorios

```
proyecto/
├── storage/
│   └── app/
│       └── public/
│           └── publicaciones/          ← Imágenes se guardan aquí
│               ├── abc123.jpg
│               ├── def456.png
│               └── ghi789.webp
├── public/
│   └── storage/                        ← Enlace simbólico
│       └── publicaciones/              ← Accesible via web
```

---

## 🎯 Flujos de Uso

### Flujo 1: Usuario Crea Publicación con Imágenes

1. Usuario autenticado navega a `/user/publicaciones`
2. Click en "Nueva Publicación"
3. Completa formulario de datos
4. Click en cada cuadro de upload para seleccionar hasta 3 imágenes
5. Ve preview instantáneo de cada imagen
6. Puede eliminar previews antes de enviar
7. Click en "Publicar"
8. Imágenes se suben y procesan
9. Rutas se guardan en base de datos

### Flujo 2: Usuario Edita Publicación con Imágenes

1. Usuario navega a `/user/publicaciones`
2. Click en "Editar" en una publicación existente
3. Ve imágenes actuales
4. Opciones por imagen:
   - **Mantener**: No hace nada
   - **Reemplazar**: Click en "Reemplazar" y selecciona nueva imagen
   - **Eliminar**: Click en "Eliminar" (marca para eliminación)
5. Click en "Guardar Cambios"
6. Imágenes antiguas se eliminan del storage si fueron reemplazadas
7. Nuevas imágenes se suben y guardan

### Flujo 3: Ver Imágenes en Marketplace

1. Cualquier usuario (autenticado o no) accede al marketplace
2. Ve miniaturas en grid de productos
3. Click en producto
4. Ve galería completa con imagen principal
5. Click en miniaturas para cambiar imagen principal
6. Si no hay imágenes, ve emoji placeholder ♻️

---

## 🔧 Código Técnico

### UserPublicacionController

**Método `store()` - Crear con imágenes:**
```php
// Procesar upload de foto 1
if ($request->hasFile('foto1')) {
    $foto1Path = $request->file('foto1')->store('publicaciones', 'public');
}

// Guardar en BD
Publicacion::create([
    'foto1' => $foto1Path,
    // ... otros campos
]);
```

**Método `update()` - Actualizar con imágenes:**
```php
if ($request->hasFile('foto1')) {
    // Eliminar imagen anterior
    if ($publicacion->foto1) {
        Storage::disk('public')->delete($publicacion->foto1);
    }
    // Subir nueva
    $validated['foto1'] = $request->file('foto1')->store('publicaciones', 'public');
} elseif ($request->has('eliminar_foto1') && $request->eliminar_foto1) {
    // Solo eliminar
    if ($publicacion->foto1) {
        Storage::disk('public')->delete($publicacion->foto1);
        $validated['foto1'] = null;
    }
} else {
    // Mantener actual
    $validated['foto1'] = $publicacion->foto1;
}
```

**Método `destroy()` - Eliminar publicación:**
```php
// Eliminar todas las imágenes del storage
if ($publicacion->foto1) {
    Storage::disk('public')->delete($publicacion->foto1);
}
if ($publicacion->foto2) {
    Storage::disk('public')->delete($publicacion->foto2);
}
if ($publicacion->foto3) {
    Storage::disk('public')->delete($publicacion->foto3);
}

$publicacion->delete();
```

### Validación en Formulario

```php
'foto1' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
'foto2' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
'foto3' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
```

### Mostrar Imágenes en Blade

```blade
@if($publicacion->foto1)
    <img src="{{ asset('storage/' . $publicacion->foto1) }}" alt="{{ $publicacion->nombre }}">
@else
    ♻️ {{-- Placeholder --}}
@endif
```

---

## 🗺️ Rutas Disponibles

### Panel de Usuario (Requiere Autenticación)
```
GET  /user/publicaciones              → Listar mis publicaciones
GET  /user/publicaciones/create       → Formulario crear
POST /user/publicaciones              → Guardar (procesa upload)
GET  /user/publicaciones/{id}/edit    → Formulario editar
PUT  /user/publicaciones/{id}         → Actualizar (procesa upload)
DELETE /user/publicaciones/{id}       → Eliminar (+ imágenes)
```

---

## 💻 JavaScript - Preview y Gestión

### Preview en Creación
```javascript
function previewImage(input, number) {
    const box = document.getElementById('box' + number);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            box.innerHTML = `
                <img src="${e.target.result}" class="preview-image">
                <button onclick="removeImage(${number})">Eliminar</button>
            `;
        };

        reader.readAsDataURL(input.files[0]);
    }
}
```

### Gestión en Edición
```javascript
function removeExistingImage(number) {
    // Marca imagen para eliminación
    document.getElementById('eliminar_foto' + number).value = '1';

    // Actualiza UI
    const box = document.getElementById('box' + number);
    box.innerHTML = `<div>Imagen marcada para eliminar</div>`;
}
```

---

## 🎨 Estilos CSS

### Cuadro de Upload
```css
.image-upload-box {
    border: 2px dashed #e0e0e0;
    border-radius: 10px;
    min-height: 200px;
    cursor: pointer;
    transition: all 0.3s;
}

.image-upload-box:hover {
    border-color: #667eea;
    background: #f8f9ff;
}

.image-upload-box.has-image {
    border-style: solid;
    border-color: #667eea;
}
```

### Preview de Imagen
```css
.preview-image {
    max-width: 100%;
    max-height: 180px;
    border-radius: 8px;
}
```

---

## 🐛 Solución de Problemas

### Error: "The file 'public/storage' does not exist"

**Causa:** No se ha creado el enlace simbólico.

**Solución:**
```bash
docker exec -it reciclin-app php artisan storage:link
```

### Error: "Permission denied" al subir imagen

**Causa:** Permisos incorrectos en carpeta storage.

**Solución:**
```bash
docker exec -it reciclin-app chmod -R 775 storage
docker exec -it reciclin-app chown -R www-data:www-data storage
```

### Las imágenes no se muestran

**Verificaciones:**
1. ¿Existe el enlace simbólico?
   ```bash
   docker exec -it reciclin-app ls -la public/storage
   ```

2. ¿La ruta en BD es correcta?
   ```sql
   SELECT foto1 FROM publicacion WHERE cod_publicacion = X;
   -- Debe ser algo como: publicaciones/abc123.jpg
   ```

3. ¿La URL es correcta en el navegador?
   ```
   http://localhost/storage/publicaciones/abc123.jpg
   ```

### Imágenes muy grandes tardan en subir

**Solución 1:** Aumentar límites en PHP
Edita `Dockerfile`:
```dockerfile
RUN echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/uploads.ini
```

**Solución 2:** Comprimir imágenes antes de subir (frontend)
- Implementar compresión con JavaScript
- Usar librerías como `browser-image-compression`

---

## 📊 Límites y Configuración

### Límites Actuales
- **Máximo de imágenes por publicación:** 3
- **Tamaño máximo por imagen:** 2MB
- **Formatos permitidos:** JPG, JPEG, PNG, WEBP
- **Resolución máxima:** Sin límite (se recomienda 1920x1080)

### Modificar Límites

**Cambiar tamaño máximo (en validación):**
```php
// En UserPublicacionController.php
'foto1' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120', // 5MB
```

**Agregar más formatos:**
```php
'foto1' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif,svg|max:2048',
```

---

## 🚀 Mejoras Futuras Sugeridas

### 1. **Optimización de Imágenes**
- Redimensionar automáticamente a tamaños estándar
- Generar miniaturas (thumbnails)
- Convertir a formato WEBP automáticamente
- Comprimir sin pérdida de calidad

**Librería recomendada:** `intervention/image`

```php
use Intervention\Image\Facades\Image;

$image = Image::make($request->file('foto1'));
$image->resize(800, 600, function ($constraint) {
    $constraint->aspectRatio();
    $constraint->upsize();
});
$image->save(storage_path('app/public/publicaciones/thumb_' . $filename));
```

### 2. **Lazy Loading**
- Cargar imágenes solo cuando sean visibles
- Mejora el rendimiento en listados largos

```html
<img src="{{ asset('storage/' . $pub->foto1) }}"
     alt="{{ $pub->nombre }}"
     loading="lazy">
```

### 3. **CDN Integration**
- Subir a AWS S3, Cloudinary, o similar
- Distribución global más rápida
- No consume recursos del servidor

### 4. **Drag & Drop**
- Arrastrar y soltar imágenes
- Mejor experiencia de usuario

### 5. **Crop y Edición**
- Recortar imágenes antes de subir
- Rotar, ajustar brillo, etc.
- Usar librerías como `cropperjs`

### 6. **Múltiples Imágenes Simultáneas**
- Upload en batch
- Barra de progreso
- Queue para procesamiento asíncrono

---

## 📝 Checklist de Implementación

- [x] Configurar storage de Laravel
- [x] Crear controlador con upload logic
- [x] Validar tipo, tamaño y formato
- [x] Guardar rutas en base de datos
- [x] Mostrar imágenes en frontend
- [x] Preview en tiempo real
- [x] Edición de imágenes existentes
- [x] Eliminación de imágenes antiguas
- [x] Enlace simbólico para acceso público
- [x] Manejo de errores y validaciones
- [x] Responsive en todos los dispositivos
- [x] Placeholder cuando no hay imagen
- [x] Documentación completa

---

## 🔗 Enlaces Útiles

- [Laravel Storage Docs](https://laravel.com/docs/11.x/filesystem)
- [File Upload Validation](https://laravel.com/docs/11.x/validation#rule-image)
- [Intervention Image](http://image.intervention.io/)
- [Browser Image Compression](https://www.npmjs.com/package/browser-image-compression)

---

## 📞 Accesos Rápidos

- **Crear Publicación:** `http://localhost/user/publicaciones/create`
- **Mis Publicaciones:** `http://localhost/user/publicaciones`
- **Ver Marketplace:** `http://localhost/marketplace`

---

**Versión:** 1.0
**Última actualización:** 2025-10-20
**Autor:** Sistema Reciclin.com
**Framework:** Laravel 11.x
