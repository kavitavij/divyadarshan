<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmation</title>
<style>
body {
font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
line-height: 1.6;
color: #333333;
background-color: #f4f4f4;
margin: 0;
padding: 0;
}
.container {
width: 90%;
max-width: 600px;
margin: 20px auto;
padding: 20px;
border: 1px solid #dddddd;
border-radius: 8px;
background-color: #ffffff;
}
.header {
text-align: center;
margin-bottom: 20px;
padding-bottom: 10px;
border-bottom: 2px solid #eeeeee;
}
.header h2 {
margin: 0;
color: #2c3e50;
}
.order-details, .item-table {
width: 100%;
border-collapse: collapse;
margin-bottom: 20px;
}
.order-details td {
padding: 8px 0;
}
.item-table th, .item-table td {
border: 1px solid #dddddd;
padding: 12px;
text-align: left;
}
.item-table th {
background-color: #f8f8f8;
font-weight: bold;
}
.item-table .price {
text-align: right;
}
.total-row td {
font-weight: bold;
font-size: 1.1em;
}
.footer {
text-align: center;
margin-top: 20px;
font-size: 0.9em;
color: #777777;
}
p {
margin: 0 0 15px 0;
}
</style>
</head>
<body>
<div class="container">
<div class="header">
<h2>Thank You for Your Order!</h2>
</div>
<p>Dear {{ $order->user->name }},</p>
<p>We have successfully received your order and it is now being processed. We appreciate your devotion and support. Here are the details of your transaction:</p>

    <h3>Order Summary</h3>
    <table class="order-details">
        <tr>
            <td><strong>Order Number:</strong></td>
            <td>{{ $order->order_number }}</td>
        </tr>
        <tr>
            <td><strong>Order Date:</strong></td>
            <td>{{ $order->created_at->format('d M, Y') }}</td>
        </tr>
         <tr>
            <td><strong>Payment ID:</strong></td>
            <td>{{ $order->payment_id }}</td>
        </tr>
    </table>

    <h3>Order Items</h3>
    <table class="item-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Details</th>
                <th class="price">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->order_details as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>
                    @if($item['type'] === 'darshan')
                        Date: <strong>{{ \Carbon\Carbon::parse($item['details']['selected_date'])->format('d M, Y') }}</strong> <br>
                        Number of Devotees: <strong>{{ $item['details']['number_of_people'] }}</strong>
                    @elseif($item['type'] === 'stay')
                         Check-in: <strong>{{ \Carbon\Carbon::parse($item['details']['check_in_date'])->format('d M, Y') }}</strong> <br>
                         Check-out: <strong>{{ \Carbon\Carbon::parse($item['details']['check_out_date'])->format('d M, Y') }}</strong> <br>
                         Number of Guests: <strong>{{ $item['details']['number_of_guests'] }}</strong>
                    @elseif($item['type'] === 'seva' || $item['type'] === 'ebook')
                        Quantity: <strong>{{ $item['quantity'] }}</strong>
                    @elseif($item['type'] === 'donation')
                        Purpose: <strong>{{ $item['details']['donation_purpose'] ?? 'General Donation' }}</strong>
                    @endif
                </td>
                <td class="price">₹{{ number_format($item['price'], 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" style="text-align: right;"><strong>Total Amount:</strong></td>
                <td class="price"><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <p>You can view your complete order history by visiting the "My Orders" section in your profile on our website.</p>
    <p>Thank you once again for your contribution.</p>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Divyadarshan. All rights reserved.</p>
    </div>
</div>

</body>
</html>
