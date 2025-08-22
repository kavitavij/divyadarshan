<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
/**
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Ebook[] $ebooks
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function favorites()
    {
        return $this->belongsToMany(Temple::class, 'favorites');
    }
    public function ebooks()
    {
        return $this->belongsToMany(Ebook::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function hotel()
    {
        return $this->hasOne(Hotel::class, 'manager_id');
    }
    public function temple()
    {
        return $this->hasOne(Temple::class, 'manager_id');
    }
}
