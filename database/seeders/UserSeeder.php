<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador
        User::updateOrCreate(
            ['email' => 'admin@mina.local'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'rol' => 'admin',
            ]
        );

        // Operador 1
        User::updateOrCreate(
            ['email' => 'operador1@mina.local'],
            [
                'name' => 'Operador 1',
                'password' => Hash::make('operador123'),
                'rol' => 'operador',
            ]
        );

        // Operador 2
        User::updateOrCreate(
            ['email' => 'operador2@mina.local'],
            [
                'name' => 'Operador 2',
                'password' => Hash::make('operador123'),
                'rol' => 'operador',
            ]
        );
    }
}