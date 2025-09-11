<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// In app/Providers/AuthServiceProvider.php

use App\Models\HotelImage; // Add this line at the top
use App\Policies\HotelImagePolicy; // Add this line at the top

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // ... other policies

        // ✅ Add this line to register your new policy
        HotelImage::class => HotelImagePolicy::class,
    ];

    // ...
}
