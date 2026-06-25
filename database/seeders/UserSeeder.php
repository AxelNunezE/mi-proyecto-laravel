<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@cerveceria.com',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'activo' => true
        ]);

        User::create([
            'name' => 'Almacenero',
            'email' => 'almacen@cerveceria.com',
            'password' => Hash::make('almacen123'),
            'rol' => 'almacenero',
            'activo' => true
        ]);

        User::create([
            'name' => 'Vendedor',
            'email' => 'vendedor@cerveceria.com',
            'password' => Hash::make('vendedor123'),
            'rol' => 'vendedor',
            'activo' => true
        ]);
    }
}
