<?php

namespace App\Models;
use App\Models\Guest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StayBooking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'number_of_guests',
        'total_amount',
        'status',
        'phone_number',
    ];

    /**
     * Get the room associated with the stay booking.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user who made the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the guests for the stay booking.
     * (Assuming you have a Guest model for guest details)
     */
    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
