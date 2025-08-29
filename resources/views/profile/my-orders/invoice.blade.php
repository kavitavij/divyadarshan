<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; color: #333; }
        .container { max-width: 680px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th, .details-table td { text-align: left; padding: 8px; border-bottom: 1px solid #ddd; }
        .details-table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .total { font-weight: bold; font-size: 1.2em; }
        .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invoice</h1>
            <p>Divyadarshan Trust</p>
        </div>

        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
        <p><strong>Billed To:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>

        <br>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->order_details as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right total">Total Amount:</td>
                    <td class="text-right total">₹{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Thank you for your transaction.</p>
        </div>
    </div>
</body>
</html>
