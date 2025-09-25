<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'location',
        'description',
        'image',
        'temple_id',
        'rating',
        // Amenity fields
        'has_wifi',
        'has_ac',
        'has_parking',
        'has_food',
        // JSON fields
        'policies',
        'nearby_attractions',
        //map
        'latitude',
        'longitude',
        'terms_and_conditions',
        'manager_id',
    ];
    protected $casts = [
        'has_wifi' => 'boolean',
        'has_ac' => 'boolean',
        'has_parking' => 'boolean',
        'has_food' => 'boolean',
        'policies' => 'array',
        'nearby_attractions' => 'array',
        
    ];

    /**
     * Get the rooms for the hotel.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get the temple associated with the hotel.
     */
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }
        public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
        public function images()
    {
        return $this->hasMany(HotelImage::class);
    }
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }
     public function getPoliciesAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
    public function getNearbyAttractionsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
    public function bookings()
    {
        return $this->hasMany(StayBooking::class, 'hotel_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
