<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temple extends Model
{
    protected $fillable = [
        'name',
        'image',
        'description',
        'about',
        'online_services',
        'Slot Booking',
        'news',
        'social_services',
        // other fields
    ];
}
