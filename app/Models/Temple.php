<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
    'manager_id',
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
    public function darshanBookings()
    {
        return $this->hasMany(Booking::class);
    }
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
    protected function city(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $parts = explode(',', $attributes['location'] ?? 'N/A,N/A', 2);
                return trim($parts[0]);
            }
        );
    }
    protected function state(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $parts = explode(',', $attributes['location'] ?? 'N/A,N/A', 2);
                return trim($parts[1] ?? 'N/A');
            }
        );
    }
}
