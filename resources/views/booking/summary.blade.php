<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary</title>
    {{-- Link to Bootstrap CSS to maintain basic styling --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-wrapper {
            flex-grow: 1;
        }
        .summary-card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
        .summary-card .card-header {
            background-color: #4a148c; /* Deep purple */
            color: white;
            text-align: center;
            font-size: 1.5rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 1.25rem;
        }
        .summary-card .card-body {
            padding: 2rem;
        }
        .details-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
        }
        .details-box h4 {
            color: #4a148c;
            font-weight: 600;
            border-bottom: 2px solid #ede7f6;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        .btn-proceed {
            background: linear-gradient(135deg, #28a745, #218838);
            border: none;
            font-size: 1.2rem;
            padding: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-proceed:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .footer-container {
            background-color: #f8f9fa;
            border-top: 1px solid #e7e7e7;
            padding: 2.5rem 0;
            text-align: center;
            margin-top: 3rem;
        }
        .footer-container h4 {
            font-weight: 600;
            color: #333;
        }
        .footer-container p {
            color: #6c757d;
            margin-top: 0.5rem;
        }
        .footer-buttons .btn {
            margin: 0.5rem;
            min-width: 180px;
        }
        .footer-links {
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }
        .footer-links a {
            color: #4a148c;
            text-decoration: none;
            margin: 0 0.5rem;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
        .footer-copyright {
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card summary-card">
                        <div class="card-header"><h2>Booking Summary</h2></div>
                        <div class="card-body">
                            <h3 class="text-xl font-semibold mb-3 text-center">Please confirm your details before proceeding to payment.</h3>

                            {{-- Booking Details Section --}}
                            <div class="details-box mb-4">
                                <h4 class="font-bold">Darshan Details</h4>
                                <p><strong>Temple:</strong> {{ $booking->temple->name }}</p>
                                <p><strong>Number of Devotees:</strong> {{ $booking->number_of_people }}</p>
                                <p><strong>Status:</strong> <span class="badge bg-warning text-dark">{{ $booking->status }}</span></p>
                            </div>

                            {{-- Devotee Details Section --}}
                            <div class="details-box">
                                <h4 class="font-bold">Devotee Information</h4>
                                <ul class="list-unstyled">
                                    @foreach($booking->devotee_details as $index => $devotee)
                                        <li class="mb-2">
                                            <strong>Devotee {{ $index + 1 }}:</strong> 
                                            {{ $devotee['first_name'] }} {{ $devotee['last_name'] }} 
                                            (Age: {{ $devotee['age'] }})
                                            <br><small>Phone: {{ $devotee['phone_number'] ?? 'N/A' }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            {{-- Payment Button --}}
                            <div class="text-center mt-4">
                                <a href="{{ route('payment.show', ['type' => 'darshan', 'id' => $booking->id]) }}" class="btn btn-success btn-lg">Proceed to Payment</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- THE FIX: Footer is moved outside the main content wrapper --}}
    <footer class="footer-container">
        <div class="container">
            <h4>Have questions about these Terms or need assistance?</h4>
            <div class="footer-buttons mt-3">
                <a href="{{ route('complaint.form') }}" class="btn btn-outline-danger">Submit a Complaint</a>
                <a href="{{ route('guidelines') }}" class="btn btn-outline-secondary">Read Guidelines</a>
            </div>
            <p>We’re here to guide you every step of your spiritual journey.</p>
            <div class="footer-links">
                <a href="{{ route('terms') }}">Terms</a> •
                <a href="{{ route('guidelines') }}">Guidelines</a> •
                <a href="{{ route('complaint.form') }}">Complaint</a>
            </div>
            <div class="footer-copyright">
                © {{ date('Y') }} DivyaDarshan Pilgrimage Care
            </div>
        </div>
    </footer>
</body>
</html>
