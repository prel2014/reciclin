# 📱 API REST - Sistema Recipuntos

Sistema flexible de API REST con autenticación mediante tokens (Laravel Sanctum).
**Funciona en paralelo con la interfaz web** - puedes usar API o interfaz según necesites.

---

## 🔐 Autenticación

La API usa **Laravel Sanctum** para autenticación mediante tokens Bearer.

### Base URL
```
http://localhost:8000/api
```

### Headers Requeridos
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {tu_token}  # Solo para rutas protegidas
```

---

## 📋 Endpoints Disponibles

### 🔓 **Autenticación (Públicos - Sin Token)**

#### 1. Registrar Usuario
```http
POST /api/auth/register
```

**Body:**
```json
{
  "tipo": "alumno",
  "nick": "juan123",
  "correo": "juan@example.com",
  "contrasena": "password123",
  "nombre": "Juan",
  "apellido": "Pérez",
  "telefono": "987654321",
  "cod_profesor": 2
}
```

**Campos:**
- `tipo` (required): `alumno`, `profesor`, o `admin`
- `nick` (required): Nombre de usuario único
- `correo` (required): Email único
- `contrasena` (required): Mínimo 6 caracteres
- `nombre` (required): Nombre del usuario
- `apellido` (required): Apellido del usuario
- `telefono` (optional): Teléfono
- `cod_profesor` (optional): ID del profesor (solo para alumnos)

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "message": "Usuario registrado exitosamente",
  "data": {
    "user": {
      "cod_usuario": 15,
      "tipo": "alumno",
      "nick": "juan123",
      "correo": "juan@example.com",
      "nombre": "Juan",
      "apellido": "Pérez",
      "telefono": "987654321",
      "recipuntos": 0,
      "estado": "activo"
    },
    "token": "15|Xj3kL9mP2nQ5rS8tV1wY4zA7bC0dE6fH",
    "token_type": "Bearer"
  }
}
```

---

#### 2. Login
```http
POST /api/auth/login
```

**Body:**
```json
{
  "correo": "admin@recipuntos.com",
  "contrasena": "admin123"
}
```

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "user": {
      "cod_usuario": 1,
      "tipo": "administrador",
      "nick": "admin",
      "correo": "admin@recipuntos.com",
      "nombre": "Administrador",
      "apellido": "Sistema",
      "telefono": "999999999",
      "recipuntos": 0,
      "estado": "activo",
      "cod_profesor": null
    },
    "token": "1|IDgV3skIsfyqDhAuKlB7i3HHVspys6XRGL8EyMXZ31eb7fb5",
    "token_type": "Bearer"
  }
}
```

**Errores Comunes:**
```json
// 401 - Credenciales incorrectas
{
  "success": false,
  "message": "Credenciales incorrectas"
}

// 403 - Usuario inactivo
{
  "success": false,
  "message": "Usuario inactivo"
}
```

---

### 🔒 **Rutas Protegidas (Requieren Token)**

Para todas estas rutas, incluye el header:
```
Authorization: Bearer {tu_token}
```

#### 3. Obtener Usuario Actual
```http
GET /api/auth/me
```

**Respuesta (200):**
```json
{
  "success": true,
  "data": {
    "user": {
      "cod_usuario": 1,
      "tipo": "administrador",
      "nick": "admin",
      "correo": "admin@recipuntos.com",
      "nombre": "Administrador",
      "apellido": "Sistema",
      "telefono": "999999999",
      "recipuntos": 0,
      "estado": "activo",
      "cod_profesor": null
    }
  }
}
```

---

#### 4. Logout (Revocar Token Actual)
```http
POST /api/auth/logout
```

**Respuesta (200):**
```json
{
  "success": true,
  "message": "Logout exitoso"
}
```

---

#### 5. Revocar Todos los Tokens
```http
POST /api/auth/revoke-all
```

**Respuesta (200):**
```json
{
  "success": true,
  "message": "Todos los tokens han sido revocados"
}
```

---

#### 6. Registrar Alumno (Solo Profesores)
```http
POST /api/profesores/alumnos
```

**Autenticación:** Requiere token de un usuario tipo 'profesor'

**Body:**
```json
{
  "nick": "estudiante123",
  "correo": "estudiante@escuela.com",
  "contrasena": "password123",
  "nombre": "Carlos",
  "apellido": "Ramírez",
  "telefono": "987654321"
}
```

**Campos:**
- `nick` (required): Nombre de usuario único
- `correo` (required): Email único
- `contrasena` (required): Mínimo 6 caracteres
- `nombre` (required): Nombre del alumno
- `apellido` (required): Apellido del alumno
- `telefono` (optional): Teléfono

**Nota:** El campo `cod_profesor` se asigna automáticamente desde el profesor autenticado. No es necesario enviarlo.

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "message": "Alumno Carlos Ramírez creado exitosamente",
  "data": {
    "alumno": {
      "cod_usuario": 33,
      "tipo": "alumno",
      "nick": "estudiante123",
      "correo": "estudiante@escuela.com",
      "nombre": "Carlos",
      "apellido": "Ramírez",
      "telefono": "987654321",
      "recipuntos": 0,
      "estado": "activo",
      "cod_profesor": 10
    }
  }
}
```

