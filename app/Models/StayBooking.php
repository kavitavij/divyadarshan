<?php

namespace App\Models;

use App\Models\Room;
use App\Models\User;
use App\Models\StayBookingGuest;
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
        'order_id',
        'room_id',
        'hotel_id',
        'check_in_date',
        'check_out_date',
        'number_of_guests',
        'email',
        'phone_number',
        'total_amount',
        'status',
        'refund_status',
        'payment_method',
        'payment_status', 
    ];
     protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'checked_in_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guests()
    {
        return $this->hasMany(StayBookingGuest::class);
    }

    public function refundRequests()
    {
        return $this->morphMany(RefundRequest::class, 'bookingable', 'booking_type', 'booking_id');
    }
    public function review()
    {

        return $this->hasOne(Review::class);
    }
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
