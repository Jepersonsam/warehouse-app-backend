<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin already exists
        if (!User::where('email', 'admin@gudangku.com')->exists()) {
            User::create([
                'name' => 'Admin GudangKu',
                'email' => 'admin@gudangku.com',
                'password' => 'password123',
                'role' => 'admin',
            ]);
            $this->command->info('Admin account created: admin@gudangku.com / password123');
        } else {
            $this->command->info('Admin account already exists.');
        }
    }
}
