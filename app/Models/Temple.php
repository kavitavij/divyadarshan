<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temple extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'location',
        'description',
        'image',
        'about',
        'online_services',
        'news',
        'social_services',
        'slot_data', 
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
     protected $casts = [
        'slot_data' => 'array', // This is the crucial line
        'news' => 'array',      // Also good to have for your news column
    ];
    public function darshanSlots() {
    return $this->hasMany(DarshanSlot::class);
}
}