<?php

namespace App\Mail;

use App\Models\StayBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StayBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    // Public property to hold the booking data
    public $booking;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\StayBooking $booking
     * @return void
     */
    public function __construct(StayBooking $booking)
    {
        // Accept the booking object when creating the mail
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Booking Notification for ' . $this->booking->room->hotel->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Point to the new markdown view we created
        return new Content(
            markdown: 'Mails.stay_booking_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}