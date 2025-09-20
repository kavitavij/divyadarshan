<x-mail::message>
# New Booking Confirmation

Hello {{ $booking->room->hotel->user->name ?? 'Hotel Manager' }},

You have received a new booking for your hotel: **{{ $booking->room->hotel->name }}**.

Please find the details of the booking below:

<x-mail::panel>
**Booking ID:** {{ $booking->id }} <br>
**Booked By (User):** {{ $booking->user->name }} <br>
**User Contact:** {{ $booking->phone_number }} | {{ $booking->email }}
</x-mail::panel>

### Booking Details
<x-mail::table>
| | |
|:--------------------|:-----------------------------------------------------------|
| **Payment Status** | **{{ $booking->payment_status == 'unpaid' ? 'To be Paid at Hotel' : 'Paid Online' }}** |
| **Room Type** | {{ $booking->room->type }}                                 |
| **Check-in Date** | {{ \Carbon\Carbon::parse($booking->check_in_date)->format('D, M j, Y') }} |
| **Check-out Date** | {{ \Carbon\Carbon::parse($booking->check_out_date)->format('D, M j, Y') }} |
| **Number of Guests**| {{ $booking->number_of_guests }}                           |
| **Total Amount** | ₹{{ number_format($booking->total_amount, 2) }}          |
</x-mail::table>

Please prepare for the guest's arrival. You can view and manage all your bookings from your hotel dashboard.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>