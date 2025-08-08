<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Temple;

class TempleSeeder extends Seeder
{
    public function run()
    {
        $temples = [
            [
                'name' => 'Tirupati Balaji Temple',
                'location' => 'Tirupati, Andhra Pradesh',
                'description' => 'A famous temple dedicated to Lord Venkateswara...',
                'image' => 'temple1.jpg',
                'gallery' => json_encode(['temple1.jpg', 'temple1_2.jpg', 'temple1_3.jpg']),
                'map_embed' => '<iframe src="https://www.google.com/maps/embed?...1" width="100%" height="250" style="border:0;" allowfullscreen loading="lazy"></iframe>',
            ],
            [
                'name' => 'Meenakshi Temple',
                'location' => 'Madurai, Tamil Nadu',
                'description' => 'Dedicated to Goddess Meenakshi...',
                'image' => 'temple2.jpg',
                'gallery' => json_encode(['temple2.jpg', 'temple2_2.jpg', 'temple2_3.jpg']),
                'map_embed' => '<iframe src="https://www.google.com/maps/embed?...2" width="100%" height="250" style="border:0;" allowfullscreen loading="lazy"></iframe>',
            ],
            [
                'name' => 'Jagannath Temple',
                'location' => 'Puri, Odisha',
                'description' => 'Famous for Rath Yatra...',
                'image' => 'temple3.jpg',
                'gallery' => json_encode(['temple3.jpg', 'temple3_2.jpg']),
                'map_embed' => '<iframe src="https://www.google.com/maps/embed?...3" width="100%" height="250" style="border:0;" allowfullscreen loading="lazy"></iframe>',
            ],
        ];

        foreach ($temples as $temple) {
            Temple::create($temple);
        }
    }
}
