<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Submit a Complaint – DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        :root {
            --brand: #4a148c;
            --brand-soft: #ede7f6;
            --accent: #ffb300;
            --ink: #2c3e50;
            --muted: #6b7280;
            --bg: #f8f7fb;
            --ok: #2e7d32;
            --warn: #c62828;
        }
        *{ box-sizing: border-box; }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--ink);
            line-height: 1.6;
        }
        .wrap {
            max-width: 1000px;
            margin: auto;
            padding: 24px 18px;
        }
        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(20, 16, 50, .06);
            overflow: hidden;
            border: 1px solid #eee;
        }
        header.hero {
            background: linear-gradient(135deg, var(--brand) 0%, #6a1b9a 55%, #7b1fa2 100%);
            color: #fff;
            padding: 28px 22px;
        }
        .hero h1 { margin: 0; font-size: clamp(22px, 2.4vw, 34px); letter-spacing: .3px; }
        .hero p { margin: 8px 0 0; color: #f3e8ff; }
        .content { padding: 22px; }
        .lead {
            background: var(--brand-soft);
            border-left: 5px solid var(--brand);
            padding: 14px 14px;
            border-radius: 10px;
            color: #3c2a63;
            margin: 0 0 24px; /* Increased margin */
        }
        .form-group {
            margin-bottom: 18px; /* Added for consistent spacing */
        }
        label {
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 6px;
            display: block;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px; /* Increased padding */
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px; /* Increased font size */
            font-family: inherit;
            background-color: #fdfdfd;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 2px var(--brand-soft);
        }
        button[type="submit"] {
            background: var(--brand);
            color: #fff;
            border: none;
            padding: 12px 25px; /* Adjusted padding */
            font-weight: 600;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%; /* Make button full width */
            max-width: 250px; /* Optional: constrain max width */
            display: block;
            margin: 10px auto 0; /* Center the button */
        }
        button[type="submit"]:hover { background: #6a1b9a; }
        footer {
            padding: 16px;
            text-align: center;
            color: var(--muted);
            font-size: 14px;
            border-top: 1px solid #eee;
        }
        footer .links a {
            color: var(--brand);
            text-decoration: none;
            font-weight: 600;
            margin: 0 6px;
        }
        .muted { color: var(--muted); }
        .tag { display: inline-block; font-size: 12px; background: #eef2ff; color: #27348b; padding: 4px 8px; border-radius: 999px; margin-left: 6px; }

        /* --- ERROR MESSAGE STYLES --- */
        .alert-danger {
            background: #fdeded;
            border: 1px solid var(--warn);
            color: #58151c;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 18px;
        }
        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }

        /* --- MODAL STYLES --- */
        .modal-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 14px; /* Matched card radius */
            text-align: center;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 8px 24px rgba(20, 16, 50, .1);
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-content h2 {
            margin-top: 0;
            color: var(--ink);
            font-size: 24px;
        }
        .modal-content p {
            color: var(--muted);
            margin-bottom: 25px;
            font-size: 16px;
        }
        .modal-content button {
            background: var(--brand); /* Matched theme button */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px; /* Matched theme button */
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .modal-content button:hover {
            background: #6a1b9a; /* Matched theme button hover */
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <header class="hero">
                <h1>Submit a Complaint</h1>
                <p>We value your feedback and strive to address concerns promptly.</p>
            </header>

            <div class="content">
                <p class="lead">Please fill out the form below with accurate details so we can look into your issue effectively.</p>

                <!-- Error messages will be displayed here -->
                <div id="formErrors" class="alert-danger" style="display:none;"></div>

                <form action="{{ route('complaint.form') }}" method="POST" id="complaintForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" id="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" id="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="issue_type">Type of Issue</label>
                        <select name="issue_type" id="issue_type" required>
                            <option value="general_feedback">General Feedback</option>
                            <option value="technical_problem">Technical Problem</option>
                            <option value="booking_issue">Booking Issue</option>
                            <option value="content_error">Content Error</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Complaint Details</label>
                        <textarea name="message" id="message" rows="5" required></textarea>
                    </div>

                    <button type="submit">Submit Complaint</button>
                </form>
            </div>

            <footer>
                <div class="links">
                    <a href="{{ route('terms') }}">Terms</a> •
                    <a href="{{ route('guidelines') }}">Guidelines</a> •
                    <a href="{{ route('complaint.form') }}">Complaint</a>
                </div>
                <div class="muted">© {{ date('Y') }} DivyaDarshan <span class="tag">Pilgrimage Care</span></div>
            </footer>
        </div>
    </div>

    <!-- START: POP-UP MODAL HTML -->
    <div id="successModal" class="modal-overlay">
        <div class="modal-content">
            <h2>Thank You!</h2>
            <p>Your complaint has been submitted successfully.</p>
            <button id="backToHomeBtn">Back to Home</button>
        </div>
    </div>
    <!-- END: POP-UP MODAL HTML -->

    <!-- START: JAVASCRIPT -->
    {{-- You need jQuery for the AJAX script to work. --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        // Intercept the form submission
        $('#complaintForm').on('submit', function(e) {
            e.preventDefault(); // Stop the form from submitting the traditional way

            var form = $(this);
            var url = form.attr('action');
            var errorContainer = $('#formErrors');
            errorContainer.hide(); // Hide errors on a new submission

            // Optional: Add a loading state to the button
            var submitButton = form.find('button[type="submit"]');
            var originalButtonText = submitButton.text();
            submitButton.text('Submitting...').prop('disabled', true);

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(), // Serialize form data
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Show the modal
                        $('#successModal').css('display', 'flex');

                        // When the "Back to Home" button is clicked, redirect
                        $('#backToHomeBtn').on('click', function() {
                            window.location.href = response.redirect_url;
                        });
                    }
                },
                error: function(xhr) {
                    // Restore button state on error
                    submitButton.text(originalButtonText).prop('disabled', false);
                    
                    errorContainer.html(''); // Clear previous errors
                    var errorMessage = '<ul><li>An unexpected error occurred. Please try again later.</li></ul>';

                    // Handle Laravel validation errors (status 422)
                    if (xhr.status === 422 && xhr.responseJSON) {
                        var errors = xhr.responseJSON.errors;
                        var errorList = '<ul>';
                        $.each(errors, function(key, value) {
                            errorList += '<li>' + value[0] + '</li>'; // Display the first error for each field
                        });
                        errorList += '</ul>';
                        errorContainer.html(errorList);
                    } else {
                        // For all other errors, show a generic message
                        errorContainer.html(errorMessage);
                    }
                    
                    errorContainer.show(); // Display the error container
                    // Scroll to the top of the form to make sure the user sees the error
                    $('html, body').animate({
                        scrollTop: form.offset().top - 20
                    }, 500);
                }
            });
        });
    });
    </script>
    <!-- END: JAVASCRIPT -->
</body>
</html>
