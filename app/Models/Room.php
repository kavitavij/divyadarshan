<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoomPhoto;
class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'type',
        'description',
        'capacity',
        'price_per_night',
        'total_rooms',
        'facilities',
        'room_size',
        'is_visible'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * By adding 'photos' to this array, you ensure that
     * whenever a Room model is retrieved, its photo relationship
     * is automatically loaded with it. This makes the photo data
     * available to your JavaScript front-end.
     *
     * @var array
     */
    protected $with = ['photos'];
    
    protected $casts = [
        'is_visible' => 'boolean', 
    ];
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function photos()
    {
        return $this->hasMany(RoomPhoto::class);
    }
}

