<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Darshan Booking Receipt #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; color: #333; }
        .container { max-width: 680px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; }
        .details-table, .devotees-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table td { padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .details-table td:first-child { font-weight: bold; width: 150px; }
        .devotees-table th, .devotees-table td { text-align: left; padding: 8px; border-bottom: 1px solid #ddd; }
        .devotees-table th { background-color: #f2f2f2; }
        .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #777; }
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
                <td>Date of Booking:</td>
                <td>{{ $booking->created_at->format('F jS, Y') }}</td>
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

        <br>
        <h3>Devotee Details</h3>
        <table class="devotees-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>ID Proof</th>
                </tr>
            </thead>
            <tbody>
                {{-- THIS IS THE CORRECTED LOOP --}}
                @foreach($booking->devotee_details as $devotee)
                    <tr>
                        <td>{{ $devotee['first_name'] ?? ($devotee['full_name'] ?? '') }} {{ $devotee['last_name'] ?? '' }}</td>
                        <td>{{ $devotee['age'] ?? '' }}</td>
                        <td>{{ Str::upper($devotee['id_type'] ?? '') }}: {{ $devotee['id_number'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Please carry a valid ID proof for verification at the temple.</p>
            <p>This is a computer-generated receipt.</p>
        </div>
    </div>
</body>
</html>
