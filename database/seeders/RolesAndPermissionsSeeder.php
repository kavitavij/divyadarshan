<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Make sure this is imported

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Use firstOrCreate to prevent errors on re-running
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'hotel_manager']);
        Role::firstOrCreate(['name' => 'temple_manager']);
        Role::firstOrCreate(['name' => 'user']);
    }
}