<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\SevaBooking;

class NewSevaBooking extends Notification
{
    use Queueable;

    public $booking;

    public function __construct(SevaBooking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        // Eager load relationships to prevent extra queries
        $this->booking->load('seva');

        return [
            'booking_id' => $this->booking->id,
            'message' => "New Seva booking (#{$this->booking->id}) for '{$this->booking->seva->name}'.",
            'url' => route('temple-manager.bookings.index')
        ];
    }
}