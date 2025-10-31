<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class User extends Authenticatable implements MustVerifyEmail
{
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
        'google_id',
        'google_token',
        'google_refresh_token',
        'email_verified_at',
        'is_active',
        'profile_photo_path', // <-- ADD THIS
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'google_refresh_token',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }

        // Return a default image or UI Avatar
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=1e293b&background=cbd5e1';
    }

    // ... (rest of your methods: favorites, ebooks, etc.)

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

