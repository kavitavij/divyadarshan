<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * THE FIX IS HERE: Add 'path' to this array.
     */
    protected $fillable = [
        'hotel_id',
        'path',
    ];

    /**
     * Get the hotel that the image belongs to.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
