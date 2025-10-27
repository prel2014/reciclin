# 🎥 GUÍA: CÓMO SUBIR VIDEOS LOCALES

## ✅ IMPLEMENTACIÓN COMPLETADA

Ahora el sistema **SÍ permite subir archivos de video** directamente desde tu computadora, además de usar URLs de YouTube/Vimeo.

---

## 📋 PASOS PARA SUBIR UN VIDEO LOCAL:

### **1. Acceder al Panel de Administración**
```
URL: http://localhost:8000/admin/multimedia
```

### **2. Crear Nuevo Contenido**
1. Click en el botón **"+ Nuevo Contenido"**

### **3. Seleccionar Tipo Video**
1. En los cards de tipo de contenido, click en **"🎥 Video"**

### **4. Elegir Subir Archivo (Nueva Opción)**
Ahora verás **DOS TABS**:

```
┌────────────────────────────────────────┐
│  📁 Subir Archivo  │  🔗 URL Externa   │
└────────────────────────────────────────┘
      ↑ ACTIVO            (inactivo)
```

El tab **"📁 Subir Archivo"** está seleccionado por defecto.

### **5. Subir Tu Video**
1. Click en la zona de **"Haz clic para subir un video"**
2. Selecciona tu archivo de video
3. **Formatos soportados**:
   - MP4
   - MOV
   - AVI
   - WMV
   - FLV
   - WEBM
4. **Tamaño máximo**: 50MB

### **6. Vista Previa**
- El video aparecerá inmediatamente con controles
- Puedes reproducirlo para verificar

### **7. Completar el Formulario**
- **Título**: Título descriptivo
- **Descripción**: (Opcional)
- **Sección**: Banner / Galería / Destacado
- **Orden**: Número de orden (0 = primero)
- **Estado**: Activo

### **8. Guardar**
- Click en **"✓ Guardar Contenido"**
- El video se sube automáticamente

---

## 🔄 ALTERNATIVA: USAR URL EXTERNA

Si prefieres usar un video de YouTube/Vimeo:

1. Click en el tab **"🔗 URL Externa"**
2. Pega la URL del video:
   ```
   https://www.youtube.com/watch?v=XXXXXXX
   https://youtu.be/XXXXXXX
   ```
3. Guardar

---

## 📁 DÓNDE SE GUARDAN LOS VIDEOS

Los videos locales se guardan en:
```
storage/app/public/multimedia/videos/
```

Son accesibles públicamente vía:
```
public/storage/multimedia/videos/
```

---

## 🎨 CÓMO SE MUESTRAN EN LA PÁGINA

El sistema detecta automáticamente el tipo de video:

### **Video Local (Archivo)**
```html
<video autoplay muted loop playsinline>
    <source src="/storage/multimedia/videos/video.mp4">
</video>
```

### **Video Externo (YouTube)**
```html
<iframe src="https://www.youtube.com/embed/XXXXX">
</iframe>
```

---

## ⚠️ LIMITACIONES Y CONSIDERACIONES

### **Tamaño de Archivos**
- **Imágenes**: Máx. 5MB
- **Videos**: Máx. 50MB

### **Si necesitas videos más grandes**

Puedes aumentar el límite editando:

**Archivo**: `app/Http/Controllers/AdminMultimediaController.php`

Línea 75:
```php
'archivo_video' => 'nullable|file|mimes:mp4,mov,avi,wmv,flv,webm|max:51200', // 50MB
```

Cambiar `51200` por el valor deseado en kilobytes:
- 100MB = `102400`
- 200MB = `204800`
- 500MB = `512000`

**También necesitas ajustar PHP**:

Archivo: `php.ini` (dentro del contenedor Docker)
```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
```

---

## 🧪 PRUEBA RÁPIDA

### **Test con video de muestra**

