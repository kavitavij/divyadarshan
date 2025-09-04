<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Devotee;
class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'temple_id',
        'darshan_slot_id',
        'default_darshan_slot_id',
        'booking_date',
        'time_slot',
        'line_number',
        'number_of_people',
        'amount',
        'devotee_details',
        'status',
        'refund_status',
        'check_in_token',
        'checked_in_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'devotee_details' => 'array',
        'booking_date' => 'date',
    ];

    // --- RELATIONSHIPS ---

    /**
     * Get the user that made the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the temple associated with the booking (if any).
     */
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    /**
     * Get the hotel associated with the booking (if any).
     * This is the required relationship to fix the error.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the specific darshan slot for the booking.
     */
    public function slot()
    {
        return $this->belongsTo(DarshanSlot::class, 'darshan_slot_id');
    }

    /**
     * Get the devotees associated with the booking.
     * Note: This assumes you have a Devotee model and table.
     */
    public function devotees()
    {
        return $this->hasMany(Devotee::class);
    }
        public function refundRequest()
    {
        return $this->hasOne(\App\Models\RefundRequest::class);
    }
    public function darshanSlot()
    {
        return $this->belongsTo(DarshanSlot::class);
    }
    // In app/Models/Booking.php
    public function refundRequests()
    {
        return $this->morphMany(RefundRequest::class, 'bookingable', 'booking_type', 'booking_id');
    }
    // In app/Models/Booking.php
public function defaultDarshanSlot()
{
    return $this->belongsTo(DefaultDarshanSlot::class);
}
}

