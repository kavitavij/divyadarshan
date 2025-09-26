<?php

namespace Database\Seeders;

// Make sure your RolesAndPermissionsSeeder is imported
use Database\Seeders\RolesAndPermissionsSeeder; 
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the seeder to create the roles
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);
    }
}