**Errores Comunes:**
```json
// 403 - Usuario no es profesor
{
  "success": false,
  "message": "Solo profesores pueden registrar alumnos"
}

// 422 - Error de validación
{
  "success": false,
  "message": "Error de validación",
  "errors": {
    "correo": ["El correo ya ha sido registrado."],
    "nick": ["El nick ya ha sido registrado."]
  }
}
```

---

#### 7. Asignar Recipuntos por Reciclaje (Solo Profesores)
```http
POST /api/profesores/alumnos/{id}/asignar-recipuntos
```

**Autenticación:** Requiere token de un usuario tipo 'profesor'

**Parámetros URL:**
- `id`: ID del alumno (debe pertenecer al profesor autenticado)

**Body:**
```json
{
  "cod_material": 1,
  "cantidad": 5.0,
  "descripcion": "Reciclaje de botellas en el aula"
}
```

**Campos:**
- `cod_material` (required): ID del material reciclable
- `cantidad` (required): Cantidad reciclada (mínimo 0.01)
- `descripcion` (optional): Descripción adicional

**Nota:** Los recipuntos se calculan como: `cantidad * recipuntos_por_cantidad` del material.

**Respuesta Exitosa (200):**
```json
{
  "success": true,
  "message": "Se asignaron 5 Recipuntos a Carlos Ramírez por reciclar 5 unidades de Botella",
  "data": {
    "alumno": {
      "cod_usuario": 16,
      "nombre": "Carlos",
      "apellido": "Ramírez",
      "recipuntos_anteriores": 355,
      "recipuntos_asignados": 5,
      "recipuntos_actuales": 360
    },
    "material": {
      "cod_material": 1,
      "nombre": "Botella",
      "cantidad": 5,
      "recipuntos_por_cantidad": 1
    }
  }
}
```

**Errores Comunes:**
```json
// 403 - Usuario no es profesor
{
  "success": false,
  "message": "Solo profesores pueden asignar recipuntos"
}

// 404 - Alumno no pertenece al profesor
{
  "success": false,
  "message": "Alumno no encontrado o no pertenece a este profesor"
}
```

---

#### 8. Registrar Examen y Asignar Recipuntos (Solo Profesores)
```http
POST /api/profesores/alumnos/{id}/registrar-examen
```

**Autenticación:** Requiere token de un usuario tipo 'profesor'

**Parámetros URL:**
- `id`: ID del alumno (debe pertenecer al profesor autenticado)

**Body:**
```json
{
  "tipo_examen": "matematica",
  "preguntas_correctas": 15,
  "fecha_examen": "2025-10-23",
  "observaciones": "Examen mensual de octubre"
}
```

**Campos:**
- `tipo_examen` (required): `comunicacion`, `matematica`, o `general`
- `preguntas_correctas` (required): Número entre 0 y 20
- `fecha_examen` (required): Fecha en formato YYYY-MM-DD
- `observaciones` (optional): Observaciones del examen

**Nota:** Se asigna **1 recipunto por cada pregunta correcta**. El examen se registra en la base de datos.

**Respuesta Exitosa (201):**
```json
{
  "success": true,
  "message": "Examen de Matemática registrado. Se asignaron 15 Recipuntos a Carlos Ramírez (15/20 correctas)",
  "data": {
    "examen": {
      "cod_examen": 1,
      "tipo_examen": "matematica",
      "tipo_examen_nombre": "Matemática",
      "preguntas_correctas": 15,
      "recipuntos_obtenidos": 15,
      "fecha_examen": "2025-10-23T00:00:00.000000Z",
      "observaciones": "Examen mensual de octubre"
    },
    "alumno": {
      "cod_usuario": 16,
      "nombre": "Carlos",
      "apellido": "Ramírez",
      "recipuntos_anteriores": 360,
      "recipuntos_obtenidos": 15,
      "recipuntos_actuales": 375
    }
  }
}
```

**Errores Comunes:**
```json
// 403 - Usuario no es profesor
{
  "success": false,
  "message": "Solo profesores pueden registrar exámenes"
}

// 422 - Error de validación
{
  "success": false,
  "message": "Error de validación",
  "errors": {
    "tipo_examen": ["El tipo de examen no es válido."],
    "preguntas_correctas": ["El número de preguntas debe estar entre 0 y 20."]
  }
}
```

