<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'stefano@admin.com',
            'password' => Hash::make('password'),
            'level' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Professor User
        User::create([
            'name' => 'João Silva',
            'email' => 'joao.silva@instituicao.edu.br',
            'password' => Hash::make('password'),
            'level' => 'professor',
            'email_verified_at' => now(),
        ]);

        // Create Student User
        User::create([
            'name' => 'Carlos Souza',
            'email' => 'carlos.souza@aluno.edu.br',
            'password' => Hash::make('password'),
            'level' => 'estudante',
            'email_verified_at' => now(),
        ]);
    }
}
