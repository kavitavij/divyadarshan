<?php

// app/Models/StayBookingGuest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StayBookingGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'stay_booking_id',
        'name',
        'id_type',
        'id_number',
    ];

    // Optional: Define the inverse relationship
    public function stayBooking()
    {
        return $this->belongsTo(StayBooking::class);
    }
}