---

## 🧪 Ejemplos con cURL

### Registrar un alumno
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "tipo": "alumno",
    "nick": "maria_alumna",
    "correo": "maria@escuela.com",
    "contrasena": "securepass",
    "nombre": "María",
    "apellido": "González",
    "telefono": "987654321",
    "cod_profesor": 2
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "correo": "admin@recipuntos.com",
    "contrasena": "admin123"
  }'
```

### Obtener perfil (con token)
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|IDgV3skIsfyqDhAuKlB7i3HHVspys6XRGL8EyMXZ31eb7fb5"
```

### Logout
```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {tu_token}"
```

### Profesor Registra Alumno
```bash
curl -X POST http://localhost:8000/api/profesores/alumnos \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {token_profesor}" \
  -d '{
    "nick": "estudiante123",
    "correo": "estudiante@escuela.com",
    "contrasena": "password123",
    "nombre": "Carlos",
    "apellido": "Ramírez",
    "telefono": "987654321"
  }'
```

### Asignar Recipuntos por Reciclaje
```bash
curl -X POST http://localhost:8000/api/profesores/alumnos/16/asignar-recipuntos \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {token_profesor}" \
  -d '{
    "cod_material": 1,
    "cantidad": 5.0,
    "descripcion": "Reciclaje de botellas en el aula"
  }'
```

### Registrar Examen y Asignar Recipuntos
```bash
curl -X POST http://localhost:8000/api/profesores/alumnos/16/registrar-examen \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {token_profesor}" \
  -d '{
    "tipo_examen": "matematica",
    "preguntas_correctas": 15,
    "fecha_examen": "2025-10-23",
    "observaciones": "Examen mensual de octubre"
  }'
```

---

## 🧪 Ejemplos con JavaScript/Fetch

### Login
```javascript
const login = async () => {
  const response = await fetch('http://localhost:8000/api/auth/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      correo: 'admin@recipuntos.com',
      contrasena: 'admin123'
    })
  });

  const data = await response.json();

  if (data.success) {
    // Guardar token
    localStorage.setItem('token', data.data.token);
    localStorage.setItem('user', JSON.stringify(data.data.user));
    console.log('Login exitoso', data.data.user);
  }

  return data;
};
```

### Petición Autenticada
```javascript
const getProfile = async () => {
  const token = localStorage.getItem('token');

  const response = await fetch('http://localhost:8000/api/auth/me', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`
    }
  });

  const data = await response.json();
  return data;
};
```

---

## 🧪 Ejemplos con Python/Requests

### Login
```python
import requests

def login(correo, contrasena):
    url = "http://localhost:8000/api/auth/login"
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json"
    }
    data = {
        "correo": correo,
        "contrasena": contrasena
    }

    response = requests.post(url, json=data, headers=headers)
    return response.json()

# Uso
result = login("admin@recipuntos.com", "admin123")
if result['success']:
    token = result['data']['token']
    print(f"Token: {token}")
```

### Petición Autenticada
```python
def get_profile(token):
    url = "http://localhost:8000/api/auth/me"
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": f"Bearer {token}"
    }

    response = requests.get(url, headers=headers)
    return response.json()

# Uso
profile = get_profile(token)
print(profile)
```

### Profesor Registra Alumno
```python
def registrar_alumno(token_profesor, datos_alumno):
    url = "http://localhost:8000/api/profesores/alumnos"
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": f"Bearer {token_profesor}"
    }

    response = requests.post(url, json=datos_alumno, headers=headers)
    return response.json()

# Uso
nuevo_alumno = {
    "nick": "estudiante123",
    "correo": "estudiante@escuela.com",
    "contrasena": "password123",
    "nombre": "Carlos",
    "apellido": "Ramírez",
    "telefono": "987654321"
}

result = registrar_alumno(token_profesor, nuevo_alumno)
if result['success']:
    print(f"Alumno creado: {result['data']['alumno']['nombre']}")
```

### Asignar Recipuntos por Reciclaje
```python
def asignar_recipuntos_reciclaje(token_profesor, alumno_id, datos):
    url = f"http://localhost:8000/api/profesores/alumnos/{alumno_id}/asignar-recipuntos"
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": f"Bearer {token_profesor}"
    }

    response = requests.post(url, json=datos, headers=headers)
    return response.json()

# Uso
datos_reciclaje = {
    "cod_material": 1,
    "cantidad": 5.0,
    "descripcion": "Reciclaje de botellas en el aula"
}

