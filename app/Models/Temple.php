<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temple extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'location',
    'description',
    'image',
    'gallery',
    'map',
    'history',
    'timings',
    'how_to_reach',
    'ebook_path',
    'about',
    'online_services',
    'slot_booking', 
    'news',
    'social_services',
];
protected $casts = [
        'news' => 'array',
        'slot_data' => 'array', 
    ];
}