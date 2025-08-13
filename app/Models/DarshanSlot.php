<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DarshanSlot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'temple_id',
        'slot_date',
        'start_time',
        'end_time',
        'total_capacity',
        'booked_capacity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'slot_date' => 'date',
    ];

    /**
     * Get the temple that owns the slot.
     */
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }
}