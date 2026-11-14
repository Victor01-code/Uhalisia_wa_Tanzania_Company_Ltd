<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Portal Administrator',
                'email' => 'admin@uhalisiawatanzania.com',
                'password' => Hash::make('Admin@UWT2026!'),
                'role' => 'admin',
                'active' => true,
            ],
            [
                'name' => 'Safari Tour Agent',
                'email' => 'agent@uhalisiawatanzania.com',
                'password' => Hash::make('Agent@UWT2026!'),
                'role' => 'agent',
                'active' => true,
            ],
            [
                'name' => 'Chief Accountant',
                'email' => 'accountant@uhalisiawatanzania.com',
                'password' => Hash::make('Accounts@UWT2026!'),
                'role' => 'accountant',
                'active' => true,
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }
    }
}
