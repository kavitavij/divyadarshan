<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hotel_id',
        'type',
        'description',
        'capacity',
        'price_per_night',
        'total_rooms',
    ];

    /**
     * Get the hotel that the room belongs to.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}