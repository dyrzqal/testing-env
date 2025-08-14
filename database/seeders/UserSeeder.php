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
        // Create Admin User
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@wbs.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'department' => 'IT Department',
            'email_verified_at' => now(),
        ]);

        // Create Moderator User
        User::create([
            'name' => 'Content Moderator',
            'email' => 'moderator@wbs.com',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'is_active' => true,
            'department' => 'Compliance Department',
            'email_verified_at' => now(),
        ]);

        // Create Investigator User
        User::create([
            'name' => 'Senior Investigator',
            'email' => 'investigator@wbs.com',
            'password' => Hash::make('password'),
            'role' => 'investigator',
            'is_active' => true,
            'department' => 'Legal Department',
            'email_verified_at' => now(),
        ]);

        // Create Regular User
        User::create([
            'name' => 'Regular User',
            'email' => 'user@wbs.com',
            'password' => Hash::make('password'),
            'role' => 'investigator',
            'is_active' => true,
            'department' => 'General Department',
            'email_verified_at' => now(),
        ]);

        // Create additional test users
        User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@wbs.com',
            'password' => Hash::make('password'),
            'role' => 'investigator',
            'is_active' => true,
            'department' => 'HR Department',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@wbs.com',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'is_active' => true,
            'department' => 'Finance Department',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike.johnson@wbs.com',
            'password' => Hash::make('password'),
            'role' => 'investigator',
            'is_active' => true,
            'department' => 'Operations Department',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Sarah Wilson',
            'email' => 'sarah.wilson@wbs.com',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'is_active' => true,
            'department' => 'Marketing Department',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'David Brown',
            'email' => 'david.brown@wbs.com',
            'password' => Hash::make('password'),
            'role' => 'investigator',
            'is_active' => true,
            'department' => 'Security Department',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Lisa Davis',
            'email' => 'lisa.davis@wbs.com',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'is_active' => true,
            'department' => 'Customer Service',
            'email_verified_at' => now(),
        ]);
    }
}
