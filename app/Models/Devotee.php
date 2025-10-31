<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devotee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'full_name',
        'age',
        'gender',
        'email',
        'phone_number',
        'pincode',
        'address',
        'city',
        'state',
        'id_type',
        'id_number',
        'id_photo_path',
        'email',
        'phone',
    ];

    /**
     * A devotee belongs to a single booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
