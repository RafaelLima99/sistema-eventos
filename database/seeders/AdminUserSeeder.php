<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Cria (ou atualiza) o usuário administrador para acesso inicial ao sistema.
     */
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => 'admin@unu.com.br']);

        // "role" não é mass-assignable, por isso o forceFill.
        $admin->forceFill([
            'name' => 'Administrador',
            'password' => 'password',
            'role' => 'admin',
            'email_verified_at' => now(),
        ])->save();
    }
}
