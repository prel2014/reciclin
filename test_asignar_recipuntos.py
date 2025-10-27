#!/usr/bin/env python
# -*- coding: utf-8 -*-

import requests
import json
from datetime import date

API_URL = "http://localhost:8000/api"

def test_asignar_recipuntos():
    """
    Prueba que un profesor pueda asignar recipuntos por reciclaje y por examen
    """

    print("=" * 70)
    print("PRUEBA: PROFESOR ASIGNA RECIPUNTOS (RECICLAJE Y EXAMENES)")
    print("=" * 70)

    # 1. Login como profesor
    print("\n1. Login como profesor (Inicial 3)...")
    login_data = {
        "correo": "admin2@recipuntos.com",
        "contrasena": "inicial3abc"
    }

    response = requests.post(
        f"{API_URL}/auth/login",
        json=login_data,
        headers={"Content-Type": "application/json", "Accept": "application/json"}
    )

    login_result = response.json()

    if not login_result.get('success'):
        print(f"ERROR: Login fallido - {login_result.get('message')}")
        return

    token = login_result['data']['token']
    profesor = login_result['data']['user']

    print(f"   Login exitoso!")
    print(f"   Profesor: {profesor['nombre']} {profesor['apellido']}")
    print(f"   Codigo: {profesor['cod_usuario']}")

    # 2. Obtener lista de alumnos del profesor
    print("\n2. Obteniendo lista de alumnos del profesor...")
    response = requests.get(
        f"{API_URL}/usuarios?tipo=alumno&cod_profesor={profesor['cod_usuario']}",
        headers={"Authorization": f"Bearer {token}"}
    )

    usuarios_result = response.json()
    usuarios = usuarios_result['data'] if isinstance(usuarios_result, dict) and usuarios_result.get('success') else usuarios_result
    alumnos = [u for u in usuarios if u.get('tipo') == 'alumno' and u.get('cod_profesor') == profesor['cod_usuario']]

    if not alumnos:
        print("   ERROR: No hay alumnos para este profesor")
        return

    alumno = alumnos[0]
    print(f"   Alumno seleccionado: {alumno['nombre']} {alumno['apellido']}")
    print(f"   Codigo: {alumno['cod_usuario']}")
    print(f"   Recipuntos actuales: {alumno['recipuntos']}")

    # 3. Obtener lista de materiales reciclables
    print("\n3. Obteniendo materiales reciclables...")
    response = requests.get(
        f"{API_URL}/materiales",
        headers={"Authorization": f"Bearer {token}"}
    )

    materiales_result = response.json()
    materiales = materiales_result['data'] if materiales_result.get('success') else materiales_result

    if not materiales:
        print("   ERROR: No hay materiales reciclables")
        return

    material = materiales[0]
    print(f"   Material seleccionado: {material['nombre']}")
    print(f"   Codigo: {material['cod_material']}")
    print(f"   Recipuntos por cantidad: {material['recipuntos_por_cantidad']}")

    # 4. PRUEBA 1: Asignar recipuntos por reciclaje
    print("\n" + "=" * 70)
    print("PRUEBA 1: ASIGNAR RECIPUNTOS POR RECICLAJE")
    print("=" * 70)

    asignacion_data = {
        "cod_material": material['cod_material'],
        "cantidad": 5.0,
        "descripcion": "Prueba de asignacion via API"
    }

    print(f"\n   Asignando recipuntos...")
    print(f"   - Material: {material['nombre']}")
    print(f"   - Cantidad: {asignacion_data['cantidad']}")
    print(f"   - Recipuntos esperados: {asignacion_data['cantidad'] * material['recipuntos_por_cantidad']}")

    response = requests.post(
        f"{API_URL}/profesores/alumnos/{alumno['cod_usuario']}/asignar-recipuntos",
        json=asignacion_data,
        headers={
            "Authorization": f"Bearer {token}",
            "Content-Type": "application/json",
            "Accept": "application/json"
        }
    )

    result = response.json()

    print(f"\n   Status Code: {response.status_code}")
    print(f"   Respuesta:")
    print(json.dumps(result, indent=4, ensure_ascii=False))

    if result.get('success'):
        data = result['data']
        print(f"\n   EXITO! Recipuntos asignados por reciclaje:")
        print(f"   - Recipuntos anteriores: {data['alumno']['recipuntos_anteriores']}")
        print(f"   - Recipuntos asignados: {data['alumno']['recipuntos_asignados']}")
        print(f"   - Recipuntos actuales: {data['alumno']['recipuntos_actuales']}")
        recipuntos_despues_reciclaje = data['alumno']['recipuntos_actuales']
    else:
        print(f"\n   ERROR: {result.get('message')}")
        return

    # 5. PRUEBA 2: Registrar examen y asignar recipuntos por notas
    print("\n" + "=" * 70)
    print("PRUEBA 2: ASIGNAR RECIPUNTOS POR EXAMEN")
    print("=" * 70)

    examen_data = {
        "tipo_examen": "matematica",
        "preguntas_correctas": 15,
        "fecha_examen": str(date.today()),
        "observaciones": "Examen de prueba via API"
    }

    print(f"\n   Registrando examen...")
    print(f"   - Tipo: Matematica")
    print(f"   - Preguntas correctas: {examen_data['preguntas_correctas']}/20")
    print(f"   - Recipuntos esperados: {examen_data['preguntas_correctas']}")

    response = requests.post(
        f"{API_URL}/profesores/alumnos/{alumno['cod_usuario']}/registrar-examen",
        json=examen_data,
        headers={
            "Authorization": f"Bearer {token}",
            "Content-Type": "application/json",
            "Accept": "application/json"
        }
    )

    result = response.json()

    print(f"\n   Status Code: {response.status_code}")
    print(f"   Respuesta:")
    print(json.dumps(result, indent=4, ensure_ascii=False))

    if result.get('success'):
        data = result['data']
        print(f"\n   EXITO! Examen registrado y recipuntos asignados:")
        print(f"   - Examen: {data['examen']['tipo_examen_nombre']}")
        print(f"   - Codigo examen: {data['examen']['cod_examen']}")
        print(f"   - Preguntas correctas: {data['examen']['preguntas_correctas']}/20")
        print(f"   - Recipuntos anteriores: {data['alumno']['recipuntos_anteriores']}")
        print(f"   - Recipuntos obtenidos: {data['alumno']['recipuntos_obtenidos']}")
        print(f"   - Recipuntos actuales: {data['alumno']['recipuntos_actuales']}")
        recipuntos_despues_examen = data['alumno']['recipuntos_actuales']

        # Verificar incremento correcto
        if data['alumno']['recipuntos_anteriores'] == recipuntos_despues_reciclaje:
            print(f"\n   VERIFICACION OK: Recipuntos antes del examen coinciden con despues del reciclaje")
        else:
            print(f"\n   ERROR: Discrepancia en recipuntos")
    else:
        print(f"\n   ERROR: {result.get('message')}")
        return

    # 6. Verificar recipuntos finales
    print("\n" + "=" * 70)
    print("VERIFICACION FINAL")
    print("=" * 70)

    response = requests.get(
        f"{API_URL}/usuarios/{alumno['cod_usuario']}",
        headers={"Authorization": f"Bearer {token}"}
    )

    usuario_final = response.json()['data']
    print(f"\n   Recipuntos iniciales: {alumno['recipuntos']}")
    print(f"   Recipuntos finales: {usuario_final['recipuntos']}")
    print(f"   Incremento total: {usuario_final['recipuntos'] - alumno['recipuntos']}")

    # 7. Logout
    print("\n7. Logout...")
    requests.post(
        f"{API_URL}/auth/logout",
        headers={"Authorization": f"Bearer {token}"}
    )
    print("   Logout exitoso")

    print("\n" + "=" * 70)
    print("PRUEBAS COMPLETADAS")
    print("=" * 70)

if __name__ == "__main__":
    test_asignar_recipuntos()
