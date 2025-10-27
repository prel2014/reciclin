# Instrucciones de Uso - pregunta.py

## Descripción
Script interactivo que permite a un administrador seleccionar un profesor, loguearse como ese profesor, y asignar recipuntos a los alumnos mediante la API.

## Características
- ✅ Login automático como administrador
- ✅ Lista de profesores disponibles
- ✅ Login como el profesor seleccionado
- ✅ Lista de alumnos del profesor
- ✅ Asignación de recipuntos por reciclaje
- ✅ Asignación de recipuntos por examen/notas
- ✅ Actualización en tiempo real de recipuntos

## Requisitos
```bash
pip install requests
```

## Ejecución
```bash
python pregunta.py
```

## Flujo de Uso

### 1. Inicio
El script se loguea automáticamente como administrador usando:
- Email: `admin@recipuntos.com`
- Password: `admin123`

### 2. Selección de Profesor
Se muestra la lista de profesores:
```
PROFESORES DISPONIBLES (6):
----------------------------------------------------------------------
1. Profesor Inicial 3 - admin2@recipuntos.com
2. Profesor Inicial 4 - admin3@recipuntos.com
3. Profesor Inicial 2 - admin4@recipuntos.com
4. Profesor Primaria 3 - admin5@recipuntos.com
5. Profesor Primaria 5 - admin6@recipuntos.com
6. Profesor Fisica 1 - admin7@recipuntos.com

0. Salir del sistema
```

Selecciona el número del profesor con el que quieres loguearte.

### 3. Login como Profesor
Ingresa la contraseña del profesor seleccionado:

**Contraseñas de Profesores:**
- admin2@recipuntos.com → `inicial3abc`
- admin3@recipuntos.com → `inicial4abc`
- admin4@recipuntos.com → `inicial2abc`
- admin5@recipuntos.com → `primaria3abc`
- admin6@recipuntos.com → `primaria5abc`
- admin7@recipuntos.com → `fisica1abc`

### 4. Menú del Profesor
```
PROFESOR: [Nombre del Profesor]
----------------------------------------------------------------------
Bienvenido Profesor [Nombre]

Opciones:
  1. Ver y seleccionar alumno
  2. Cerrar sesion
```

### 5. Selección de Alumno
Se muestra la lista de alumnos del profesor con sus recipuntos actuales:
```
TUS ALUMNOS (3):
----------------------------------------------------------------------
1. Alumno Inicial 3 1 - 375 pts
2. Alumno Inicial 3 2 - 280 pts
3. Test API Alumno Prueba - 0 pts
```

Selecciona el número del alumno al que quieres asignar recipuntos.

### 6. Menú de Asignación de Recipuntos
```
MENU: ASIGNAR RECIPUNTOS
----------------------------------------------------------------------
Alumno: [Nombre del Alumno]
Recipuntos actuales: [X] pts

Opciones:
  1. Asignar por Reciclaje
  2. Asignar por Examen/Notas
  3. Volver al menu anterior
```

#### Opción 1: Asignar por Reciclaje
1. Selecciona el material reciclable:
   ```
   MATERIALES RECICLABLES DISPONIBLES:
   1. Botella - 1 pts/unidad
   2. Papel y carton - 1 pts/unidad
   3. Metal - 1 pts/unidad
   4. Otros - 0 pts/unidad
   ```

2. Ingresa la cantidad reciclada (ejemplo: `5`)

3. Ingresa una descripción opcional (puedes presionar Enter para omitir)

4. El sistema asigna los recipuntos automáticamente:
   ```
   EXITO!
   ======================================================================
   Material: Botella
   Cantidad: 5.0 unidades
   Recipuntos asignados: +5 pts

   Recipuntos anteriores: 375 pts
   Recipuntos actuales: 380 pts
   ```

#### Opción 2: Asignar por Examen/Notas
1. Selecciona el tipo de examen:
   ```
   TIPOS DE EXAMEN:
   1. Comunicacion
   2. Matematica
   3. Conocimientos Generales
   ```

2. Ingresa el número de preguntas correctas (0-20)

3. Ingresa observaciones opcionales (puedes presionar Enter para omitir)

4. El sistema registra el examen y asigna recipuntos:
   ```
   EXITO!
   ======================================================================
   Examen: Matemática
   Preguntas correctas: 15/20
   Recipuntos obtenidos: +15 pts

   Recipuntos anteriores: 380 pts
   Recipuntos actuales: 395 pts
   ```

