<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Intenta iniciar sesión con las credenciales proporcionadas
     * 
     * @param string $email
     * @param string $password
     * @return array ['success' => bool, 'user' => User|null, 'message' => string]
     */
    public function login($email, $password)
    {
        $user = User::where('email', $email)->where('activo', true)->first();

        if (!$user) {
            return [
                'success' => false,
                'user' => null,
                'message' => 'Correo electrónico o contraseña incorrectos.'
            ];
        }

        if (!Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'user' => null,
                'message' => 'Correo electrónico o contraseña incorrectos.'
            ];
        }

        // Actualizar último login
        $user->update(['ultimo_login' => now()]);

        // Iniciar sesión
        Auth::login($user);

        return [
            'success' => true,
            'user' => $user,
            'message' => 'Bienvenido ' . $user->name
        ];
    }

    /**
     * Registra un nuevo usuario
     * 
     * @param array $data
     * @return array ['success' => bool, 'user' => User|null, 'message' => string]
     */
    public function register($data)
    {
        // Verificar si el email ya existe
        $existingUser = User::where('email', $data['email'])->first();

        if ($existingUser) {
            return [
                'success' => false,
                'user' => null,
                'message' => 'Este correo electrónico ya está registrado.'
            ];
        }

        // Crear usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'telefono' => $data['telefono'] ?? null,
            'rol' => 'almacenero', // Por defecto
            'activo' => true
        ]);

        // Iniciar sesión automáticamente
        Auth::login($user);

        return [
            'success' => true,
            'user' => $user,
            'message' => '¡Registro exitoso! Bienvenido ' . $user->name
        ];
    }

    /**
     * Cierra la sesión del usuario
     * 
     * @return void
     */
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * Obtiene el usuario actualmente autenticado
     * 
     * @return User|null
     */
    public function getCurrentUser()
    {
        return Auth::user();
    }

    /**
     * Verifica si el usuario tiene un rol específico
     * 
     * @param string $rol
     * @return bool
     */
    public function hasRole($rol)
    {
        $user = Auth::user();
        if (!$user) return false;

        $roles = ['vendedor' => 1, 'almacenero' => 2, 'admin' => 3];
        return $roles[$user->rol] >= $roles[$rol];
    }
}
