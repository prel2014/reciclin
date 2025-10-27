# Marketplace - Guía de Usuario

## Descripción
Sistema completo de marketplace para compra/venta de materiales reciclables en Reciclin.com.

## Características Implementadas

### 1. Listado de Publicaciones (Marketplace Index)
- **Ruta:** `/marketplace`
- **Vista:** `marketplace/index.blade.php`
- **Características:**
  - Grid responsivo de publicaciones
  - Búsqueda por palabra clave
  - Filtrado por categoría
  - Ordenamiento (recientes, precio, popularidad)
  - Paginación
  - Badges para productos nuevos y populares
  - Información del vendedor
  - Ubicación del producto

### 2. Detalle de Publicación
- **Ruta:** `/marketplace/{id}`
- **Vista:** `marketplace/show.blade.php`
- **Características:**
  - Galería de imágenes (hasta 3 fotos)
  - Información completa del producto
  - Datos del vendedor
  - Formulario de compra con cálculo automático de total
  - Contador de visitas
  - Publicaciones relacionadas
  - Validación de stock
  - Solo usuarios autenticados pueden comprar

### 3. Proceso de Compra
- **Ruta:** `POST /marketplace/{id}/comprar`
- **Características:**
  - Validación de autenticación
  - Validación de cantidad
  - Cálculo automático de precio total
  - Creación de registro de compra
  - Estado inicial: "pendiente"
  - Notificación de éxito

## Datos de Prueba

### Categorías Incluidas:
1. **Plástico** - Botellas PET, envases, bolsas plásticas
2. **Papel y Cartón** - Periódicos, revistas, cajas de cartón
3. **Vidrio** - Botellas y frascos de vidrio
4. **Metal** - Latas de aluminio, cobre, hierro
5. **Electrónicos** - Computadoras, celulares, cables
6. **Textiles** - Ropa, telas, retazos

### Publicaciones de Prueba:
Se crean **12 publicaciones** variadas:
- Botellas PET (100 unidades) - S/ 50.00
- Cartón corrugado (50kg) - S/ 75.00
- Latas de aluminio (200 unidades) - S/ 120.00
- Papel blanco de oficina (100kg) - S/ 180.00
- Botellas de vidrio (80 unidades) - S/ 60.00
- Plástico HDPE triturado (30kg) - S/ 90.00
- Periódicos y revistas (80kg) - S/ 40.00
- Cables de cobre (15kg) - S/ 250.00
- Envases plásticos PET (150 unidades) - S/ 85.00
- Chatarra de hierro (100kg) - S/ 150.00
- Textiles de algodón (40kg) - S/ 60.00
- Componentes electrónicos (lote mixto) - S/ 200.00

Cada publicación incluye:
- Nombre descriptivo
- Descripción detallada
- Stock disponible
- Precio en soles (PEN)
- Calidad (alta/media)
- Ubicación (Lima, varios distritos)
- Contador de visitas simulado

## Instalación y Configuración

### 1. Ejecutar Migraciones y Seeders

Para crear las tablas y poblar la base de datos con datos de prueba:

```bash
docker exec -it reciclin-app php artisan migrate:fresh --seed
```

Este comando:
- Elimina todas las tablas existentes
- Crea todas las tablas desde cero
- Ejecuta todos los seeders:
  - `UsuarioSeeder` - 3 usuarios (admin, usuario1, usuario2)
  - `CategoriaSeeder` - 6 categorías
  - `PublicacionSeeder` - 12 publicaciones

### 2. Si necesitas ejecutar solo los seeders sin borrar datos:

```bash
docker exec -it reciclin-app php artisan db:seed
```

### 3. Ejecutar un seeder específico:

```bash
docker exec -it reciclin-app php artisan db:seed --class=PublicacionSeeder
```

## Rutas Disponibles

### Públicas
- `GET /marketplace` - Listado de publicaciones
- `GET /marketplace/{id}` - Detalle de publicación

### Protegidas (requieren autenticación)
- `POST /marketplace/{id}/comprar` - Procesar compra

## Uso del Sistema

### 1. Explorar el Marketplace

1. Visita `http://localhost/marketplace`
2. Explora las publicaciones disponibles
3. Usa los filtros para:
   - Buscar por palabra clave
   - Filtrar por categoría
   - Ordenar por precio o popularidad
4. Click en "Ver Detalles" para más información

### 2. Ver Detalle de Producto

1. Click en cualquier publicación
2. Observa:
   - Galería de imágenes (si hay fotos disponibles)
   - Descripción completa
   - Precio y stock
   - Información del vendedor
   - Ubicación
3. Cambia entre imágenes haciendo click en las miniaturas

### 3. Realizar una Compra

**Requisito:** Debes estar autenticado

1. Inicia sesión (usa uno de los usuarios de prueba)
2. Navega al detalle de una publicación
3. Selecciona la cantidad deseada
4. El total se calculará automáticamente
5. Click en "Comprar Ahora"
6. Verás un mensaje de confirmación

