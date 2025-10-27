<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo' => 'required|email',
            'contrasena' => 'required|string|min:6',
        ], [
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'contrasena.required' => 'La contraseña es obligatoria',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buscar usuario por correo
        $usuario = Usuario::where('correo', $request->correo)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if ($usuario && Hash::check($request->contrasena, $usuario->contrasena)) {
            // Verificar si el usuario está activo
            if ($usuario->estado !== 'activo') {
                return redirect()->back()
                    ->withErrors(['correo' => 'Tu cuenta está inactiva. Contacta al administrador.'])
                    ->withInput();
            }

            // Iniciar sesión
            Auth::login($usuario, $request->has('remember'));

            // Regenerar sesión por seguridad
            $request->session()->regenerate();

            // Redirigir según el tipo de usuario
            switch ($usuario->tipo) {
                case 'administrador':
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                case 'profesor':
                    return redirect()->intended('/profesor/dashboard');
                case 'alumno':
                    return redirect()->intended('/alumno/dashboard');
                default:
                    return redirect()->intended('/');
            }
        }

        return redirect()->back()
            ->withErrors(['correo' => 'Las credenciales no coinciden con nuestros registros.'])
            ->withInput();
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar registro
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nick' => 'required|string|max:200|unique:usuario,nick',
            'correo' => 'required|email|max:200|unique:usuario,correo',
            'contrasena' => 'required|string|min:6|confirmed',
            'nombre' => 'required|string|max:200',
            'apellido' => 'required|string|max:250',
            'telefono' => 'nullable|string|max:200',
        ], [
            'nick.required' => 'El nick es obligatorio',
            'nick.unique' => 'Este nick ya está en uso',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'correo.unique' => 'Este correo ya está registrado',
            'contrasena.required' => 'La contraseña es obligatoria',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
            'contrasena.confirmed' => 'Las contraseñas no coinciden',
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Crear usuario
        $usuario = Usuario::create([
            'tipo' => 'usuario', // Por defecto es usuario normal
            'nick' => $request->nick,
            'correo' => $request->correo,
            'contrasena' => $request->contrasena, // Se hasheará automáticamente con el mutator
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'publicaciones' => 0,
            'estado' => 'activo',
        ]);

        // Iniciar sesión automáticamente
        Auth::login($usuario);

        return redirect('/')->with('success', '¡Registro exitoso! Bienvenido a Reciclin.com');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sesión cerrada exitosamente');
    }

    /**
     * Mostrar formulario de recuperación de contraseña
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar enlace de recuperación (simplificado)
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo' => 'required|email|exists:usuario,correo',
        ], [
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'correo.exists' => 'No existe una cuenta con este correo',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Aquí puedes implementar el envío de correo real
        // Por ahora solo retornamos un mensaje de éxito
        return redirect()->back()->with('success', 'Si el correo existe, recibirás instrucciones para recuperar tu contraseña.');
    }
}
