<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin {--email=} {--name=} {--password=}';
    protected $description = 'Create or update an administrative user account';

    public function handle(): int
    {
        $this->info('--- Uhalisia Portal: Create Admin User ---');

        $email = $this->option('email') ?: $this->ask('Admin Email Address');
        $name = $this->option('name') ?: $this->ask('Full Name', 'Administrator');
        $password = $this->option('password') ?: $this->secret('Password');

        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ], [
            'email' => ['required', 'email'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return Command::FAILURE;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'role' => 'admin',
                'active' => true,
            ]
        );

        $this->info("Admin account [{$user->email}] successfully configured.");
        return Command::SUCCESS;
    }
}
