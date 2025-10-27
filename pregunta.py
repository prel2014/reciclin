#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Demo API - Sistema Recipuntos
Menu interactivo para loguearse como admin, seleccionar profesor,
loguearse como profesor y asignar recipuntos a alumnos
"""

import requests
import json
from datetime import date

# Configuracion
API_URL = "http://192.168.1.53:8000/api"
ADMIN_EMAIL = "admin@recipuntos.com"
ADMIN_PASSWORD = "admin123"

def print_separator(title="", char="="):
    """Imprime un separador visual"""
    print("\n" + char*70)
    if title:
        print(f"  {title}")
        print(char*70)

def login(correo, contrasena):
    """Login generico y obtener token"""
    url = f"{API_URL}/auth/login"
    payload = {
        "correo": correo,
        "contrasena": contrasena
    }
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json"
    }

    try:
        response = requests.post(url, json=payload, headers=headers)
        
        # DEBUG: Imprimir respuesta completa
        print(f"\nDEBUG - Status Code: {response.status_code}")
        print(f"DEBUG - Response Text: {response.text}")
        print(f"DEBUG - Response Headers: {dict(response.headers)}")
        
        data = response.json()

        if data.get('success'):
            return data['data']['token'], data['data']['user']
        else:
            print(f"\nERROR - Error en login: {data.get('message')}")
            return None, None

    except Exception as e:
        print(f"\nERROR - Error de conexion: {str(e)}")
        return None, None

def logout(token):
    """Cerrar sesion y revocar token"""
    url = f"{API_URL}/auth/logout"
    headers = {
        "Authorization": f"Bearer {token}"
    }

    try:
        requests.post(url, headers=headers)
    except:
        pass

def listar_profesores(token):
    """Listar todos los profesores"""
    url = f"{API_URL}/usuarios?tipo=profesor"
    headers = {
        "Accept": "application/json",
        "Authorization": f"Bearer {token}"
    }

    try:
        response = requests.get(url, headers=headers)
        result = response.json()

        if isinstance(result, dict) and result.get('success'):
            usuarios = result['data']
        elif isinstance(result, list):
            usuarios = result
        else:
            return []

        # Filtrar solo profesores
        profesores = [u for u in usuarios if u.get('tipo') == 'profesor']
        return profesores

    except Exception as e:
        print(f"\nERROR - Error al listar profesores: {str(e)}")
        return []

def listar_alumnos_profesor(token, cod_profesor):
    """Listar alumnos asignados a un profesor especifico"""
    url = f"{API_URL}/usuarios?tipo=alumno&cod_profesor={cod_profesor}"
    headers = {
        "Accept": "application/json",
        "Authorization": f"Bearer {token}"
    }

    try:
        response = requests.get(url, headers=headers)
        result = response.json()

        if isinstance(result, dict) and result.get('success'):
            usuarios = result['data']
        elif isinstance(result, list):
            usuarios = result
        else:
            return []

        # Filtrar alumnos del profesor
        alumnos = [u for u in usuarios if u.get('tipo') == 'alumno' and u.get('cod_profesor') == cod_profesor]
        return alumnos

    except Exception as e:
        print(f"\nERROR - Error al listar alumnos: {str(e)}")
        return []

def listar_materiales(token):
    """Listar materiales reciclables"""
    url = f"{API_URL}/materiales"
    headers = {
        "Accept": "application/json",
        "Authorization": f"Bearer {token}"
    }

    try:
        response = requests.get(url, headers=headers)
        data = response.json()

        if data.get('success'):
            return data['data']
        return []

    except Exception as e:
        print(f"\nERROR - Error al listar materiales: {str(e)}")
        return []

def asignar_recipuntos_reciclaje(token, alumno_id, cod_material, cantidad, descripcion=""):
    """Asignar recipuntos por reciclaje"""
    url = f"{API_URL}/profesores/alumnos/{alumno_id}/asignar-recipuntos"
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": f"Bearer {token}"
    }
    payload = {
        "cod_material": cod_material,
        "cantidad": cantidad,
        "descripcion": descripcion
    }

    try:
        response = requests.post(url, json=payload, headers=headers)
        return response.json()
    except Exception as e:
        return {"success": False, "message": f"Error de conexion: {str(e)}"}

def registrar_examen(token, alumno_id, tipo_examen, preguntas_correctas, observaciones=""):
    """Registrar examen y asignar recipuntos"""
    url = f"{API_URL}/profesores/alumnos/{alumno_id}/registrar-examen"
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "Authorization": f"Bearer {token}"
    }
    payload = {
        "tipo_examen": tipo_examen,
        "preguntas_correctas": preguntas_correctas,
        "fecha_examen": str(date.today()),
        "observaciones": observaciones
    }

    try:
        response = requests.post(url, json=payload, headers=headers)
        return response.json()
    except Exception as e:
        return {"success": False, "message": f"Error de conexion: {str(e)}"}

def menu_asignar_recipuntos(token_profesor, alumno):
    """Menu para elegir como asignar recipuntos"""
    while True:
        print_separator("MENU: ASIGNAR RECIPUNTOS", "-")
        print(f"\nAlumno: {alumno['nombre']} {alumno['apellido']}")
        print(f"Recipuntos actuales: {alumno['recipuntos']} pts")
        print("\nOpciones:")
        print("  1. Asignar por Reciclaje")
        print("  2. Asignar por Examen/Notas")
        print("  3. Volver al menu anterior")
        print("-" * 70)

        opcion = input("\nSelecciona una opcion (1-3): ").strip()

        if opcion == "1":
            # Asignar por reciclaje
            materiales = listar_materiales(token_profesor)

            if not materiales:
                print("\nNo hay materiales reciclables disponibles")
                input("\nPresiona Enter para continuar...")
                continue

            print("\n" + "-" * 70)
            print("MATERIALES RECICLABLES DISPONIBLES:")
            print("-" * 70)
            for i, mat in enumerate(materiales, 1):
                print(f"{i}. {mat['nombre']} - {mat['recipuntos_por_cantidad']} pts/unidad")

            mat_opcion = input(f"\nSelecciona material (1-{len(materiales)}): ").strip()

            try:
                mat_idx = int(mat_opcion) - 1
                if mat_idx < 0 or mat_idx >= len(materiales):
                    print("\nOpcion invalida")
                    input("\nPresiona Enter para continuar...")
                    continue

                material_seleccionado = materiales[mat_idx]

                cantidad = input(f"\nCantidad de {material_seleccionado['nombre']} reciclada: ").strip()
                cantidad = float(cantidad)

                if cantidad <= 0:
                    print("\nLa cantidad debe ser mayor a 0")
                    input("\nPresiona Enter para continuar...")
                    continue

                descripcion = input("Descripcion (opcional, Enter para omitir): ").strip()

                # Asignar recipuntos
                print("\nAsignando recipuntos...")
                result = asignar_recipuntos_reciclaje(
                    token_profesor,
                    alumno['cod_usuario'],
                    material_seleccionado['cod_material'],
                    cantidad,
                    descripcion
                )

                if result.get('success'):
                    data = result['data']
                    print("\n" + "=" * 70)
                    print("  EXITO!")
                    print("=" * 70)
                    print(f"\nMaterial: {data['material']['nombre']}")
                    print(f"Cantidad: {data['material']['cantidad']} unidades")
                    print(f"Recipuntos asignados: +{data['alumno']['recipuntos_asignados']} pts")
                    print(f"\nRecipuntos anteriores: {data['alumno']['recipuntos_anteriores']} pts")
                    print(f"Recipuntos actuales: {data['alumno']['recipuntos_actuales']} pts")

                    # Actualizar recipuntos del alumno en memoria
                    alumno['recipuntos'] = data['alumno']['recipuntos_actuales']
                else:
                    print(f"\nERROR: {result.get('message')}")

                input("\nPresiona Enter para continuar...")

            except ValueError:
                print("\nEntrada invalida")
                input("\nPresiona Enter para continuar...")
            except Exception as e:
                print(f"\nError: {str(e)}")
                input("\nPresiona Enter para continuar...")

        elif opcion == "2":
            # Asignar por examen
            print("\n" + "-" * 70)
            print("TIPOS DE EXAMEN:")
            print("-" * 70)
            print("1. Comunicacion")
            print("2. Matematica")
            print("3. Conocimientos Generales")

            tipo_opcion = input("\nSelecciona tipo de examen (1-3): ").strip()

            tipos = {
                "1": "comunicacion",
                "2": "matematica",
                "3": "general"
            }

            if tipo_opcion not in tipos:
                print("\nOpcion invalida")
                input("\nPresiona Enter para continuar...")
                continue

            tipo_examen = tipos[tipo_opcion]

            try:
                preguntas = input("\nNumero de preguntas correctas (0-20): ").strip()
                preguntas = int(preguntas)

                if preguntas < 0 or preguntas > 20:
                    print("\nDebe estar entre 0 y 20")
                    input("\nPresiona Enter para continuar...")
                    continue

                observaciones = input("Observaciones (opcional, Enter para omitir): ").strip()

                # Registrar examen
                print("\nRegistrando examen...")
                result = registrar_examen(
                    token_profesor,
                    alumno['cod_usuario'],
                    tipo_examen,
                    preguntas,
                    observaciones
                )

                if result.get('success'):
                    data = result['data']
                    print("\n" + "=" * 70)
                    print("  EXITO!")
                    print("=" * 70)
                    print(f"\nExamen: {data['examen']['tipo_examen_nombre']}")
                    print(f"Preguntas correctas: {data['examen']['preguntas_correctas']}/20")
                    print(f"Recipuntos obtenidos: +{data['alumno']['recipuntos_obtenidos']} pts")
                    print(f"\nRecipuntos anteriores: {data['alumno']['recipuntos_anteriores']} pts")
                    print(f"Recipuntos actuales: {data['alumno']['recipuntos_actuales']} pts")

                    # Actualizar recipuntos del alumno en memoria
                    alumno['recipuntos'] = data['alumno']['recipuntos_actuales']
                else:
                    print(f"\nERROR: {result.get('message')}")

                input("\nPresiona Enter para continuar...")

            except ValueError:
                print("\nEntrada invalida")
                input("\nPresiona Enter para continuar...")
            except Exception as e:
                print(f"\nError: {str(e)}")
                input("\nPresiona Enter para continuar...")

        elif opcion == "3":
            break
        else:
            print("\nOpcion invalida")
            input("\nPresiona Enter para continuar...")

def menu_profesor(token_profesor, profesor):
    """Menu del profesor para seleccionar alumno"""
    while True:
        print_separator(f"PROFESOR: {profesor['nombre']} {profesor['apellido']}")
        print(f"\nBienvenido Profesor {profesor['nombre']} {profesor['apellido']}")
        print("\nOpciones:")
        print("  1. Ver y seleccionar alumno")
        print("  2. Cerrar sesion")
        print("-" * 70)

        opcion = input("\nSelecciona una opcion (1-2): ").strip()

        if opcion == "1":
            # Listar alumnos
            alumnos = listar_alumnos_profesor(token_profesor, profesor['cod_usuario'])

            if not alumnos:
                print("\nNo tienes alumnos asignados")
                input("\nPresiona Enter para continuar...")
                continue

            print("\n" + "-" * 70)
            print(f"TUS ALUMNOS ({len(alumnos)}):")
            print("-" * 70)
            for i, alumno in enumerate(alumnos, 1):
                print(f"{i}. {alumno['nombre']} {alumno['apellido']} - {alumno['recipuntos']} pts")

            alumno_opcion = input(f"\nSelecciona alumno (1-{len(alumnos)}) o 0 para volver: ").strip()

            try:
                alumno_idx = int(alumno_opcion)

                if alumno_idx == 0:
                    continue

                if alumno_idx < 1 or alumno_idx > len(alumnos):
                    print("\nOpcion invalida")
                    input("\nPresiona Enter para continuar...")
                    continue

                alumno_seleccionado = alumnos[alumno_idx - 1]
                menu_asignar_recipuntos(token_profesor, alumno_seleccionado)

            except ValueError:
                print("\nEntrada invalida")
                input("\nPresiona Enter para continuar...")

        elif opcion == "2":
            print("\nCerrando sesion...")
            logout(token_profesor)
            break
        else:
            print("\nOpcion invalida")
            input("\nPresiona Enter para continuar...")

def main():
    """Funcion principal"""
    print("\n")
    print("=" * 70)
    print("       SISTEMA RECIPUNTOS - MENU INTERACTIVO")
    print("       Asignacion de Recipuntos via API")
    print("=" * 70)

    # Login como admin
    print("\n1. Iniciando sesion como ADMINISTRADOR...")
    token_admin, user_admin = login(ADMIN_EMAIL, ADMIN_PASSWORD)

    if not token_admin:
        print("\nERROR - No se pudo iniciar sesion como administrador")
        print("Verifica que el servidor este corriendo en http://localhost:8000")
        return

    print(f"\n   OK - Sesion iniciada como {user_admin['nombre']} {user_admin['apellido']}")

    while True:
        # Listar profesores
        print_separator("MENU PRINCIPAL - SELECCIONAR PROFESOR")
        profesores = listar_profesores(token_admin)

        if not profesores:
            print("\nNo se encontraron profesores")
            break

        print(f"\nPROFESORES DISPONIBLES ({len(profesores)}):")
        print("-" * 70)
        for i, prof in enumerate(profesores, 1):
            print(f"{i}. {prof['nombre']} {prof['apellido']} - {prof['correo']}")

        print("\n0. Salir del sistema")
        print("-" * 70)

        opcion = input(f"\nCon que profesor te quieres loguear? (0-{len(profesores)}): ").strip()

        try:
            prof_idx = int(opcion)

            if prof_idx == 0:
                print("\nSaliendo del sistema...")
                logout(token_admin)
                break

            if prof_idx < 1 or prof_idx > len(profesores):
                print("\nOpcion invalida")
                input("\nPresiona Enter para continuar...")
                continue

            profesor_elegido = profesores[prof_idx - 1]

            # Pedir contrasena del profesor
            print(f"\n{'='*70}")
            print(f"  LOGIN COMO PROFESOR: {profesor_elegido['nombre']} {profesor_elegido['apellido']}")
            print("="*70)
            print(f"\nEmail: {profesor_elegido['correo']}")
            contrasena_prof = input("Contrasena: ").strip()

            # Login como profesor
            token_profesor, user_profesor = login(profesor_elegido['correo'], contrasena_prof)

            if not token_profesor:
                print("\nCredenciales incorrectas")
                input("\nPresiona Enter para continuar...")
                continue

            print(f"\n   OK - Sesion iniciada como {user_profesor['nombre']} {user_profesor['apellido']}")
            input("\nPresiona Enter para continuar...")

            # Menu del profesor
            menu_profesor(token_profesor, user_profesor)

        except ValueError:
            print("\nEntrada invalida")
            input("\nPresiona Enter para continuar...")
        except KeyboardInterrupt:
            print("\n\nInterrumpido por el usuario")
            logout(token_admin)
            break
        except Exception as e:
            print(f"\nError inesperado: {str(e)}")
            input("\nPresiona Enter para continuar...")

    print("\n" + "=" * 70)
    print("  Gracias por usar el Sistema Recipuntos!")
    print("=" * 70 + "\n")

if __name__ == "__main__":
    main()
