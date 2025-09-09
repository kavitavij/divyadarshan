<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temple extends Model
{
    use HasFactory;

    protected $fillable = [
    'name',
    'location',
    'description',
    'image',
    'about',
    'online_services',
    'news',
    'social_services',
    'offered_social_services',
    'slot_data',
    'darshan_charge',
    'offered_services',
    'terms_and_conditions',
];
    protected $casts = [
    'offered_services' => 'array',
    'offered_social_services' => 'array',
    'slot_data' => 'array',
    'news' => 'array',
    'terms_and_conditions' => 'array',
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
    public function galleryImages()
    {
        return $this->hasMany(TempleImage::class);
    }
}
