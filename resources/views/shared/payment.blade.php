<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | DivyaDarshan</title>
</head>

<body>
    <h2>Complete Your Payment</h2>
    <button id="rzp-button1">Pay ₹{{ $summary['amount'] }}</button>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "{{ config('services.razorpay.key') }}",
            "amount": "{{ $summary['amount'] * 100 }}", // paise
            "currency": "INR",
            "name": "DivyaDarshan",
            "description": "{{ $summary['title'] }}",
            "order_id": "{{ $orderId }}",
            "handler": function(response) {
                fetch("{{ route('razorpay.callback') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(response)
                    })
                    .then(res => res.json())
                    .then(data => alert(data.message))
                    .catch(err => console.error(err));
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

</body>

</html>