result = asignar_recipuntos_reciclaje(token_profesor, alumno_id=16, datos=datos_reciclaje)
if result['success']:
    data = result['data']
    print(f"Recipuntos asignados: {data['alumno']['recipuntos_asignados']}")
    print(f"Recipuntos actuales: {data['alumno']['recipuntos_actuales']}")
```

### Registrar Examen y Asignar Recipuntos
```python
from datetime import date

def registrar_examen(token_profesor, alumno_id, datos_examen):
    url = f"http://localhost:8000/api/profesores/alumnos/{alumno_id}/registrar-examen"
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": f"Bearer {token_profesor}"
    }

    response = requests.post(url, json=datos_examen, headers=headers)
    return response.json()

# Uso
datos_examen = {
    "tipo_examen": "matematica",
    "preguntas_correctas": 15,
    "fecha_examen": str(date.today()),
    "observaciones": "Examen mensual de octubre"
}

result = registrar_examen(token_profesor, alumno_id=16, datos_examen=datos_examen)
if result['success']:
    data = result['data']
    print(f"Examen registrado: {data['examen']['tipo_examen_nombre']}")
    print(f"Preguntas correctas: {data['examen']['preguntas_correctas']}/20")
    print(f"Recipuntos obtenidos: {data['alumno']['recipuntos_obtenidos']}")
    print(f"Recipuntos actuales: {data['alumno']['recipuntos_actuales']}")
```

---

## 🔑 Credenciales de Prueba

### Administrador
```
Correo: admin@recipuntos.com
Contraseña: admin123
```

### Profesores
```
Correo: admin2@recipuntos.com | Contraseña: inicial3abc
Correo: admin3@recipuntos.com | Contraseña: inicial4abc
Correo: admin5@recipuntos.com | Contraseña: primaria3abc
```

---

## ⚠️ Códigos de Estado HTTP

| Código | Significado |
|--------|-------------|
| 200 | OK - Petición exitosa |
| 201 | Created - Recurso creado exitosamente |
| 401 | Unauthorized - Token inválido o credenciales incorrectas |
| 403 | Forbidden - Usuario inactivo o sin permisos suficientes |
| 422 | Unprocessable Entity - Error de validación |
| 500 | Internal Server Error - Error del servidor |

---

## 🛡️ Seguridad

### Mejores Prácticas

1. **Almacenar tokens de forma segura**
   - En aplicaciones móviles: Keychain (iOS) / Keystore (Android)
   - En web: HttpOnly cookies o localStorage (con precaución)

2. **Revocar tokens**
   - Al cerrar sesión: `POST /api/auth/logout`
   - En caso de compromiso: `POST /api/auth/revoke-all`

3. **HTTPS en producción**
   - Siempre usa HTTPS en producción
   - Los tokens viajan en headers

4. **Validar respuestas**
   - Siempre verifica `response.success === true`
   - Maneja errores apropiadamente

---

## 🚀 Flexibilidad del Sistema

Este sistema es **completamente flexible**:

✅ **Puedes usar solo la API** (sin interfaz web)
✅ **Puedes usar solo la interfaz web** (sin API)
✅ **Puedes usar ambos simultáneamente** (híbrido)

Los usuarios pueden:
- Iniciar sesión desde la web → Usar el sistema web
- Iniciar sesión desde la API → Usar el sistema vía API
- Alternar entre ambos sin problemas

---

## 📱 Casos de Uso

### App Móvil
1. Usuario hace login → Recibe token
2. App guarda token en almacenamiento seguro
3. Todas las peticiones incluyen: `Authorization: Bearer {token}`

### SPA (Single Page Application)
1. Usuario hace login → Recibe token
2. App guarda token en localStorage/sessionStorage
3. Axios/Fetch configurado para incluir token automáticamente

### Sistema Híbrido
1. Administradores usan interfaz web
2. Alumnos usan app móvil con API
3. Profesores usan ambos según conveniencia

---

## 🆘 Soporte

Si necesitas más endpoints API (materiales, útiles, canjes, etc.), avísame y los implemento.

**Endpoints disponibles actualmente:**
- ✅ Autenticación (login, register, logout, me, revoke-all)
- ✅ Gestión de Alumnos (profesores pueden registrar alumnos)
- ✅ Asignación de Recipuntos por Reciclaje (profesores)
- ✅ Registro de Exámenes y Asignación de Recipuntos por Notas (profesores)
- ✅ Materiales reciclables (CRUD completo)
- ✅ Útiles escolares (CRUD completo)
- ✅ Usuarios (listar y consultar)
- ⏳ Canjes (próximamente)

---

**Versión:** 1.2.0
**Última actualización:** 2025-10-23
