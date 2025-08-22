<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temple extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'location', 'description', 'image', 'about',
        'online_services', 'news', 'social_services', 'slot_data',
    ];

    protected $casts = [
        'slot_data' => 'array',
        'news' => 'array',
    ];

    public function sevas()
    {
        return $this->hasMany(Seva::class);
    }

    /**
     * Get all Darshan bookings for the temple.
     */
    public function darshanBookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all Seva bookings for the temple through its sevas.
     */
    public function sevaBookings()
    {
        return $this->hasManyThrough(SevaBooking::class, Seva::class);
    }
    public function darshanSlots()
    {
        return $this->hasMany(DarshanSlot::class);
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
