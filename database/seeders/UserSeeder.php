<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Administrator SIADIL',
            'username' => 'admin',
            'email'    => 'admin@ptunbandarlampung.go.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Room Accounts
        $users = [
            ['name' => 'PTIP', 'username' => 'ptip', 'email' => 'ptip@ptun.go.id'],
            ['name' => 'Kepegawaian', 'username' => 'kepegawaian', 'email' => 'kepegawaian@ptun.go.id'],
            ['name' => 'Umum Keuangan', 'username' => 'umum-keuangan', 'email' => 'umum@ptun.go.id'],
            ['name' => 'Paniteraan Hukum', 'username' => 'paniteraan-hukum', 'email' => 'hukum@ptun.go.id'],
            ['name' => 'Paniteraan Perkara', 'username' => 'paniteraan-perkara', 'email' => 'perkara@ptun.go.id'],
            ['name' => 'Hakim', 'username' => 'hakim', 'email' => 'hakim@ptun.go.id'],
        ];

        foreach ($users as $userData) {
            User::create([
                'name'     => $userData['name'],
                'username' => $userData['username'],
                'email'    => $userData['email'],
                'password' => Hash::make('password'),
                'role'     => 'user',
            ]);
        }
    }
}
