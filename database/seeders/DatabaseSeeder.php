<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // THE FIX: The call to the old RolesTableSeeder has been removed.
        // $this->call(RolesTableSeeder::class);

        $this->call([
            UsersTableSeeder::class,
            TempleSeeder::class,
            // You can add other seeders here if needed
        ]);
         $this->call([
            DefaultDarshanSlotSeeder::class,
        ]);
    }
}
