<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
class OrderConfirmation extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // We will start with email. We can add SMS later.
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Your Divyadarshan Order is Confirmed!')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Thank you for your order. Your payment was successful and your booking is confirmed.')
                    ->line('Order Number: ' . $this->order->order_number)
                    ->line('Total Amount Paid: ₹' . number_format($this->order->total_amount, 2))
                    ->action('View Your Order Details', route('profile.my-orders.index')) // Assuming you have this route
                    ->line('We look forward to seeing you. Thank you for using Divyadarshan!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'amount' => $this->order->total_amount,
        ];
    }
    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())
            ->content("Your Divyadarshan order #{$this->order->order_number} for Rs. " . number_format($this->order->total_amount, 2) . " is confirmed. Thank you!");
    }
}
