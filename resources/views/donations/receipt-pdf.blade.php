<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Donation Receipt #{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            /* UPDATED: Changed font to one that supports the Rupee symbol */
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
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #222;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .details-table td:first-child {
            font-weight: bold;
            width: 150px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #777;
        }
        .amount-box {
            text-align: center;
            border: 2px solid #1a8a44;
            background-color: #f0fff4;
            padding: 15px;
            margin: 30px 0;
        }
        .amount-box .amount {
            font-size: 28px;
            font-weight: bold;
            color: #1a8a44;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $donation->temple->name ?? 'Divyadarshan Trust' }}</h1>
            <p>Donation Receipt</p>
        </div>

        <table class="details-table">
            <tr>
                <td>Receipt No:</td>
                <td>#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Date:</td>
                <td>{{ $donation->created_at->format('F jS, Y') }}</td>
            </tr>
            <tr>
                <td>Donor Name:</td>
                <td>{{ $donation->user->name ?? 'N/A' }}</td>
            </tr>
             @if($donation->purpose)
                <tr>
                    <td>Purpose:</td>
                    <td>{{ Illuminate\Support\Str::title(str_replace('_', ' ', $donation->purpose)) }}</td>
                </tr>
            @endif
        </table>

        <div class="amount-box">
            Amount Received
            <div class="amount">₹{{ number_format($donation->amount, 2) }}</div>
        </div>

        <div class="footer">
            <p>Thank you for your generous support.</p>
            <p>This is a computer-generated receipt and does not require a signature.</p>
        </div>
    </div>
</body>
</html>

