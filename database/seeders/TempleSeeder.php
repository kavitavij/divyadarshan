<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Temple;

class TempleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Temple::create([
            'name' => 'Vaishno Devi Temple',
            'location' => 'Katra, Jammu and Kashmir',
            'description' => 'A major Hindu pilgrimage site dedicated to the goddess Vaishno Devi.',
            'image' => 'default.jpg' // Assuming a default image in public/images/temples/
        ]);

        Temple::create([
            'name' => 'Tirupati Balaji Temple',
            'location' => 'Tirumala, Andhra Pradesh',
            'description' => 'One of the richest temples in the world, dedicated to Lord Venkateswara.',
            'image' => 'default.jpg'
        ]);

        Temple::create([
            'name' => 'Golden Temple',
            'location' => 'Amritsar, Punjab',
            'description' => 'The holiest Gurdwara and the most important pilgrimage site of Sikhism.',
            'image' => 'default.jpg'
        ]);
    }
}