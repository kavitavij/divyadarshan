<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\StayBooking;

class BookingCancelledByManager extends Notification
{
    use Queueable;

    public $booking;

    public function __construct(StayBooking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Store in the database for the user
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => "Your booking (#{$this->booking->id}) for '{$this->booking->room->type}' has been cancelled by the hotel.",
            'url' => route('profile.my-stays.index') 
        ];
    }
}

