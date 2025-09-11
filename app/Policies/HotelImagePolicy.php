<?php

namespace App\Policies;

use App\Models\HotelImage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HotelImagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\HotelImage  $image
     * @return bool
     */


    public function delete(User $user, HotelImage $image)
    {
        if ($user->role !== 'hotel_manager' || !$user->hotel) {
            return false;
        }

        return $user->hotel->id === $image->hotel_id;
    }
}
