<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registro de nuevo usuario
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'tipo' => 'required|in:alumno,profesor,admin',
                'nick' => 'required|string|max:200|unique:usuario,nick',
                'correo' => 'required|email|max:200|unique:usuario,correo',
                'contrasena' => 'required|string|min:6',
                'nombre' => 'required|string|max:200',
                'apellido' => 'required|string|max:250',
                'telefono' => 'nullable|string|max:200',
                'cod_profesor' => 'nullable|exists:usuario,cod_usuario',
            ]);

            // No hashear aquí, el mutator lo hace automáticamente
            $usuario = Usuario::create([
                'tipo' => $validated['tipo'],
                'nick' => $validated['nick'],
                'correo' => $validated['correo'],
                'contrasena' => $validated['contrasena'], // El mutator hará el hash
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'telefono' => $validated['telefono'] ?? null,
                'cod_profesor' => $validated['cod_profesor'] ?? null,
                'publicaciones' => 0,
                'recipuntos' => 0,
                'estado' => 'activo',
            ]);

            // Crear token
            $token = $usuario->createToken('auth-token', [$validated['tipo']])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'data' => [
                    'user' => [
                        'cod_usuario' => $usuario->cod_usuario,
                        'tipo' => $usuario->tipo,
                        'nick' => $usuario->nick,
                        'correo' => $usuario->correo,
                        'nombre' => $usuario->nombre,
                        'apellido' => $usuario->apellido,
                        'telefono' => $usuario->telefono,
                        'recipuntos' => $usuario->recipuntos,
                        'estado' => $usuario->estado,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login de usuario
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'correo' => 'required|email',
                'contrasena' => 'required|string',
            ]);

            $usuario = Usuario::where('correo', $request->correo)->first();

            if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            if ($usuario->estado !== 'activo') {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario inactivo'
                ], 403);
            }

            // Crear token con abilities según el tipo de usuario
            $token = $usuario->createToken('auth-token', [$usuario->tipo])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => [
                    'user' => [
                        'cod_usuario' => $usuario->cod_usuario,
                        'tipo' => $usuario->tipo,
                        'nick' => $usuario->nick,
                        'correo' => $usuario->correo,
                        'nombre' => $usuario->nombre,
                        'apellido' => $usuario->apellido,
                        'telefono' => $usuario->telefono,
                        'recipuntos' => $usuario->recipuntos,
                        'estado' => $usuario->estado,
                        'cod_profesor' => $usuario->cod_profesor,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout de usuario (revoca el token actual)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Revocar el token actual
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout exitoso'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información del usuario autenticado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        try {
            $usuario = $request->user();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'cod_usuario' => $usuario->cod_usuario,
                        'tipo' => $usuario->tipo,
                        'nick' => $usuario->nick,
                        'correo' => $usuario->correo,
                        'nombre' => $usuario->nombre,
                        'apellido' => $usuario->apellido,
                        'telefono' => $usuario->telefono,
                        'recipuntos' => $usuario->recipuntos,
                        'estado' => $usuario->estado,
                        'cod_profesor' => $usuario->cod_profesor,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revocar todos los tokens del usuario
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeAll(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Todos los tokens han sido revocados'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al revocar tokens',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