**Usuarios de Prueba:**
- Email: `usuario@reciclin.com` | Contraseña: `usuario123`
- Email: `eco@reciclin.com` | Contraseña: `eco123`

### 4. Validaciones

El sistema valida:
- ✓ Usuario autenticado para comprar
- ✓ Cantidad mínima: 1
- ✓ Cantidad máxima: Stock disponible
- ✓ Producto activo
- ✓ Cálculo correcto del precio total

## Estructura de Archivos

```
app/
├── Http/
│   └── Controllers/
│       └── MarketplaceController.php
├── Models/
│   ├── Publicacion.php
│   ├── Categoria.php
│   └── Compra.php
database/
├── migrations/
│   ├── create_publicacions_table.php
│   ├── create_categorias_table.php
│   └── create_compras_table.php
├── seeders/
│   ├── CategoriaSeeder.php
│   └── PublicacionSeeder.php
resources/
└── views/
    └── marketplace/
        ├── index.blade.php
        └── show.blade.php
routes/
└── web.php
```

## Características del Código

### MarketplaceController

**`index()`** - Listado con filtros:
```php
- Búsqueda por palabra clave (nombre, descripción)
- Filtro por categoría
- Solo publicaciones activas
- Ordenamiento múltiple
- Paginación (12 items por página)
```

**`show($id)`** - Detalle:
```php
- Carga la publicación con relación de usuario
- Incrementa contador de visitas
- Obtiene publicaciones relacionadas (misma categoría)
- Retorna vista con datos
```

**`comprar($id)`** - Procesar compra:
```php
- Valida autenticación
- Valida cantidad
- Crea registro en tabla compras
- Estado inicial: "pendiente"
- Calcula precio total
- Redirige con mensaje de éxito
```

### Vistas Blade

**marketplace/index.blade.php:**
- Grid responsivo (auto-fit, min 280px)
- Cards con hover effect
- Navbar integrado
- Toolbar de búsqueda y filtros
- Paginación estilizada
- Empty state cuando no hay resultados

**marketplace/show.blade.php:**
- Layout de dos columnas (galería + info)
- Galería con imagen principal y miniaturas
- Formulario de compra con cálculo en tiempo real
- JavaScript para cambiar imagen principal
- Publicaciones relacionadas
- Breadcrumb de navegación

## Próximas Funcionalidades Sugeridas

1. **Sistema de Favoritos**
   - Guardar publicaciones favoritas
   - Lista de deseos

2. **Historial de Compras del Usuario**
   - Ver compras realizadas
   - Seguimiento de estado

3. **Panel de Vendedor**
   - Crear publicaciones
   - Gestionar mis publicaciones
   - Ver compras recibidas

4. **Sistema de Mensajería**
   - Chat entre comprador y vendedor
   - Negociación de precios

5. **Calificaciones y Reseñas**
   - Valorar vendedores
   - Comentarios en publicaciones

6. **Subida de Imágenes**
   - Upload de fotos reales
   - Galería expandible

7. **Mapa de Ubicación**
   - Integración con Google Maps
   - Ver ubicación del vendedor

8. **Notificaciones**
   - Alertas de nuevas publicaciones
   - Notificaciones de compra

## Troubleshooting

### Error: "No hay usuarios o categorías"
**Solución:** Ejecuta primero los seeders de Usuario y Categoria:
```bash
docker exec -it reciclin-app php artisan db:seed --class=UsuarioSeeder
docker exec -it reciclin-app php artisan db:seed --class=CategoriaSeeder
docker exec -it reciclin-app php artisan db:seed --class=PublicacionSeeder
```

### Error: "SQLSTATE[42S02]: Base table or view not found"
**Solución:** Ejecuta las migraciones primero:
```bash
docker exec -it reciclin-app php artisan migrate:fresh
```

### No aparecen publicaciones en el marketplace
**Solución:** Verifica que el status sea 'activo':
```sql
UPDATE publicacion SET status = 'activo';
```

### Las imágenes no se muestran
**Nota:** Las publicaciones de prueba no tienen imágenes reales (foto1, foto2, foto3 son null).
Se muestra un emoji ♻️ como placeholder. Para agregar imágenes reales, actualiza las URLs en los seeders o en la base de datos.

## Integración con Componentes Existentes

El marketplace está completamente integrado con:
- ✓ Sistema de autenticación
- ✓ Navbar principal de Reciclin
- ✓ Estilos CSS existentes
- ✓ Modelos de Usuario, Publicacion, Categoria, Compra
- ✓ Base de datos completa

## Accesos Rápidos

- **Página Principal:** `http://localhost/`
- **Marketplace:** `http://localhost/marketplace`
- **Login:** `http://localhost/login`
- **Panel Admin:** `http://localhost/admin/dashboard`
  - Usuario: `admin@reciclin.com`
  - Contraseña: `admin123`