1. Descarga un video corto de prueba (menos de 50MB)
2. Ve a `/admin/multimedia`
3. Crear nuevo video
4. Seleccionar tipo "Video"
5. Tab "📁 Subir Archivo"
6. Subir tu video de prueba
7. Título: "Video de Prueba"
8. Sección: "Galería"
9. Orden: 10
10. Estado: Activo
11. **Guardar**

### **Verificar**

1. Ve a la página principal: `http://localhost:8000`
2. Scroll hasta la sección **"📸 Galería de Reciclin"**
3. Tu video debe aparecer allí

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### ❌ "El archivo es demasiado grande"

**Solución**:
1. Comprimir el video (usar Handbrake, FFmpeg, etc.)
2. O aumentar límite en `AdminMultimediaController.php`

### ❌ "Error al subir el archivo"

**Verificar**:
1. Storage link creado:
   ```bash
   docker-compose exec app php artisan storage:link
   ```

2. Permisos de escritura:
   ```bash
   docker-compose exec app chmod -R 775 storage/
   ```

### ❌ "El video no se reproduce"

**Verificar**:
1. Formato compatible (MP4 recomendado)
2. Codec H.264 (más compatible)
3. Navegador moderno

---

## 📊 COMPARACIÓN: ARCHIVO vs URL

| Característica | Video Local | URL Externa |
|----------------|-------------|-------------|
| **Control total** | ✅ Sí | ❌ Depende del servicio |
| **Privacidad** | ✅ Tu servidor | ❌ Plataforma externa |
| **Velocidad carga** | ⚠️ Depende servidor | ✅ CDN optimizado |
| **Ancho de banda** | ❌ Consume tu banda | ✅ No consume |
| **Límite tamaño** | ⚠️ 50MB (configurable) | ✅ Sin límite |
| **Funciona offline** | ✅ Sí (con VPN) | ❌ No |

---

## 💡 RECOMENDACIONES

### **Usa Video Local cuando:**
- Videos cortos (< 50MB)
- Contenido privado/exclusivo
- Control total sobre reproducción
- No quieres depender de YouTube

### **Usa URL Externa cuando:**
- Videos largos (> 50MB)
- Ya están en YouTube/Vimeo
- Quieres ahorrar ancho de banda
- Aprovechas SEO de YouTube

---

## 🎬 EDITAR VIDEOS EXISTENTES

### **Cambiar de URL a Archivo Local**

1. Ir a `/admin/multimedia`
2. Click en **"✏️ Editar"** en el video
3. El video actual se muestra arriba
4. Click en tab **"📁 Subir Archivo"**
5. Subir nuevo archivo
6. **Guardar**

El sistema **elimina automáticamente** la URL anterior y guarda el archivo.

### **Cambiar de Archivo Local a URL**

1. Editar el video
2. Click en tab **"🔗 URL Externa"**
3. Pegar nueva URL
4. **Guardar**

El sistema **elimina automáticamente** el archivo anterior y guarda la URL.

---

## ✅ CHECKLIST DE VERIFICACIÓN

Antes de reportar un problema, verifica:

- [ ] Migración ejecutada (`2025_10_22_000002_add_archivo_video_to_multimedia_homepage`)
- [ ] Storage link creado (`php artisan storage:link`)
- [ ] Permisos de escritura en `storage/`
- [ ] Video menor a 50MB
- [ ] Formato MP4, MOV, AVI, WMV, FLV o WEBM
- [ ] Navegador actualizado (Chrome, Firefox, Edge)

---

## 🎉 ¡LISTO PARA USAR!

El sistema ahora está **100% funcional** para:
- ✅ Subir imágenes
- ✅ Subir videos locales **(NUEVO)**
- ✅ Usar URLs de videos externos

**Prueba subiendo un video ahora:** http://localhost:8000/admin/multimedia

---

## 📞 SOPORTE

Si sigues teniendo problemas:

1. Verifica los logs:
   ```bash
   docker-compose logs app
   ```

2. Verifica Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Verifica el inspector del navegador (F12) → Consola

---

¡Disfruta subiendo tus videos! 🚀
