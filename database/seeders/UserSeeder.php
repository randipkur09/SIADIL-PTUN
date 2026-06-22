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

        // Sample Users/Pegawai
        $users = [
            ['name' => 'Budi Santoso',      'username' => 'budi.santoso',   'email' => 'budi@ptun.go.id'],
            ['name' => 'Siti Rahayu',       'username' => 'siti.rahayu',    'email' => 'siti@ptun.go.id'],
            ['name' => 'Ahmad Fauzi',       'username' => 'ahmad.fauzi',    'email' => 'ahmad@ptun.go.id'],
            ['name' => 'Dewi Kusuma',       'username' => 'dewi.kusuma',    'email' => 'dewi@ptun.go.id'],
            ['name' => 'Rudi Hermawan',     'username' => 'rudi.hermawan',  'email' => 'rudi@ptun.go.id'],
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
