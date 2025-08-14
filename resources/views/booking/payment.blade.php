<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment</title>
    {{-- Link to Bootstrap CSS to maintain basic styling --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
        }
        .payment-card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
        .payment-card .card-header {
            background-color: #4a148c; /* Deep purple */
            color: white;
            text-align: center;
            font-size: 1.5rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 1.25rem;
        }
        .payment-card .card-body {
            padding: 2rem;
        }
        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
        }
        .summary-box h4 {
            color: #4a148c;
            font-weight: 600;
        }
        .summary-box .total-amount {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2c3e50;
        }
        .form-control:read-only {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        .btn-pay-now {
            background: linear-gradient(135deg, #28a745, #218838);
            border: none;
            font-size: 1.2rem;
            padding: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-pay-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        /* --- NEW STYLES FOR PAYMENT TABS --- */
        .payment-methods-nav {
            display: flex;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1.5rem;
        }
        .payment-methods-nav .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 0.75rem 1rem;
            cursor: pointer;
        }
        .payment-methods-nav .nav-link.active {
            color: #4a148c;
            border-bottom-color: #4a148c;
            font-weight: 600;
        }
        .payment-method-content {
            display: none;
        }
        .payment-method-content.active {
            display: block;
        }
        .wallet-icons {
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .wallet-icons img {
            height: 40px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        .wallet-icons img:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card payment-card">
                    <div class="card-header"><h2>Complete Your Payment</h2></div>
                    <div class="card-body">
                        <div class="mb-4 summary-box">
                            <h4>Booking Summary</h4>
                            <p><strong>Temple:</strong> {{ $booking->temple->name }}</p>
                            <p><strong>Number of Devotees:</strong> {{ $booking->number_of_people }}</p>
                            <p class="total-amount"><strong>Total Amount:</strong> ₹{{ $amount }}</p>
                        </div>

                        {{-- Payment Method Tabs --}}
                        <div class="payment-methods-nav">
                            <div class="nav-link active" data-target="#card-payment">Credit/Debit Card</div>
                            <div class="nav-link" data-target="#upi-payment">UPI</div>
                            <div class="nav-link" data-target="#netbanking-payment">Net Banking</div>
                            <div class="nav-link" data-target="#wallets-payment">Wallets</div>
                        </div>

                        {{-- Payment Method Content Panes --}}
                        <div id="card-payment" class="payment-method-content active">
                            <div class="form-group">
                                <label for="card_number">Card Number</label>
                                <input type="text" id="card_number" class="form-control" placeholder="4242 4242 4242 4242">
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 form-group">
                                    <label for="expiry">Expiry</label>
                                    <input type="text" id="expiry" class="form-control" placeholder="MM/YY">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" class="form-control" placeholder="123">
                                </div>
                            </div>
                        </div>

                        <div id="upi-payment" class="payment-method-content text-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=upi://pay?pa=divyadarshan@okhdfcbank" alt="UPI QR Code" class="mx-auto">
                            <p class="my-3">Scan to pay or enter your UPI ID below.</p>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="yourname@upi">
                            </div>
                        </div>

                        <div id="netbanking-payment" class="payment-method-content">
                            <div class="form-group">
                                <label for="bank-select">Select Your Bank</label>
                                <select id="bank-select" class="form-select">
                                    <option>State Bank of India</option>
                                    <option>HDFC Bank</option>
                                    <option>ICICI Bank</option>
                                    <option>Axis Bank</option>
                                    <option>Punjab National Bank</option>
                                </select>
                            </div>
                        </div>

                        <div id="wallets-payment" class="payment-method-content text-center">
                            <p>Select your preferred wallet to pay.</p>
                            <div class="wallet-icons">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d2/Amazon_Pay_logo.svg/2560px-Amazon_Pay_logo.svg.png" alt="Amazon Pay">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/PhonePe_Logo.svg/1200px-PhonePe_Logo.svg.png" alt="PhonePe">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/JioMoney_logo.svg/1200px-JioMoney_logo.svg.png" alt="JioMoney">
                            </div>
                        </div>

                        {{-- This form handles the final submission --}}
                        <form action="{{ route('booking.confirm') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <button type="submit" class="btn btn-success btn-lg w-100 btn-pay-now">Pay Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.payment-methods-nav .nav-link');
    const panes = document.querySelectorAll('.payment-method-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Deactivate all tabs and panes
            tabs.forEach(t => t.classList.remove('active'));
            panes.forEach(p => p.classList.remove('active'));

            // Activate the clicked tab and its corresponding pane
            this.classList.add('active');
            const targetPane = document.querySelector(this.dataset.target);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
});
</script>

</body>
</html>
