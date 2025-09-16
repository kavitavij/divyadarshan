@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-20 text-center">
        <h2 class="text-2xl font-bold mb-4">Redirecting to Payment...</h2>
        <p class="text-gray-600">Please do not refresh or close this page.</p>

        {{-- Optional: Add a loading spinner --}}
        <div class="flex justify-center items-center mt-6">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        </div>
    </div>

    {{-- This form is now hidden; it's just here for the success handler --}}
    <form action="{{ route('cart.payment.success') }}" method="POST" id="razorpay-form" class="hidden">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "{{ $razorpay_key }}",
            "amount": "{{ $order['amount'] }}",
            "currency": "INR",
            "name": "DivyaDarshan",
            "description": "Payment for Order",
            "order_id": "{{ $order['id'] }}",
            "handler": function(response) {
                // On successful payment, populate the hidden form and submit it
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('razorpay-form').submit();
            },
            "prefill": {
                "name": "{{ Auth::user()->name ?? '' }}",
                "email": "{{ Auth::user()->email ?? '' }}",
                "contact": "{{ Auth::user()->phone ?? '' }}"
            },
            "theme": {
                "color": "#3399cc"
            },
            // This function is called when the user closes the gateway without paying
            "modal": {
                "ondismiss": function() {
                    window.location.href = "{{ route('cart.view') }}";
                }
            }
        };

        var rzp1 = new Razorpay(options);

        // Automatically open the Razorpay gateway when the page loads
        window.onload = function() {
            rzp1.open();
        };
    </script>
@endsection
