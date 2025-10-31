<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Darshan Booking Receipt #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
        }

        .container {
            max-width: 680px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table td {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .details-table td:first-child {
            font-weight: bold;
            width: 160px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }

        .info-grid {
            width: 100%;
            margin-top: 20px;
        }

        .info-grid td {
            vertical-align: top;
            padding: 10px;
        }

        .devotees-table {
            width: 100%;
            border-collapse: collapse;
        }

        .devotees-table th,
        .devotees-table td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .devotees-table th {
            background-color: #f2f2f2;
        }

        .qr-code-box {
            text-align: center;
            padding-left: 20px;
        }

        .qr-code-box p {
            margin-top: 5px;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $booking->temple->name ?? 'Divyadarshan Trust' }}</h1>
            <p>Darshan Booking Receipt</p>
        </div>

        <table class="details-table">
            <tr>
                <td>Booking ID:</td>
                <td>#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Darshan Date & Time:</td>
                <td>
                    {{ $booking->booking_date->format('F jS, Y') }} at {{ $booking->time_slot ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td>Status:</td>
                <td>{{ $booking->status }}</td>
            </tr>
            <tr>
                <td>Number of Devotees:</td>
                <td>{{ $booking->number_of_people }}</td>
            </tr>
        </table>

        <table class="info-grid">
            <tr>
                <td style="width: 60%;">
                    <h3>Devotee Details</h3>
                    <table class="devotees-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->devotees as $devotee)
                                <tr>
                                    <td>{{ $devotee->full_name }}</td>
                                    <td>{{ $devotee->age }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td style="width: 40%;" class="qr-code-box">
                    @if ($booking->check_in_token)
                        <img src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate(route('check-in.show', $booking->check_in_token))) }}"
                            alt="QR Code">

                        <p>Scan for entry</p>
                    @endif
                </td>
            </tr>
        </table>

        <div class="footer">
            <p>Please carry a valid ID proof for verification at the temple.</p>
            <p>This is a computer-generated receipt.</p>
        </div>
    </div>
</body>

</html>
