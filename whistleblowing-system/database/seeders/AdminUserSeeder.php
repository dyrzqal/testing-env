<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@whistleblowing.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'department' => 'IT Administration',
            'email_verified_at' => now(),
        ]);

        // Create moderator user
        User::create([
            'name' => 'John Moderator',
            'email' => 'moderator@whistleblowing.com',
            'password' => Hash::make('moderator123'),
            'role' => 'moderator',
            'is_active' => true,
            'department' => 'Human Resources',
            'email_verified_at' => now(),
        ]);

        // Create investigator user
        User::create([
            'name' => 'Jane Investigator',
            'email' => 'investigator@whistleblowing.com',
            'password' => Hash::make('investigator123'),
            'role' => 'investigator',
            'is_active' => true,
            'department' => 'Internal Audit',
            'email_verified_at' => now(),
        ]);
    }
}
