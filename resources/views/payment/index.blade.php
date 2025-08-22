@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>{{ $summary['title'] }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <p><strong>Item:</strong> {{ $summary['item_name'] }}</p>
                            <p class="h4 mt-3">
                                <strong>Total Amount:</strong>
                                ₹{{ number_format($summary['amount'], 2) }}
                            </p>
                        </div>

                        <button id="pay-button" class="btn btn-primary btn-lg w-100">Pay Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- This hidden form will be submitted on successful payment --}}
    <form id="confirmation-form" action="{{ route('payment.confirm') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" id="payment_id" name="payment_id" value="">
        <input type="hidden" name="order_type" value="{{ $summary['type'] }}">
        <input type="hidden" name="order_id" value="{{ $summary['id'] }}">
    </form>
@endsection

@push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('pay-button').onclick = function(e) {
            e.preventDefault();

            var options = {
                "key": "{{ env('RAZORPAY_KEY') }}",
                "amount": {{ $summary['amount'] * 100 }}, // Amount in paise
                "currency": "INR",
                "name": "Divyadarshan Trust",
                "description": "Payment for {{ $summary['item_name'] }}",
                "handler": function(response) {
                    // On success, fill the payment ID and submit the form
                    document.getElementById('payment_id').value = response.razorpay_payment_id;
                    document.getElementById('confirmation-form').submit();
                },
                "prefill": {
                    "name": "{{ Auth::user()->name ?? 'Devotee' }}",
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
