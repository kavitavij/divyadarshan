<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Donation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    {{-- You can link to your site's CSS file here --}}
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Donation Summary</h1>
            <p class="text-gray-600">Please confirm your generous donation.</p>
        </div>

        <div class="space-y-4 mb-8">
            @if ($donation->temple)
                <div class="flex justify-between">
                    <span class="text-gray-500">For Temple:</span>
                    <span class="font-medium">{{ $donation->temple->name }}</span>
                </div>
            @endif
            <div class="flex justify-between text-2xl font-bold">
                <span>Amount:</span>
                <span>₹{{ $donation->amount }}</span>
            </div>
        </div>

        <button id="pay-button" class="w-full bg-orange-500 text-white font-bold py-3 rounded-lg">
            Pay Now
        </button>
    </div>

    <script>
        document.getElementById('pay-button').onclick = function(e) {
            e.preventDefault();

            var options = {
                "key": "{{ env('RAZORPAY_KEY') }}",
                "amount": {{ $donation->amount * 100 }}, // Amount in paise
                "currency": "INR",
                "name": "Divyadarshan Temple Trust",
                "description": "Temple Donation",
                "handler": function(response) {
                    // On success, submit a form to your confirm route
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('donations.confirm') }}";

                    let hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = '_token';
                    hiddenField.value = "{{ csrf_token() }}";
                    form.appendChild(hiddenField);

                    let donationIdField = document.createElement('input');
                    donationIdField.type = 'hidden';
                    donationIdField.name = 'donation_id';
                    donationIdField.value = {{ $donation->id }};
                    form.appendChild(donationIdField);

                    document.body.appendChild(form);
                    form.submit();
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
</body>

</html>
