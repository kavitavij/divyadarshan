<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Superadmin',
                'email' => 'rohitjoshi2899@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('ebir8685'),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@divyadarshan.com',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ],
            [
                'name' => 'Temple Manager',
                'email' => 'manager@temple.com',
                'role' => 'temple_manager',
                'password' => Hash::make('temple123'),
            ],
            [
                'name' => 'Hotel Manager',
                'email' => 'manager@hotel.com',
                'role' => 'hotel_manager',
                'password' => Hash::make('hotel123'),
            ],
            [
                'name' => 'User',
                'email' => 'user@divyadarshan.com',
                'role' => 'user',
                'password' => Hash::make('user123'),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
