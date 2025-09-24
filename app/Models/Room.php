<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoomPhoto;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'type',
        'description',
        'capacity',
        'price_per_night',
        'discount_percentage',
        'total_rooms',
        'facilities',
        'room_size',
        'is_visible'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * By adding 'photos' to this array, you ensure that
     * whenever a Room model is retrieved, its photo relationship
     * is automatically loaded with it. This makes the photo data
     * available to your JavaScript front-end.
     *
     * @var array
     */
    protected $with = ['photos'];
protected $appends = ['discounted_price'];
    protected $casts = [
        'is_visible' => 'boolean',
    ];
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function photos()
    {
        return $this->hasMany(RoomPhoto::class);
    }
    protected function discountedPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $originalPrice = $attributes['price_per_night'];

                // FIX #2: Make the logic more robust by handling null values.
                $discount = $attributes['discount_percentage'] ?? 0;

                if ($discount > 0) {
                    $discountAmount = ($originalPrice * $discount) / 100;
                    return round($originalPrice - $discountAmount, 2);
                }

                return $originalPrice;
            }
        );
    }
}

