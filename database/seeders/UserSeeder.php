<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@broadcaster.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Client user
        User::create([
            'name' => 'Client User',
            'email' => 'client@broadcaster.com',
            'password' => Hash::make('client123'),
            'role' => 'client',
        ]);

        echo "✓ Created Admin user: admin@broadcaster.com / admin123\n";
        echo "✓ Created Client user: client@broadcaster.com / client123\n";
    }
}
