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
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card payment-card">
                    <div class="card-header"><h2>Complete Your Payment</h2></div>
                    <div class="card-body">
                        <div class="alert alert-info text-center">
                            This is a mock payment gateway for demonstration purposes.
                        </div>

                        <div class="mb-4 summary-box">
                            <h4>Booking Summary</h4>
                            <p><strong>Temple:</strong> {{ $booking->temple->name }}</p>
                            <p><strong>Number of Devotees:</strong> {{ $booking->number_of_people }}</p>
                            <p class="total-amount"><strong>Total Amount:</strong> ₹{{ $amount }}</p>
                        </div>

                        {{-- This form simulates the final step after payment --}}
                        <form action="{{ route('booking.confirm') }}" method="POST">
                            @csrf
                            {{-- We pass the booking ID to the final confirmation step --}}
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                            <div class="form-group">
                                <label for="card_number">Card Number</label>
                                <input type="text" id="card_number" class="form-control" value="4242 4242 4242 4242" readonly>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 form-group">
                                    <label for="expiry">Expiry</label>
                                    <input type="text" id="expiry" class="form-control" value="12/28" readonly>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" class="form-control" value="123" readonly>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 mt-4 btn-pay-now">Pay Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
