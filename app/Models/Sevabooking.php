<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SevaBooking extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 
        'order_id', // Ensures this can be saved
        'seva_id', 
        'amount', 
        'quantity', // Ensures this can be saved
        'status'
    ];

    /**
     * Get the user who made the booking.
     */
    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

    /**
     * Get the seva that was booked.
     */
    public function seva()
    {
        return $this->belongsTo(Seva::class);
    }

    /**
     * Get the order that this seva booking belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

