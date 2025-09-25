<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Guest Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Lato', sans-serif;
            padding-top: 2rem;
        }

        .details-card {
            border: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            margin-top: 2rem;
            background-color: white;
        }

        .details-card .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            padding: 1.5rem;
            border-radius: 15px 15px 0 0;
        }

        .guest-details-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .guest-details-card h4 {
            color: #007bff;
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
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }

        .btn-proceed {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            font-size: 1.1rem;
            font-weight: 700;
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
            border-left: 5px solid #007bff;
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

        .price-summary {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
        }

        .price-summary h5 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #007bff;
        }

        .price-summary p {
            font-size: 1.1rem;
            margin: 0.5rem 0;
        }

        .price-summary .fs-5 {
            font-size: 1.25rem;
            color: green;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card details-card">
                    <div class="card-header">
                        <h2>Enter Your Details</h2>
                    </div>
                    <div class="card-body">
                        <!-- Error Message -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Room Summary -->
                        <div class="room-summary mb-4">
                            <h3>{{ $room->type }} Room at {{ $room->hotel->name }}</h3>
                            <p>Price: <strong>₹{{ number_format($room->discounted_price, 2) }}</strong> / night</p>
                        </div>

                        <!-- Form -->
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

                            <!-- Guest Forms -->
                            <div id="guest-forms-container">
                                @for ($i = 0; $i < $room->capacity; $i++)
                                    <div class="guest-details-card" id="guest-form-{{ $i }}" style="display:none;">
                                        <h4>Guest {{ $i + 1 }} Details</h4>
                                        <div class="details-grid">
                                            <div class="form-group">
                                                <label for="guest_name_{{ $i }}">Full Name</label>
                                                <input type="text" name="guests[{{ $i }}][name]"
                                                    id="guest_name_{{ $i }}" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="guest_id_type_{{ $i }}">ID Type</label>
                                                <select name="guests[{{ $i }}][id_type]"
                                                    id="guest_id_type_{{ $i }}" class="form-control" required>
                                                    <option value="aadhar">Aadhar Card</option>
                                                    <option value="pan">PAN Card</option>
                                                    <option value="passport">Passport</option>
                                                </select>
                                            </div>
                                            <div class="form-group grid-full-width">
                                                <label for="guest_id_number_{{ $i }}">ID Number</label>
                                                <input type="text" name="guests[{{ $i }}][id_number]"
                                                    id="guest_id_number_{{ $i }}" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <!-- Price Summary -->
                            <div class="price-summary my-4 p-3">
                                <h5>Price Details</h5>
                                <p class="mb-1">Number of Nights: <strong id="num-nights">--</strong></p>
                                <p class="mb-0 fs-5">Total Payable Amount: <strong id="total-amount" class="text-success">₹--</strong></p>
                                <input type="hidden" name="total_amount" id="total_amount_input" value="0">
                            </div>

                            <!-- Contact Details -->
                            <div class="row mt-4 mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="tel" name="phone_number" id="phone_number" class="form-control"
                                        value="{{ old('phone_number') }}" required>
                                </div>
                            </div>
                            <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" value="1" id="agree_terms" name="agree_terms" required>
            <label class="form-check-label" for="agree_terms">
                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-primary">
                    Terms and Conditions
                </a>
            </label>
        </div>
            <!-- Action Buttons -->
            <div class="form-footer mt-4">
              <div class="row g-3">
                <div class="col-md-4">
                  <button type="submit" class="btn btn-outline-secondary w-100 p-3 fw-bold"
                    formaction="{{ route('cart.addStay') }}">
                    🛒 Add to Cart
                  </button>
                </div>
                <div class="col-md-4">
                  <button type="submit" class="btn btn-success w-100 p-3 fw-bold"
                       formaction="{{ route('stays.payNow') }}">
                    💳 Pay Now Online
                  </button>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 p-3 fw-bold"
                        formaction="{{ route('stays.bookPayAtHotel') }}">
                        🏨 Pay at Hotel <span class="fw-normal" style="font-size: 0.9rem;">(+₹50 extra)</span>
                    </button>
                    </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const numberOfGuestsInput = document.getElementById("number_of_guests");
      const maxGuests = {{ $room->capacity }};
      const checkInInput = document.getElementById("check_in_date");
      const checkOutInput = document.getElementById("check_out_date");
      const pricePerNight = {{ $room->discounted_price }};
      const numNightsDisplay = document.getElementById("num-nights");
      const totalAmountDisplay = document.getElementById("total-amount");
      const totalAmountInput = document.getElementById("total_amount_input");

      function calculateTotal() {
        const ci = new Date(checkInInput.value);
        const co = new Date(checkOutInput.value);
        if (checkInInput.value && checkOutInput.value && co > ci) {
          const diffMs = co - ci;
          const nights = Math.ceil(diffMs / (1000 * 3600 * 24));
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
      checkInInput.addEventListener("change", calculateTotal);
      checkOutInput.addEventListener("change", calculateTotal);

      toggleGuestForms();
      validateDates();
      calculateTotal();
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions - {{ $room->hotel->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                @if($room->hotel->terms_and_conditions)
    <div class="trix-content">
        {!! $room->hotel->terms_and_conditions !!}
    </div>
@else
    <p>No specific terms and conditions have been provided for this hotel.</p>
@endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>