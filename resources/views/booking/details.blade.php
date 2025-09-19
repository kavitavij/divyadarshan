<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Devotee Details - Divyadarshan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f7f8fa; font-family: 'Poppins', sans-serif; }

        .progress-stepper { display: flex; justify-content: space-between; width: 100%; position: relative; margin-bottom: 2.5rem; }
        .progress-stepper::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 2px; background-color: #e0e0e0; transform: translateY(-50%); z-index: 1; }

        .step { text-align: center; position: relative; z-index: 2; }
        .step-icon { width: 40px; height: 40px; border-radius: 50%; background-color: #fff; border: 2px solid #e0e0e0; display: flex; align-items: center; justify-content: center; color: #bdbdbd; font-weight: bold; margin: 0 auto 0.5rem; }
        .step-label { font-size: 0.85rem; color: #757575; }

        .step.completed .step-icon { ba
        .step.active .step-icon { background-color: #4f46e5; border-color: #4f46e5; color: #fff; }
        .step.active .step-label { font-weight: 600; color: #4f46e5; }

        .main-card { border: none; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border-radius: 12px; background-color: #fff; }
        .main-card-header { background-color: transparent; border-bottom: 1px solid #eee; padding: 1.25rem 1.5rem; font-size: 1.25rem; font-weight: 600; }

        .accordion-item { border: 1px solid #e0e0e0 !important; border-radius: 8px !important; margin-bottom: 1rem; }
        .accordion-button { font-weight: 600; color: #333; }
        .accordion-button:not(.collapsed) { background-color: #f3f0ff; color: #4f46e5; box-shadow: none; }
        .accordion-button:focus { box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25); }

        .summary-card { position: sticky; top: 20px; }
        .summary-list li { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px dashed #eee; }
        .summary-list li:last-child { border-bottom: none; }
        .total-payable { background-color: #e8f5e9; border-radius: 8px; padding: 1rem; }

        /* ✅ Button purple */
        .btn-proceed { background: linear-gradient(90deg, #4f46e5, #4338ca); border: none; padding: 0.75rem 1.5rem; font-size: 1.1rem; font-weight: 600; border-radius: 8px; color: #fff; }
        .terms-link { color: #4f46e5; text-decoration: none; font-weight: 600; }

        .pincode-wrapper { position: relative; }
        .pincode-loader { position: absolute; top: 50%; right: 10px; transform: translateY(-50%); display: none; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="progress-stepper">
            <div class="step completed"><div class="step-icon"><i class="fas fa-check"></i></div><div class="step-label">Select Date & Time</div></div>
            <div class="step active"><div class="step-icon">2</div><div class="step-label">Add Devotee(s) Details</div></div>
            <div class="step"><div class="step-icon">3</div><div class="step-label">Review & Pay</div></div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card main-card">
                    <div class="card-header main-card-header">Add Devotee(s) Details</div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <h5 class="alert-heading">Please correct the errors below:</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form id="devotee-form" action="{{ route('cart.addDarshan') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="temple_id" value="{{ $bookingData['temple_id'] ?? '' }}">
                            <input type="hidden" name="darshan_slot_id" value="{{ $bookingData['darshan_slot_id'] ?? '' }}">
                            <input type="hidden" name="selected_date" value="{{ $bookingData['selected_date'] ?? '' }}">
                            <input type="hidden" name="number_of_people" value="{{ $bookingData['number_of_people'] ?? '' }}">
                            <input type="hidden" name="slot_details" value="{{ $bookingData['slot_details'] ?? '' }}">

                            <div class="accordion" id="devoteeAccordion">
                                @for ($i = 0; $i < ($bookingData['number_of_people'] ?? 0); $i++)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $i }}">
                                            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}">
                                                <i class="fas fa-user me-2"></i> Devotee {{ $i + 1 }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#devoteeAccordion">
                                            <div class="accordion-body">
                                                <div class="row g-3">
                                                    <div class="col-12"><label class="form-label">Full Name</label><input type="text" name="devotees[{{ $i }}][full_name]" class="form-control devotee-input" data-index="{{ $i }}" data-field="full_name" required></div>
                                                    <div class="col-md-6"><label class="form-label">Gender</label><div class="d-flex"><div class="form-check me-3"><input class="form-check-input" type="radio" name="devotees[{{ $i }}][gender]" id="male_{{ $i }}" value="male" checked><label class="form-check-label" for="male_{{ $i }}">Male</label></div><div class="form-check"><input class="form-check-input" type="radio" name="devotees[{{ $i }}][gender]" id="female_{{ $i }}" value="female"><label class="form-check-label" for="female_{{ $i }}">Female</label></div></div></div>
                                                    <div class="col-md-6"><label class="form-label">Age</label><input type="number" name="devotees[{{ $i }}][age]" class="form-control devotee-input" min="1" max="120" data-index="{{ $i }}" data-field="age" required></div>
                                                    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="devotees[{{ $i }}][email]" class="form-control" required></div>
                                                    <div class="col-md-6"><label class="form-label">Mobile Number</label><input type="tel" name="devotees[{{ $i }}][phone_number]" class="form-control" required></div>
                                                    <div class="col-md-4">
                                                        <label for="pincode-input-{{$i}}" class="form-label">Pincode</label>
                                                        <div class="pincode-wrapper">
                                                            <input type="text" id="pincode-input-{{$i}}" name="devotees[{{ $i }}][pincode]" class="form-control pincode-input" data-index="{{ $i }}" maxlength="6" pattern="\d{6}" required>
                                                            <div class="spinner-border spinner-border-sm text-secondary pincode-loader" id="pincode-loader-{{$i}}"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4"><label class="form-label">City</label><input type="text" id="city-autofill-{{$i}}" name="devotees[{{ $i }}][city]" class="form-control" readonly required></div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">State</label>
                                                        <select id="state-autofill-{{$i}}" name="devotees[{{ $i }}][state]" class="form-select" disabled required>
                                                            <option value="">-- Pincode First --</option>
                                                            <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                            <option value="Assam">Assam</option>
                                                            <option value="Bihar">Bihar</option>
                                                            <option value="Chandigarh">Chandigarh</option>
                                                            <option value="Chhattisgarh">Chhattisgarh</option>
                                                            <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
                                                            <option value="Delhi">Delhi</option>
                                                            <option value="Goa">Goa</option>
                                                            <option value="Gujarat">Gujarat</option>
                                                            <option value="Haryana">Haryana</option>
                                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                                            <option value="Jharkhand">Jharkhand</option>
                                                            <option value="Karnataka">Karnataka</option>
                                                            <option value="Kerala">Kerala</option>
                                                            <option value="Ladakh">Ladakh</option>
                                                            <option value="Lakshadweep">Lakshadweep</option>
                                                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                            <option value="Maharashtra">Maharashtra</option>
                                                            <option value="Manipur">Manipur</option>
                                                            <option value="Meghalaya">Meghalaya</option>
                                                            <option value="Mizoram">Mizoram</option>
                                                            <option value="Nagaland">Nagaland</option>
                                                            <option value="Odisha">Odisha</option>
                                                            <option value="Puducherry">Puducherry</option>
                                                            <option value="Punjab">Punjab</option>
                                                            <option value="Rajasthan">Rajasthan</option>
                                                            <option value="Sikkim">Sikkim</option>
                                                            <option value="Tamil Nadu">Tamil Nadu</option>
                                                            <option value="Telangana">Telangana</option>
                                                            <option value="Tripura">Tripura</option>
                                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                            <option value="Uttarakhand">Uttarakhand</option>
                                                            <option value="West Bengal">West Bengal</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12"><label class="form-label">Full Address</label><textarea name="devotees[{{ $i }}][address]" class="form-control" rows="2" required></textarea></div>
                                                    <div class="col-md-5"><label class="form-label">ID Proof Type</label><select name="devotees[{{ $i }}][id_type]" class="form-select" required><option value="">Select ID</option><option value="aadhar">Aadhar Card</option><option value="pan">PAN Card</option><option value="passport">Passport</option><option value="voter_id">Voter ID</option></select></div>
                                                    <div class="col-md-7"><label class="form-label">ID Number</label><input type="text" name="devotees[{{ $i }}][id_number]" class="form-control" required></div>
                                                    <div class="col-12"><label class="form-label">Upload ID Proof Photo</label><input class="form-control" type="file" name="devotees[{{ $i }}][id_photo]" accept="image/png, image/jpeg, image/jpg" required></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            <div class="form-check my-4"><input class="form-check-input" type="checkbox" value="" id="declarationCheck" required><label class="form-check-label" for="declarationCheck">Please Accept <a href="#" class="terms-link" data-bs-toggle="modal" data-bs-target="#termsModal">General Terms & Conditions</a> for the Advance Darshan Booking.</label></div>
                            <button type="submit" class="btn btn-primary w-100 btn-proceed">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card main-card summary-card">
                    <div class="card-header main-card-header">Booking Summary</div>
                    <div class="card-body p-4">
                        <h5>{{ $temple->name ?? 'N/A' }}</h5>
                        <p class="text-muted"><i class="fas fa-map-marker-alt me-1"></i> {{ $temple->city ?? 'N/A' }}, {{ $temple->state ?? 'N/A' }}</p>
                        <ul class="list-unstyled summary-list">
                        <li>
                            <span><i class="fas fa-calendar-alt text-muted me-2"></i>Darshan Date</span>
                            <strong>{{ \Carbon\Carbon::parse($bookingData['selected_date'])->format('d M, Y') }}</strong>
                        </li>
                            <li>
                                <span><i class="fas fa-clock text-muted me-2"></i>Time Slot</span>
                                <strong>
                                    {{ \Carbon\Carbon::parse($darshanSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($darshanSlot->end_time)->format('h:i A') }}
                                </strong>
                            </li>

                            <li>
                                <span><i class="fas fa-users text-muted me-2"></i>Devotees</span>
                                <strong>{{ $bookingData['number_of_people'] }}</strong>
                            </li>
                        </ul>
                        <div id="devotee-summary-list" class="my-3"></div>
                        <hr>
                        <h6 class="mb-3">Price Details</h6>
                        <ul class="list-unstyled summary-list">
                            <li><span>Base Price</span> <span>₹{{ number_format($temple->darshan_charge ?? 0, 2) }} x {{ $bookingData['number_of_people'] }}</span></li>
                            <li><span>Sub Total</span> <span>₹{{ number_format(($temple->darshan_charge ?? 0) * $bookingData['number_of_people'], 2) }}</span></li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center fw-bold mt-3 total-payable">
                            <span class="fs-5">Amount Payable</span>
                            <span class="fs-5 text-success">₹{{ number_format(($temple->darshan_charge ?? 0) * $bookingData['number_of_people'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">General Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if(!empty($temple->terms_and_conditions))
                        {{-- Loop through the array and display as an ordered list --}}
                        <ol>
                            @foreach($temple->terms_and_conditions as $term)
                                <li>{{ $term }}</li>
                            @endforeach
                        </ol>
                    @else
                        {{-- Fallback text if no terms are set for a temple --}}
                        <p><strong>Default Terms:</strong> Please contact the temple administration for booking terms and conditions.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="termsModal" tabindex="-1"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const summaryList = document.getElementById('devotee-summary-list');
    const numPeople = parseInt("{{ $bookingData['number_of_people'] ?? 0 }}");
    let devoteeData = Array(numPeople).fill({}).map(() => ({ full_name: '', age: '' }));
    function updateSummary() {
        summaryList.innerHTML = '';
        const header = document.createElement('h6');
        header.innerHTML = '<small class="text-muted">Devotee List</small>';
        summaryList.appendChild(header);
        devoteeData.forEach((devotee, index) => {
            const name = (devotee.full_name || '...').trim();
            const age = devotee.age ? `, Age: ${devotee.age}` : '';
            const p = document.createElement('p');
            p.classList.add('mb-1');
            p.innerHTML = `<small>${index + 1}. ${name}${age}</small>`;
            summaryList.appendChild(p);
        });
    }
    document.getElementById('devotee-form').addEventListener('input', function(e) {
        if (e.target.classList.contains('devotee-input')) {
            const index = parseInt(e.target.dataset.index);
            const field = e.target.dataset.field;
            if (!isNaN(index) && devoteeData[index]) {
                devoteeData[index][field] = e.target.value;
                updateSummary();
            }
        }
    });
    updateSummary();

    let debounceTimer;
    const debounce = (callback, time) => {
        return function(...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => callback.apply(this, args), time);
        };
    };
    const fetchLocationData = async (pincode, index) => {
        const pincodeInput = document.getElementById(`pincode-input-${index}`);
        const cityInput = document.getElementById(`city-autofill-${index}`);
        const stateSelect = document.getElementById(`state-autofill-${index}`);
        const loader = document.getElementById(`pincode-loader-${index}`);
        pincodeInput.classList.remove('is-invalid');
        cityInput.value = '';
        stateSelect.value = '';
        if (pincode.length !== 6) {
            stateSelect.disabled = true;
            return;
        }
        loader.style.display = 'block';
        try {
            const response = await fetch(`https://api.postalpincode.in/pincode/${pincode}`);
            if (!response.ok) throw new Error('API request failed');
            const data = await response.json();
            if (data && data[0].Status === 'Success') {
                const postOffice = data[0].PostOffice[0];
                cityInput.value = postOffice.District;
                stateSelect.value = postOffice.State;
                if (stateSelect.value) {
                    stateSelect.disabled = false;
                }
            } else {
                pincodeInput.classList.add('is-invalid');
                stateSelect.disabled = true;
            }
        } catch (error) {
            console.error("Pincode fetch error:", error);
            pincodeInput.classList.add('is-invalid');
            stateSelect.disabled = true;
        } finally {
            loader.style.display = 'none';
        }
    };
    const debouncedFetch = debounce(fetchLocationData, 400);
    document.getElementById('devotee-form').addEventListener('input', function(e) {
        if (e.target && e.target.classList.contains('pincode-input')) {
            debouncedFetch(e.target.value, e.target.dataset.index);
        }
    });
});
</script>

</body>
</html>
