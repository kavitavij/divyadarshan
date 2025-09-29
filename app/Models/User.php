<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable implements MustVerifyEmail
{
    // FIX: Add the HasRoles trait here
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // This custom method is no longer needed as the Spatie package provides it.
    // public function hasRole(string $role): bool { ... }

    public function favorites()
    {
        return $this->belongsToMany(Temple::class, 'favorites');
    }

    public function ebooks()
    {
        return $this->belongsToMany(\App\Models\Ebook::class, 'ebook_user', 'user_id', 'ebook_id');
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
    
    public function routeNotificationForTwilio()
    {
        return $this->phone_number; // Assuming you have a 'phone_number' column
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

}