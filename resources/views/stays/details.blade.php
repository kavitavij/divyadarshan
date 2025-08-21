<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Guest Details</title>
    {{-- Link to Bootstrap CSS to provide base styling --}}
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
            /* Ensures child elements conform to border-radius */
        }

        .details-card .card-header {
            background-color: #0056b3;
            /* A professional blue */
            color: white;
            text-align: center;
            font-size: 1.6rem;
            font-weight: 600;
            border-bottom: 0;
            padding: 1.5rem;
        }

        .details-card .card-body {
            padding: 2.5rem;
        }

        /* This is the new class for the styled guest forms */
        .guest-details-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            /* Replaces the old mt-4 */
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

        /* Responsive grid for larger screens */
        @media (min-width: 768px) {
            .details-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .grid-full-width {
                grid-column: 1 / -1;
                /* Makes an item span the full grid width */
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

        .room-summary p {
            margin-bottom: 0.25rem;
            color: #333;
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
                        <div class="room-summary">
                            <h3>{{ $room->type }} Room at {{ $room->hotel->name }}</h3>
                            <p class="text-gray-600">Price:
                                <strong>₹{{ number_format($room->price_per_night, 2) }}</strong> / night
                            </p>
                        </div>

                        <form action="{{ route('stays.store', $room) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="check_in_date">Check-in Date</label>
                                    <input type="date" name="check_in_date" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="check_out_date">Check-out Date</label>
                                    <input type="date" name="check_out_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="number_of_guests">Number of Guests (Max: {{ $room->capacity }})</label>
                                <input type="number" name="number_of_guests" id="number_of_guests" class="form-control"
                                    min="1" max="{{ $room->capacity }}" required>
                            </div>

                            {{-- Container for dynamically shown guest forms --}}
                            <div id="guest-forms-container">
                                @for ($i = 0; $i < $room->capacity; $i++)
                                    <div class="guest-details-card" id="guest-form-{{ $i }}"
                                        style="display: {{ $i === 0 ? 'block' : 'none' }};">
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

                            <div class="form-group mb-3 mt-4">
                                <label for="phone_number">Contact Phone Number</label>
                                <input type="tel" name="phone_number" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-3 btn-proceed">Proceed to
                                Summary</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const numberOfGuestsInput = document.getElementById('number_of_guests');
            const guestFormsContainer = document.getElementById('guest-forms-container');
            const maxGuests = {{ $room->capacity }};

            function toggleGuestForms() {
                // Default to 1 if the input is empty or invalid
                const guestCount = parseInt(numberOfGuestsInput.value, 10) || 1;

                for (let i = 0; i < maxGuests; i++) {
                    const form = document.getElementById(`guest-form-${i}`);
                    const nameInput = document.getElementById(`guest_name_${i}`);
                    const idNumberInput = document.getElementById(`guest_id_number_${i}`);

                    if (form) {
                        if (i < guestCount) {
                            form.style.display = 'block';
                            nameInput.required = true;
                            idNumberInput.required = true;
                        } else {
                            form.style.display = 'none';
                            nameInput.required = false;
                            idNumberInput.required = false;
                        }
                    }
                }
            }

            numberOfGuestsInput.addEventListener('input', toggleGuestForms);

            // Set initial state based on default value
            if (!numberOfGuestsInput.value) {
                numberOfGuestsInput.value = 1;
            }

            // Initialize on page load
            toggleGuestForms();
        });
    </script>

</body>

</html>
