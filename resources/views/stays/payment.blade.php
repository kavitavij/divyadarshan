@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Confirm Accommodation Payment</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <p><strong>Hotel:</strong> {{ $stayBooking->room->hotel->name }}</p>
                            <p><strong>Room Type:</strong> {{ $stayBooking->room->type }}</p>
                            <p class="h4 mt-3">
                                <strong>Total Amount:</strong>
                                ₹{{ number_format($stayBooking->total_amount, 2) }}
                            </p>
                        </div>

                        <button id="pay-button" class="btn btn-primary btn-lg w-100">Pay Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- This hidden form will be submitted on successful payment --}}
    <form id="confirmation-form" action="{{ route('stays.confirm') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $stayBooking->id }}">
    </form>
@endsection

@push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('pay-button').onclick = function(e) {
            e.preventDefault();

            var options = {
                "key": "{{ env('RAZORPAY_KEY') }}",
                "amount": {{ $stayBooking->total_amount * 100 }}, // Amount in paise
                "currency": "INR",
                "name": "Divyadarshan - Accommodation",
                "description": "Payment for {{ $stayBooking->room->hotel->name }}",
                "handler": function(response) {
                    // On success, submit the hidden confirmation form
                    document.getElementById('confirmation-form').submit();
                },
                "prefill": {
                    "name": "{{ Auth::user()->name ?? 'Guest' }}",
                    "email": "{{ Auth::user()->email ?? '' }}",
                },
                "theme": {
                    "color": "#F97316"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        }
    </script>
@endpush
