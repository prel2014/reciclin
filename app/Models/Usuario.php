<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'usuario';
    protected $primaryKey = 'cod_usuario';

    protected $fillable = [
        'tipo',
        'nick',
        'correo',
        'contrasena',
        'nombre',
        'apellido',
        'telefono',
        'publicaciones',
        'recipuntos',
        'cod_profesor',
        'estado'
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'publicaciones' => 'double',
        'recipuntos' => 'double',
    ];

    // Sobrescribir el método de autenticación para usar 'contrasena' en lugar de 'password'
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // Mutator para hashear la contraseña automáticamente
    public function setContrasenaAttribute($value)
    {
        $this->attributes['contrasena'] = Hash::make($value);
    }

    // Relaciones
    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'cod_usuario', 'cod_usuario');
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'cod_usuario', 'cod_usuario');
    }

    // Relación: Profesor que registró al alumno
    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'cod_profesor', 'cod_usuario');
    }

    // Relación: Alumnos registrados por este profesor
    public function alumnos()
    {
        return $this->hasMany(Usuario::class, 'cod_profesor', 'cod_usuario');
    }

    // Métodos auxiliares
    public function isAdmin()
    {
        return $this->tipo === 'admin';
    }

    public function isProfesor()
    {
        return $this->tipo === 'profesor';
    }

    public function isAlumno()
    {
        return $this->tipo === 'alumno';
    }
}
