<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Create a default admin account if it does not exist.
     */
    public function run(): void
    {
        if (! User::where('email', 'jumaoasralph2003@gmail.com')->exists()) {
            User::create([
                'name' => 'Ralph Jumaoas',
                'email' => 'jumaoasralph2003@gmail.com',
                'password' => Hash::make('busco@admin2026'),
                'role' => 'admin',
            ]);

            $this->command?->info('Admin account created: jumaoasralph2003@gmail.com');

            return;
        }

        $this->command?->warn('Admin account already exists. Skipping.');
    }
}
