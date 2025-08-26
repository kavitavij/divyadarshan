<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Devotee Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7f6;
        }

        .details-card {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            margin-top: 2rem;
        }

        .details-card .card-header {
            background-color: #4a148c;
            color: white;
            text-align: center;
            font-size: 1.5rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 1.25rem;
        }

        .devotee-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .devotee-card h3 {
            color: #4a148c;
            font-weight: 600;
            border-bottom: 2px solid #ede7f6;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .footer-container {
            background-color: #f8f9fa;
            border-top: 1px solid #e7e7e7;
            padding: 2.5rem 0;
            text-align: center;
            margin-top: 3rem;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row">
            {{-- Form --}}
            <div class="col-lg-8">
                <div class="card details-card">
                    <div class="card-header">
                        <h2>Enter Devotee Details</h2>
                    </div>
                    <div class="card-body">
                        <form id="devotee-form" action="{{ route('cart.addDarshan') }}" method="POST">
                            @csrf
                            <input type="hidden" name="selected_date" value="{{ $bookingData['selected_date'] }}">
                            <input type="hidden" name="temple_id" value="{{ $bookingData['temple_id'] }}">
                            <input type="hidden" name="darshan_slot_id" value="{{ $bookingData['darshan_slot_id'] }}">
                            <input type="hidden" name="number_of_people"
                                value="{{ $bookingData['number_of_people'] }}">

                            @for ($i = 0; $i < $bookingData['number_of_people']; $i++)
                                <div class="devotee-card">
                                    <h3>Devotee {{ $i + 1 }}</h3>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="first_name_{{ $i }}">First Name</label>
                                            <input type="text" name="devotees[{{ $i }}][first_name]"
                                                class="form-control devotee-input" data-index="{{ $i }}"
                                                data-field="first_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_name_{{ $i }}">Last Name</label>
                                            <input type="text" name="devotees[{{ $i }}][last_name]"
                                                class="form-control devotee-input" data-index="{{ $i }}"
                                                data-field="last_name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="age_{{ $i }}">Age</label>
                                            <input type="number" name="devotees[{{ $i }}][age]"
                                                class="form-control devotee-input" data-index="{{ $i }}"
                                                data-field="age" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label for="phone_number_{{ $i }}">Phone Number</label>
                                            <input type="tel" name="devotees[{{ $i }}][phone_number]"
                                                class="form-control devotee-input" data-index="{{ $i }}"
                                                data-field="phone_number" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>ID Type</label>
                                            <select name="devotees[{{ $i }}][id_type]" class="form-control"
                                                required>
                                                <option value="aadhar">Aadhar</option>
                                                <option value="pan">PAN</option>
                                                <option value="passport">Passport</option>
                                                <option value="voter_id">Voter ID</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>ID Number</label>
                                            <input type="text" name="devotees[{{ $i }}][id_number]"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            @endfor

                            <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                        </form>

                    </div>
                </div>
            </div>

            {{-- Summary (No changes needed here) --}}
            <div class="col-lg-4">
                <div class="card details-card">
                    <div class="card-header">
                        <h4>Booking Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="summary-box">
                            <p><strong>Temple:</strong> <span id="summary-temple">{{ $temple->name }}</span></p>
                            <p><strong>Number of Devotees:</strong> <span
                                    id="summary-count">{{ $bookingData['number_of_people'] }}</span></p>
                            <p><strong>Charge per Person:</strong> ₹<span
                                    id="summary-charge">{{ number_format($temple->darshan_charge, 2) }}</span></p>
                            <p class="fw-bold text-success"><strong>Total Charge:</strong> ₹<span
                                    id="summary-total">{{ number_format($temple->darshan_charge * $bookingData['number_of_people'], 2) }}</span>
                            </p>
                        </div>

                        <div class="summary-box mt-3">
                            <h5>Devotee Information</h5>
                            <ul id="devotee-summary-list" class="list-unstyled">
                                {{-- Filled via JavaScript --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Summary Script (No changes needed here) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const summaryList = document.getElementById('devotee-summary-list');
            const summaryCount = document.getElementById('summary-count');
            const summaryTotal = document.getElementById('summary-total');

            const numPeople = parseInt(summaryCount.textContent) || 0;
            const chargePerPerson = parseFloat(document.getElementById('summary-charge').textContent.replace(/,/g,
                '')) || 0;
            let devoteeData = Array(numPeople).fill({}).map(() => ({
                first_name: '',
                last_name: '',
                age: '',
                phone_number: ''
            }));

            function updateSummary() {
                summaryList.innerHTML = '';
                devoteeData.forEach((devotee, index) => {
                    const name = `${devotee.first_name || ''} ${devotee.last_name || ''}`.trim();
                    const age = devotee.age ? `(Age: ${devotee.age})` : '';
                    const phone = devotee.phone_number ?
                        `<br><small>Phone: ${devotee.phone_number}</small>` : '';
                    if (name || age || phone) {
                        const li = document.createElement('li');
                        li.classList.add('mb-2');
                        li.innerHTML = `<strong>Devotee ${index + 1}:</strong> ${name} ${age} ${phone}`;
                        summaryList.appendChild(li);
                    }
                });
            }
            document.getElementById('devotee-form').addEventListener('input', function(e) {
                if (e.target.classList.contains('devotee-input')) {
                    const index = parseInt(e.target.dataset.index);
                    const field = e.target.dataset.field;
                    if (!isNaN(index) && field && devoteeData[index]) {
                        devoteeData[index][field] = e.target.value;
                        updateSummary();
                    }
                }
            });

            updateSummary();
        });
    </script>
</body>

</html>
