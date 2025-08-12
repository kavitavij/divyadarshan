<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Temple;

class TempleSeeder extends Seeder
{
    public function run()
    {
        Temple::create([
            'name' => 'Example Temple',
            'location' => 'Example Location',
            'description' => 'This is an example temple.',
        ]);
    }
}