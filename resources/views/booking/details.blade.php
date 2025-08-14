<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Devotee Details</title>
    {{-- Link to Bootstrap CSS to maintain basic styling --}}
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
        background-color: #4a148c; /* Deep purple */
        color: white;
        text-align: center;
        font-size: 1.5rem;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.25rem;
    }
    .details-card .card-body {
        padding: 2rem;
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
    .details-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    @media (min-width: 768px) {
        .details-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .btn-submit-booking {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        font-size: 1.2rem;
        padding: 0.75rem;
        width: 100%;
    }

    /* --- Styles for the summary box --- */
    .summary-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .summary-box h4 {
        color: #4a148c;
        font-weight: 600;
        border-bottom: 2px solid #ede7f6;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    /* --- FOOTER STYLES --- */
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
        {{-- Main Form Section --}}
        <div class="col-lg-8">
            <div class="card details-card">
                <div class="card-header"><h2>Enter Devotee Details</h2></div>
                <div class="card-body">
                    {{-- THE FIX: Simplified the introductory text --}}
                    <p class="text-muted mb-4">Please provide the required details for booking darshan.</p>
                    
                    <form id="devotee-form" action="{{ route('booking.confirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="temple_id" value="{{ $bookingData['temple_id'] }}">
                        <input type="hidden" name="darshan_slot_id" value="{{ $bookingData['darshan_slot_id'] }}">
                        <input type="hidden" name="number_of_people" value="{{ $bookingData['number_of_people'] }}">

                        <div>
                            @for ($i = 0; $i < $bookingData['number_of_people']; $i++)
                                <div class="devotee-card">
                                    <h3>Devotee {{ $i + 1 }}</h3>
                                    <div class="details-grid">
                                        <div class="form-group">
                                            <label for="first_name_{{ $i }}">First Name</label>
                                            <input type="text" name="devotees[{{ $i }}][first_name]" id="first_name_{{ $i }}" class="form-control devotee-input" data-index="{{ $i }}" data-field="first_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name_{{ $i }}">Last Name</label>
                                            <input type="text" name="devotees[{{ $i }}][last_name]" id="last_name_{{ $i }}" class="form-control devotee-input" data-index="{{ $i }}" data-field="last_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="age_{{ $i }}">Age</label>
                                            <input type="number" name="devotees[{{ $i }}][age]" id="age_{{ $i }}" class="form-control devotee-input" data-index="{{ $i }}" data-field="age" min="1" max="120" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_number_{{ $i }}">Phone Number</label>
                                            <input type="tel" name="devotees[{{ $i }}][phone_number]" id="phone_number_{{ $i }}" class="form-control devotee-input" data-index="{{ $i }}" data-field="phone_number" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="id_type_{{ $i }}">Government ID Type</label>
                                            <select name="devotees[{{ $i }}][id_type]" id="id_type_{{ $i }}" class="form-control" required>
                                                <option value="aadhar">Aadhar Card</option>
                                                <option value="pan">PAN Card</option>
                                                <option value="passport">Passport</option>
                                                <option value="voter_id">Voter ID</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="id_number_{{ $i }}">ID Number</label>
                                            <input type="text" name="devotees[{{ $i }}][id_number]" id="id_number_{{ $i }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 btn-submit-booking">Submit Booking</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Live Summary Section --}}
        <div class="col-lg-4">
            <div class="card details-card">
                <div class="card-header"><h4> Booking Summary</h4></div>
                <div class="card-body">
                    <div class="summary-box">
                        <h5>Booking Details</h5>
                        <p><strong>Temple ID:</strong> {{ $bookingData['temple_id'] }}</p>
                        <p><strong>Number of Devotees:</strong> {{ $bookingData['number_of_people'] }}</p>
                    </div>
                    <div id="live-summary-content" class="summary-box mt-3">
                        <h5>Devotee Information</h5>
                        <ul id="devotee-summary-list" class="list-unstyled">
                            {{-- Live summary will be populated here by JavaScript --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer-container">
    <div class="container">
        <h4>Have questions about these Terms or need assistance?</h4>
        <div class="footer-buttons mt-3">
            <a href="{{ route('complaint.form') }}" class="btn btn-outline-danger">Submit a Complaint</a>
            <a href="{{ route('guidelines') }}" class="btn btn-outline-secondary">Read Guidelines</a>
        </div>
        <p>We’re here to guide you every step of your spiritual journey.</p>
        <div class="footer-links">
            <a href="{{ route('terms') }}">Terms</a> •
            <a href="{{ route('guidelines') }}">Guidelines</a> •
            <a href="{{ route('complaint.form') }}">Complaint</a>
        </div>
        <div class="footer-copyright">
            © {{ date('Y') }} DivyaDarshan Pilgrimage Care
        </div>
    </div>
</footer>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('devotee-form');
    const summaryList = document.getElementById('devotee-summary-list');
    const numberOfPeople = {{ $bookingData['number_of_people'] }};
    let devoteeData = Array(numberOfPeople).fill({}).map(() => ({ first_name: '', last_name: '', age: '', phone_number: '' }));

    // Function to update the summary UI
    function updateSummary() {
        summaryList.innerHTML = ''; // Clear the list
        devoteeData.forEach((devotee, index) => {
            const name = `${devotee.first_name || ''} ${devotee.last_name || ''}`.trim();
            const age = devotee.age ? `(Age: ${devotee.age})` : '';
            const phone = devotee.phone_number ? `<br><small>Phone: ${devotee.phone_number}</small>` : '';
            
            if (name || age || phone) {
                const li = document.createElement('li');
                li.classList.add('mb-2');
                li.innerHTML = `<strong>Devotee ${index + 1}:</strong> ${name} ${age} ${phone}`;
                summaryList.appendChild(li);
            }
        });
    }

    // Initialize the summary with empty slots
    updateSummary();

    // Add event listeners to all relevant input fields
    form.addEventListener('input', function(e) {
        if (e.target.classList.contains('devotee-input')) {
            const index = e.target.dataset.index;
            const field = e.target.dataset.field;
            devoteeData[index][field] = e.target.value;
            updateSummary();
        }
    });
});
</script>

</body>
</html>
