@extends('layouts.app')

@section('title', 'Submit a Complaint – DivyaDarshan')

@push('styles')
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
    * { box-sizing: border-box; }
    body { background: var(--bg); color: var(--ink); }
    .wrap { max-width: 1000px; margin: auto; padding: 24px 18px; }
    .card {
        background: #fff; border-radius: 14px;
        box-shadow: 0 8px 24px rgba(20,16,50,.06);
        overflow: hidden; border: 1px solid #eee;
    }
    header.hero {
        background: linear-gradient(135deg,var(--brand) 0%,#6a1b9a 55%,#7b1fa2 100%);
        color: #fff; padding: 28px 22px;
    }
    .hero h1 { margin:0; font-size:clamp(22px,2.4vw,34px); }
    .hero p { margin:8px 0 0; color:#f3e8ff; }
    .content { padding:22px; }
    .lead {
        background:var(--brand-soft); border-left:5px solid var(--brand);
        padding:14px; border-radius:10px; color:#3c2a63; margin-bottom:24px;
    }
    .form-group { margin-bottom:18px; }
    label { font-weight:600; color:var(--ink); margin-bottom:6px; display:block; }
    input, textarea, select {
        width:100%; padding:12px; border:1px solid #ccc; border-radius:8px;
        font-size:16px; background:#fdfdfd;
    }
    input:focus, textarea:focus, select:focus {
        outline:none; border-color:var(--brand);
        box-shadow:0 0 0 2px var(--brand-soft);
    }
    button[type="submit"] {
        background:var(--brand); color:#fff; border:none;
        padding:12px 25px; font-weight:600; font-size:16px;
        border-radius:8px; cursor:pointer; transition:background 0.3s;
        width:100%; max-width:250px; margin:10px auto 0; display:block;
    }
    button[type="submit"]:hover { background:#6a1b9a; }
    .alert-danger {
        background:#fdeded; border:1px solid var(--warn);
        color:#58151c; padding:14px; border-radius:8px; margin-bottom:18px;
    }
    .alert-danger ul { margin:0; padding-left:20px; }
    .modal-overlay {
        display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%;
        background:rgba(0,0,0,0.6); justify-content:center; align-items:center;
    }
    .modal-content {
        background:#fff; padding:30px; border-radius:14px; text-align:center;
        width:90%; max-width:400px; box-shadow:0 8px 24px rgba(20,16,50,.1);
        animation:fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn { from{opacity:0; transform:scale(0.9);} to{opacity:1; transform:scale(1);} }
    .modal-content h2 { margin-top:0; color:var(--ink); font-size:24px; }
    .modal-content p { color:var(--muted); margin-bottom:25px; font-size:16px; }
    .modal-content button {
        background:var(--brand); color:white; padding:12px 25px;
        border:none; border-radius:8px; cursor:pointer; font-size:16px; font-weight:600;
    }
    .modal-content button:hover { background:#6a1b9a; }
</style>
@endpush

@section('content')
<div class="wrap">
    <div class="card">
        <header class="hero">
            <h1>Submit a Complaint</h1>
            <p>We value your feedback and strive to address concerns promptly.</p>
        </header>

        <div class="content">
            <p class="lead">Please fill out the form below with accurate details so we can look into your issue effectively.</p>

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
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal-overlay">
    <div class="modal-content">
        <h2>Thank You!</h2>
        <p>Your complaint has been submitted successfully.</p>
        <button id="backToHomeBtn">Back to Home</button>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('#complaintForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const errorContainer = $('#formErrors').hide();
        const submitButton = form.find('button[type="submit"]');
        const originalText = submitButton.text();

        submitButton.text('Submitting...').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('#successModal').css('display', 'flex');
                    $('#backToHomeBtn').on('click', function() {
                        window.location.href = res.redirect_url;
                    });
                }
            },
            error: function(xhr) {
                submitButton.text(originalText).prop('disabled', false);
                let html = '<ul><li>An unexpected error occurred. Please try again later.</li></ul>';
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    html = '<ul>';
                    $.each(xhr.responseJSON.errors, function(_, val) {
                        html += `<li>${val[0]}</li>`;
                    });
                    html += '</ul>';
                }
                errorContainer.html(html).show();
                $('html, body').animate({scrollTop: form.offset().top - 20}, 500);
            }
        });
    });
});
</script>
@endpush
