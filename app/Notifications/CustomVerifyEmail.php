<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        // This tells Laravel to use your custom Blade view
        return (new MailMessage)
            ->subject('Verify Your Email for DivyaDarshan')
            ->view(
                'Mails.verify-email', // The path to your Blade file
                ['verificationUrl' => $verificationUrl] // The data to pass to it
            );
    }
}
