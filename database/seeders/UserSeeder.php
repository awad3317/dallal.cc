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
        
        User::create([
            'username' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'phone_number' => '1234567890',
            'image' => 'default.png', 
            'last_login' => now(),
            'email_verified' => true,
            'role_id'=>1
        ]);

        
        User::create([
            'username' => 'user',
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('user'),
            'phone_number' => '0987654321',
            'image' => 'default.png', 
            'last_login' => now(),
            'email_verified' => true,
            'role_id'=>2
        ]);
    }
}