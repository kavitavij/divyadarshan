<?php

namespace App\Models; // <-- This is the most important line

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Make sure all relevant fields are here
    protected $fillable = [
        'user_id',
        'hotel_id',
        'stay_booking_id',
        'name',
        'email',
        'rating',
        'comment',
        'message', // Added this from your old review system
        'review_type',
        'likes'
    ];

    // Define your relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function stayBooking()
    {
        return $this->belongsTo(StayBooking::class);
    }
}
