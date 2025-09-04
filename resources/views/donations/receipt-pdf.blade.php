<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Donation Receipt #{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            position: relative;
        }
        .header {
            display: block;
            text-align: center;
            border-bottom: 2px solid #facc15;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            color: #222;
        }
        .details-grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .details-grid td {
            padding: 8px 0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        .amount-section {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .amount-section .label {
            display: block;
            font-size: 16px;
            color: #333;
        }
        .amount-section .amount {
            font-size: 32px;
            font-weight: bold;
            color: #1a8a44;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .trust-info {
            font-size: 11px;
            color: #555;
            line-height: 1.6;
        }
        .stamp {
            position: absolute;
            top: 120px;
            right: 40px;
            border: 4px solid #1a8a44;
            color: #1a8a44;
            padding: 5px 10px;
            font-size: 24px;
            font-weight: bold;
            border-radius: 5px;
            opacity: 0.7;
            transform: rotate(-15deg);
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        .qr-code p {
            font-size: 10px;
            color: #888;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="stamp">PAID</div>

        <div class="header">
            {{--<img src="{{ public_path('images/logo.png') }}" alt="Logo">--}}
            <h1>{{ $donation->temple->name ?? 'Divyadarshan Trust' }}</h1>
            <p>Donation Receipt</p>
        </div>

        <table class="details-grid">
            <tr>
                <td class="label">Receipt No:</td>
                <td>#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td class="label">Date:</td>
                <td>{{ $donation->created_at->format('F jS, Y') }}</td>
            </tr>
            <tr>
                <td class="label">Donor Name:</td>
                <td>{{ $donation->user->name ?? 'Anonymous' }}</td>
                <td class="label">Payment ID:</td>
                <td>{{ $donation->payment_id ?? 'N/A' }}</td>
            </tr>
            @if($donation->purpose)
                <tr>
                    <td class="label">Purpose:</td>
                    <td colspan="3">{{ Illuminate\Support\Str::title(str_replace('_', ' ', $donation->purpose)) }}</td>
                </tr>
            @endif
        </table>

        <div class="amount-section">
            <span class="label">Amount Received</span>
            <div class="amount">₹{{ number_format($donation->amount, 2) }}</div>
        </div>

        <table class="details-grid">
            <tr>
                <td class="label">Received By:</td>
                <td class="trust-info">
                    <strong>{{ config('app.trust_name', 'Divyadarshan Trust') }}</strong><br>
                    {{ config('app.trust_address', '123 Temple Road, Varanasi, UP, India') }}<br>
                    PAN: {{ config('app.trust_pan', 'ABCDE1234F') }} | 80G Reg No: {{ config('app.trust_80g', 'DIT(E)/12A/80G/...') }}
                </td>

                <td class="qr-code">
                    {{-- Generate a QR code that links to a verification page --}}
                    {{-- <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate(route('donations.verify', $donation->uuid))) !!} "> --}}
                    <p>Scan to verify</p>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p>Thank you for your generous support. Donations are eligible for deduction under Section 80G of the Income Tax Act.</p>
            <p>This is a computer-generated receipt and does not require a signature.</p>
        </div>
    </div>
</body>
</html>
