<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'temple_id',
        'amount',
        'status',
        'purpose', // <-- ADD THIS LINE
    ];

    /**
     * Get the user who made the donation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the temple the donation is for (if any).
     */
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }
}
