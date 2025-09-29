<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminAnnouncement extends Notification
{
    use Queueable;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // Store in the database for the user's bell icon
    }

    public function toArray($notifiable)
    {
        $finalMessage = $this->message;

        if (in_array($notifiable->role, ['temple_manager', 'hotel_manager'])) {
            // If it's a manager, add the "By Admin:" prefix
            $finalMessage = "By Admin: " . $this->message;
        }

        return [
            'sender'  => 'Admin', 
            'message' => $finalMessage,
            'url'     => route('home') 
        ];
    }
}