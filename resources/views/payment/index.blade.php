@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white text-center">
                        <h2>Confirm Your Booking & Complete Payment</h2>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">{{ $summary['title'] }}</h4>

                        {{-- Dynamically display summary details --}}
                        <ul class="list-group list-group-flush mb-4">
                            @foreach ($summary['details'] as $label => $value)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>{{ $label }}:</strong>
                                    <span>{{ $value }}</span>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center h4">
                                <strong>Total Amount:</strong>
                                <span class="text-success">₹{{ number_format($summary['amount'], 2) }}</span>
                            </li>
                        </ul>

                        <div class="text-center">
                            <button id="pay-button" class="btn btn-primary btn-lg w-100">Pay Now with Razorpay</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden form to confirm payment --}}
    <form id="confirmation-form" action="{{ route('payment.confirm') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" id="payment_id" name="payment_id">
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
                "description": "Payment for {{ $summary['title'] }}",
                "handler": function(response) {
                    document.getElementById('payment_id').value = response.razorpay_payment_id;
                    document.getElementById('confirmation-form').submit();
                },
                "prefill": {
                    "name": "{{ Auth::user()->name ?? 'Devotee' }}",
                    "email": "{{ Auth::user()->email ?? '' }}",
                },
                "theme": {
                    "color": "#4a148c"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        }
    </script>
@endpush
