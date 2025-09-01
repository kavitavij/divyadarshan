<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DarshanSlot;
use App\Models\Devotee; // <-- This line is important

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'temple_id',
        'darshan_slot_id',
        'number_of_people',
        'status',
        // 'devotee_details',
        'booking_date',
        'order_id'
    ];

    protected $casts = [
        'devotee_details' => 'array',
        'booking_date' => 'date',
    ];

    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function slot()
    {
        return $this->belongsTo(DarshanSlot::class, 'darshan_slot_id');
    }

    /**
     * A booking has many devotees.
     */
    public function devotees()
    {
        return $this->hasMany(Devotee::class);
    }
}
