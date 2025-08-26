@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <h2 class="text-2xl font-bold mb-4">Complete Your Payment</h2>

        <button id="rzp-button1" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Pay ₹{{ $amount }}
        </button>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "{{ $razorpay_key }}",
            "amount": "{{ $order['amount'] }}",
            "currency": "INR",
            "name": "DivyaDarshan",
            "description": "Payment",
            "order_id": "{{ $order['id'] }}",
            "handler": function(response) {
                // on successful payment
                fetch("{{ route('cart.payment.success') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(response)
                }).then(res => window.location.href = "{{ route('cart.view') }}");
            },
            "prefill": {
                "name": "{{ Auth::user()->name ?? '' }}",
                "email": "{{ Auth::user()->email ?? '' }}",
                "contact": "{{ Auth::user()->phone ?? '' }}"
            },
            "theme": {
                "color": "#3399cc"
            }
        };
        var rzp1 = new Razorpay(options);
        document.getElementById('rzp-button1').onclick = function(e) {
            rzp1.open();
            e.preventDefault();
        }
    </script>
@endsection
