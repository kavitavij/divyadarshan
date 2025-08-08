<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@divyadarshan.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'), // Change later
                'role' => 'admin'
            ]
        );
    }
}
