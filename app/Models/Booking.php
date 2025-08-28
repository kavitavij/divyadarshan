<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DarshanSlot;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'temple_id',
        'darshan_slot_id',
        'number_of_people',
        'status',
        'devotee_details',
    ];

    protected $casts = [
        'devotee_details' => 'array',
    ];

    /**
     * Get the temple associated with the booking.
     */
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    /**
     * Get the user who made the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function slot()
{
    return $this->belongsTo(DarshanSlot::class, 'darshan_slot_id');
}
}
