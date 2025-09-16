<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Temple;

class SocialServiceInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The inquiry data array.
     *
     * @var array
     */
    public $inquiryData;

    /**
     * The temple instance.
     *
     * @var \App\Models\Temple
     */
    public $temple;

    /**
     * Create a new message instance.
     *
     * @param array $inquiryData
     * @param \App\Models\Temple $temple
     * @return void
     */
    public function __construct(array $inquiryData, Temple $temple)
    {
        $this->inquiryData = $inquiryData;
        $this->temple = $temple;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'New Social Service Inquiry for ' . $this->temple->name,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'Mails.SocialServiceInquiry',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
