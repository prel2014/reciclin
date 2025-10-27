#!/usr/bin/env python
# -*- coding: utf-8 -*-

import requests
import json

API_URL = "http://localhost:8000/api"

def test_profesor_registra_alumno():
    """
    Prueba que un profesor pueda registrar un alumno via API
    """

    print("=" * 60)
    print("PRUEBA: PROFESOR REGISTRA ALUMNO VIA API")
    print("=" * 60)

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
    print(f"   Token obtenido: {token[:50]}...")

    # 2. Registrar nuevo alumno
    print("\n2. Registrando nuevo alumno...")
    nuevo_alumno = {
        "nick": "test_alumno_api",
        "correo": "test_alumno_api@recipuntos.com",
        "contrasena": "password123",
        "nombre": "Test API",
        "apellido": "Alumno Prueba",
        "telefono": "555-TEST"
    }

    response = requests.post(
        f"{API_URL}/profesores/alumnos",
        json=nuevo_alumno,
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
        alumno = result['data']['alumno']
        print(f"\n   EXITO! Alumno creado:")
        print(f"   - Codigo: {alumno['cod_usuario']}")
        print(f"   - Nombre: {alumno['nombre']} {alumno['apellido']}")
        print(f"   - Nick: {alumno['nick']}")
        print(f"   - Correo: {alumno['correo']}")
        print(f"   - Cod Profesor: {alumno['cod_profesor']} (auto-asignado)")
        print(f"   - Recipuntos: {alumno['recipuntos']}")

        # Verificar que cod_profesor coincide con el profesor autenticado
        if alumno['cod_profesor'] == profesor['cod_usuario']:
            print(f"\n   VERIFICACION OK: cod_profesor coincide con profesor autenticado")
        else:
            print(f"\n   ERROR: cod_profesor NO coincide!")
            print(f"      Esperado: {profesor['cod_usuario']}")
            print(f"      Obtenido: {alumno['cod_profesor']}")
    else:
        print(f"\n   ERROR: {result.get('message')}")
        if 'errors' in result:
            print(f"   Errores de validacion:")
            for field, errors in result['errors'].items():
                print(f"      {field}: {', '.join(errors)}")

    # 3. Verificar que el alumno aparece en la lista del profesor
    print("\n3. Verificando lista de alumnos del profesor...")

    response = requests.get(
        f"{API_URL}/usuarios?tipo=alumno&cod_profesor={profesor['cod_usuario']}",
        headers={"Authorization": f"Bearer {token}"}
    )

    usuarios_result = response.json()
    usuarios = usuarios_result['data'] if isinstance(usuarios_result, dict) and usuarios_result.get('success') else usuarios_result

    # Filtrar alumnos del profesor
    alumnos = [u for u in usuarios if u.get('tipo') == 'alumno' and u.get('cod_profesor') == profesor['cod_usuario']]

    print(f"   Total alumnos del profesor: {len(alumnos)}")

    alumno_creado = next((a for a in alumnos if a.get('nick') == 'test_alumno_api'), None)
    if alumno_creado:
        print(f"   VERIFICACION OK: Alumno 'test_alumno_api' encontrado en la lista")
    else:
        print(f"   ADVERTENCIA: Alumno no encontrado en la lista")

    # 4. Logout
    print("\n4. Logout...")
    requests.post(
        f"{API_URL}/auth/logout",
        headers={"Authorization": f"Bearer {token}"}
    )
    print("   Logout exitoso")

    print("\n" + "=" * 60)
    print("PRUEBA COMPLETADA")
    print("=" * 60)

if __name__ == "__main__":
    test_profesor_registra_alumno()
