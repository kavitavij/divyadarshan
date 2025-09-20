<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Confirmation for Booking #{{ $booking->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; line-height: 1.6; color: #333; font-size: 14px; }
        .container { width: 100%; margin: 0 auto; padding: 15px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        h1 { margin: 0; color: #000; font-size: 24px; }
        h3 { border-bottom: 1px solid #eee; padding-bottom: 5px; margin-top: 25px; font-size: 16px; }
        p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f8f8; }
        .total-row td { font-weight: bold; font-size: 1.2em; text-align: right; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #777; }
        .qr-code-section { text-align: center; }
        .qr-code-section p { margin-bottom: 10px; font-size: 13px; color: #555; }
        .info-qr-grid { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .info-qr-grid td { vertical-align: top; padding: 0; }
        .guest-info-box { padding-right: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmation</h1>
            <p><strong>DivyaDarshan Pilgrimage Care</strong></p>
        </div>

        <h3>Booking Details</h3>
        <p><strong>Booking ID:</strong> STAY-{{ $booking->id }}</p>
        <p><strong>Booked On:</strong> {{ $booking->created_at->format('F jS, Y') }}</p>
        <p><strong>Booking Status:</strong> {{ $booking->status }}</p>
        <p><strong>Payment Status:</strong> {{ $booking->payment_method == 'pay_at_hotel' ? 'To be Paid at Hotel' : 'Paid Online' }}</p>

        <table class="info-qr-grid">
            <tr>
                <td style="width: 65%;" class="guest-info-box">
                    <h3>Guest Information</h3>
                    <p><strong>Name:</strong> {{ $booking->user->name }}</p>
                    <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                </td>
                <td style="width: 35%;">
                    <div class="qr-code-section">
                        <p>Scan for quick booking details:</p>
                        @php
                            $qrData = "Booking ID: STAY-{$booking->id}\nGuest: {$booking->user->name}\nHotel: {$booking->room->hotel->name}\nCheck-in: {{$booking->check_in_date}}";
                            $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($qrData);
                        @endphp
                        <img src="data:image/svg+xml;base64,{{ base64_encode($qrCodeSvg) }}" alt="Booking QR Code">
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">Item</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Hotel</strong></td>
                    <td>{{ $booking->room->hotel->name }}</td>
                </tr>
                <tr>
                    <td><strong>Room Type</strong></td>
                    <td>{{ $booking->room->type }}</td>
                </tr>
                <tr>
                    <td><strong>Check-in Date</strong></td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('F jS, Y') }}</td>
                </tr>
                 <tr>
                    <td><strong>Check-out Date</strong></td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('F jS, Y') }}</td>
                </tr>
                 <tr>
                    <td><strong>Number of Guests</strong></td>
                    <td>{{ $booking->number_of_guests }}</td>
                </tr>
                
                @if ($booking->payment_method == 'pay_at_hotel')
                    <tr class="total-row">
                        <td><strong>Total Amount Due at Hotel</strong></td>
                        <td><strong>&#8377;{{ number_format($booking->total_amount, 2) }}</strong></td>
                    </tr>
                @else
                    <tr class="total-row">
                        <td><strong>Total Amount Paid</strong></td>
                        <td><strong>&#8377;{{ number_format($booking->total_amount, 2) }}</strong></td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="footer">
            <p>Thank you for choosing DivyaDarshan for your spiritual journey.</p>
        </div>
    </div>
</body>
</html>