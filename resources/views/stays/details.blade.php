<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Guest Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .details-card {
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            margin-top: 2rem;
            overflow: hidden;
        }

        .details-card .card-header {
            background-color: #0056b3;
            color: white;
            text-align: center;
            font-size: 1.6rem;
            font-weight: 600;
            padding: 1.5rem;
        }

        .guest-details-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .guest-details-card h4 {
            color: #0056b3;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        @media (min-width: 768px) {
            .details-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .grid-full-width {
                grid-column: 1 / -1;
            }
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.25rem rgba(0, 86, 179, 0.25);
        }

        .btn-proceed {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 0.85rem;
            width: 100%;
            border-radius: 8px;
            transition: transform 0.2s ease-in-out;
        }

        .btn-proceed:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 86, 179, 0.3);
        }

        .room-summary {
            background-color: #e7f1ff;
            border-left: 5px solid #0056b3;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .room-summary h3 {
            font-size: 1.25rem;
            color: #004a99;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card details-card">
                    <div class="card-header">
                        <h2>Enter Your Details</h2>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="room-summary mb-4">
                            <h3>{{ $room->type }} Room at {{ $room->hotel->name }}</h3>
                            <p>Price: <strong>₹{{ number_format($room->price_per_night, 2) }}</strong> / night</p>
                        </div>

                        <form action="{{ route('cart.addStay') }}" method="POST">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="check_in_date">Check-in Date</label>
                                    <input type="date" name="check_in_date" id="check_in_date" class="form-control"
                                        value="{{ old('check_in_date') }}" required min="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="check_out_date">Check-out Date</label>
                                    <input type="date" name="check_out_date" id="check_out_date" class="form-control"
                                        value="{{ old('check_out_date') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="number_of_guests">Number of Guests (Max: {{ $room->capacity }})</label>
                                <input type="number" name="number_of_guests" id="number_of_guests" class="form-control"
                                    value="{{ old('number_of_guests', 1) }}" min="1"
                                    max="{{ $room->capacity }}" required>
                            </div>

                            {{-- Guest Forms --}}
                            <div id="guest-forms-container">
                                @for ($i = 0; $i < $room->capacity; $i++)
                                    <div class="guest-details-card" id="guest-form-{{ $i }}"
                                        style="display:none;">
                                        <h4>Guest {{ $i + 1 }} Details</h4>
                                        <div class="details-grid">
                                            <div class="form-group">
                                                <label for="guest_name_{{ $i }}">Full Name</label>
                                                <input type="text" name="guests[{{ $i }}][name]"
                                                    id="guest_name_{{ $i }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="guest_id_type_{{ $i }}">ID Type</label>
                                                <select name="guests[{{ $i }}][id_type]"
                                                    id="guest_id_type_{{ $i }}" class="form-control">
                                                    <option value="aadhar">Aadhar Card</option>
                                                    <option value="pan">PAN Card</option>
                                                    <option value="passport">Passport</option>
                                                </select>
                                            </div>
                                            <div class="form-group grid-full-width">
                                                <label for="guest_id_number_{{ $i }}">ID Number</label>
                                                <input type="text" name="guests[{{ $i }}][id_number]"
                                                    id="guest_id_number_{{ $i }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                        <div class="price-summary my-4 p-3 bg-light rounded border">
                            <h5>Price Details</h5>
                            <p class="mb-1">Number of Nights: <strong id="num-nights">--</strong></p>
                            <p class="mb-0 fs-5">Total Payable Amount: <strong id="total-amount" class="text-success">₹--</strong></p>
                            <input type="hidden" name="total_amount" id="total_amount_input" value="0">
                        </div>

                        {{-- Contact Details --}}
                        <div class="row mt-4 mb-3">
                            <!-- <div class="col-md-6 mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email') }}" required>
                            </div> -->
                            <div class="col-md-6 mb-3">
                                <label for="phone_number">Phone Number</label>
                                <input type="tel" name="phone_number" id="phone_number" class="form-control"
                                    value="{{ old('phone_number') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-proceed">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                alert("{{ session('success') }}");
                window.location.href = "{{ route('cart.view') }}"; // or route('home')
            });
        </script>
    @endif
   <script>
    document.addEventListener("DOMContentLoaded", function() {
        // DEFINE ALL CONSTANTS AT THE TOP
        const numberOfGuestsInput = document.getElementById("number_of_guests");
        const maxGuests = {{ $room->capacity }};
        const checkInInput = document.getElementById("check_in_date"); // Moved to top
        const checkOutInput = document.getElementById("check_out_date"); // Moved to top
        const pricePerNight = {{ $room->price_per_night }};
        const numNightsDisplay = document.getElementById("num-nights");
        const totalAmountDisplay = document.getElementById("total-amount");
        const totalAmountInput = document.getElementById("total_amount_input");

        // PRICE CALCULATION LOGIC
        function calculateTotal() {
            const checkInDate = new Date(checkInInput.value);
            const checkOutDate = new Date(checkOutInput.value);

            if (checkInInput.value && checkOutInput.value && checkOutDate > checkInDate) {
                const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
                const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                const total = nights * pricePerNight;

                numNightsDisplay.textContent = nights;
                totalAmountDisplay.textContent = `₹${total.toLocaleString('en-IN')}`;
                totalAmountInput.value = total;
            } else {
                numNightsDisplay.textContent = "--";
                totalAmountDisplay.textContent = "₹--";
                totalAmountInput.value = 0;
            }
        }

        // GUEST FORM TOGGLING LOGIC
        function toggleGuestForms() {
            const guestCount = parseInt(numberOfGuestsInput.value, 10) || 1;
            for (let i = 0; i < maxGuests; i++) {
                const form = document.getElementById(`guest-form-${i}`);
                const nameInput = document.getElementById(`guest_name_${i}`);
                const idType = document.getElementById(`guest_id_type_${i}`);
                const idNum = document.getElementById(`guest_id_number_${i}`);

                if (i < guestCount) {
                    form.style.display = "block";
                    nameInput.required = true;
                    idType.required = true;
                    idNum.required = true;
                    nameInput.disabled = false;
                    idType.disabled = false;
                    idNum.disabled = false;
                } else {
                    form.style.display = "none";
                    nameInput.required = false;
                    idType.required = false;
                    idNum.required = false;
                    nameInput.disabled = true;
                    idType.disabled = true;
                    idNum.disabled = true;
                }
            }
        }

        // DATE VALIDATION LOGIC
        function validateDates() {
            if (checkInInput.value) {
                let minDate = new Date(checkInInput.value);
                minDate.setDate(minDate.getDate() + 1);
                checkOutInput.min = minDate.toISOString().split("T")[0];
                if (checkOutInput.value && checkOutInput.value < checkOutInput.min) {
                    checkOutInput.value = "";
                }
            }
        }

        numberOfGuestsInput.addEventListener("input", toggleGuestForms);
        checkInInput.addEventListener("change", validateDates);
        checkInInput.addEventListener("change", calculateTotal); // Also trigger calculation
        checkOutInput.addEventListener("change", calculateTotal);

        toggleGuestForms();
        validateDates();
        calculateTotal();
    });
</script>
</body>
</html>