### 7. Navegación
- Después de cada asignación, puedes:
  - Asignar más recipuntos al mismo alumno
  - Volver al menú anterior (opción 3)
  - Seleccionar otro alumno
  - Cerrar sesión del profesor
  - Seleccionar otro profesor

### 8. Salida
- Para salir del sistema, selecciona la opción `0` en el menú principal
- El script cerrará todas las sesiones automáticamente

## Ejemplos de Uso

### Ejemplo 1: Asignar recipuntos por reciclaje
```
1. Seleccionar profesor: 1 (Inicial 3)
2. Ingresar contraseña: inicial3abc
3. Seleccionar alumno: 1
4. Asignar por Reciclaje: 1
5. Seleccionar material: 1 (Botella)
6. Cantidad: 10
7. Descripción: Reciclaje semanal
   → Resultado: +10 recipuntos
```

### Ejemplo 2: Asignar recipuntos por examen
```
1. Seleccionar profesor: 2 (Inicial 4)
2. Ingresar contraseña: inicial4abc
3. Seleccionar alumno: 1
4. Asignar por Examen: 2
5. Tipo de examen: 2 (Matemática)
6. Preguntas correctas: 18
7. Observaciones: Examen mensual - muy buena nota
   → Resultado: +18 recipuntos
```

### Ejemplo 3: Múltiples asignaciones
```
1. Seleccionar profesor: 1
2. Asignar recipuntos por reciclaje a Alumno 1 (+10 pts)
3. Asignar recipuntos por examen a Alumno 1 (+15 pts)
4. Volver y seleccionar Alumno 2
5. Asignar recipuntos por examen a Alumno 2 (+12 pts)
6. Cerrar sesión del profesor
7. Seleccionar otro profesor o salir
```

## Notas Importantes

### Seguridad
- El script valida que el alumno pertenezca al profesor autenticado
- Solo profesores pueden asignar recipuntos
- Todas las operaciones se registran en la base de datos

### Cálculo de Recipuntos
- **Por Reciclaje:** `cantidad × recipuntos_por_cantidad` del material
- **Por Examen:** 1 pregunta correcta = 1 recipunto (máximo 20)

### Actualización en Tiempo Real
- Los recipuntos se actualizan inmediatamente en la base de datos
- El script muestra los recipuntos anteriores y actuales después de cada asignación
- Los recipuntos se mantienen actualizados en memoria durante la sesión

## Solución de Problemas

### Error: "No se pudo iniciar sesión como administrador"
**Causa:** El servidor Laravel no está corriendo
**Solución:** Verifica que Docker esté corriendo: `docker ps`

### Error: "Credenciales incorrectas"
**Causa:** Contraseña incorrecta del profesor
**Solución:** Revisa la lista de contraseñas en la sección "Login como Profesor"

### Error: "No tienes alumnos asignados"
**Causa:** El profesor no tiene alumnos registrados
**Solución:** Primero registra alumnos desde la interfaz web o usando el endpoint de registro

### Error: "Error de conexión"
**Causa:** Problema de red o servidor caído
**Solución:**
1. Verifica que el servidor esté corriendo en http://localhost:8000
2. Prueba accediendo a http://localhost:8000/api/auth/login manualmente

## Características Técnicas

### Endpoints Utilizados
- `POST /api/auth/login` - Login de admin y profesores
- `POST /api/auth/logout` - Logout
- `GET /api/usuarios?tipo=profesor` - Listar profesores
- `GET /api/usuarios?tipo=alumno&cod_profesor={id}` - Listar alumnos del profesor
- `GET /api/materiales` - Listar materiales reciclables
- `POST /api/profesores/alumnos/{id}/asignar-recipuntos` - Asignar por reciclaje
- `POST /api/profesores/alumnos/{id}/registrar-examen` - Asignar por examen

### Tokens de Autenticación
- El script maneja automáticamente los tokens de Sanctum
- Cada profesor tiene su propio token al loguearse
- Los tokens se revocan al cerrar sesión

### Validaciones
- Cantidad de reciclaje debe ser > 0
- Preguntas correctas deben estar entre 0 y 20
- El alumno debe pertenecer al profesor autenticado
- Material reciclable debe existir y estar activo

## Versión
**Versión:** 2.0.0
**Fecha:** 2025-10-23
**Compatibilidad:** API v1.2.0
