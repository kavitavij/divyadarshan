<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@divyadarshan.com',
                'password' => Hash::make('superadmin123'),
                'role_id' => 1, // Superadmin
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@divyadarshan.com',
                'password' => Hash::make('admin123'),
                'role_id' => 2, // Admin
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Temple Manager',
                'email' => 'templemanager@divyadarshan.com',
                'password' => Hash::make('temple123'),
                'role_id' => 3, // Temple Manager
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hotel Manager',
                'email' => 'hotelmanager@divyadarshan.com',
                'password' => Hash::make('hotel123'),
                'role_id' => 4, // Hotel Manager
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User',
                'email' => 'user@divyadarshan.com',
                'password' => Hash::make('user123'),
                'role_id' => 5, // User
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
