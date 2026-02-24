<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Create a bootstrap admin account when explicitly enabled via env vars.
     */
    public function run(): void
    {
        $enabled = filter_var((string) env('ADMIN_SEED_ENABLED', false), FILTER_VALIDATE_BOOL);

        if (! $enabled) {
            $this->command?->warn('AdminSeeder skipped. Set ADMIN_SEED_ENABLED=true to create a bootstrap admin account.');

            return;
        }

        $name = trim((string) env('ADMIN_SEED_NAME', ''));
        $email = strtolower(trim((string) env('ADMIN_SEED_EMAIL', '')));
        $password = (string) env('ADMIN_SEED_PASSWORD', '');

        if ($name === '' || $email === '' || $password === '') {
            $this->command?->error('AdminSeeder skipped. ADMIN_SEED_NAME, ADMIN_SEED_EMAIL, and ADMIN_SEED_PASSWORD are required.');

            return;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->command?->error('AdminSeeder skipped. ADMIN_SEED_EMAIL is not a valid email address.');

            return;
        }

        if (User::where('email', $email)->exists()) {
            $this->command?->warn('Admin account already exists for ' . $email . '. Skipping.');

            return;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->command?->info('Admin account created: ' . $email);
    }
}
