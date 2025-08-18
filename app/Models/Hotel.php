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
        'temple_id',
        'name',
        'location',
        'description',
        'image',
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
}
