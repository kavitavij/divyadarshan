<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\StayBooking; 
class NewHotelBooking extends Notification
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(StayBooking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // We want to store this in the database
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'guest_name' => $this->booking->user->name,
            'message' => "New booking (#{$this->booking->id}) for '{$this->booking->room->type}' by {$this->booking->user->name}.",
            'url' => route('hotel-manager.guest-list.index')
        ];
    }
    
}