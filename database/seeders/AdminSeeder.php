<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin par défaut
        Admin::create([
            'name' => 'Malfrick Djeklounon',
            'email' => 'malfrickdjeklounon@gmail.com',
            'password' => Hash::make('Malfrick123'),
            'role' => 'super_admin',
        ]);

        // Admin par défaut
        Admin::create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // // Autres admins de test
        // Admin::factory(3)->admin()->create();
        // Admin::factory(1)->superAdmin()->create();
    }
}