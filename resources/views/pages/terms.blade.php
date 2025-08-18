<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Terms & Conditions – DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        :root{
            --brand:#4a148c;
            --brand-soft:#ede7f6;
            --accent:#ffb300;
            --ink:#2c3e50;
            --muted:#6b7280;
            --bg:#f8f7fb;
            --ok:#2e7d32;
            --warn:#c62828;
        }

        *{
          box-sizing:border-box
        
        }
        body{
            margin:0; font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Helvetica,Arial,sans-serif;
            background:var(--bg); color:var(--ink); line-height:1.6;
        }
        .wrap{
            max-width:1000px; margin:auto; padding:24px 18px;
        }
        .card{
            background:#fff; border-radius:14px; box-shadow:0 8px 24px rgba(20,16,50,.06);
            overflow:hidden; border:1px solid #eee;
        }
        header.hero{
            background:linear-gradient(135deg,var(--brand) 0%, #6a1b9a 55%, #7b1fa2 100%);
            color:#fff; padding:28px 22px;
        }
        .hero h1{
          margin:0; 
          font-size:clamp(22px,2.4vw,34px); 
          letter-spacing:.3px
        }

        .hero p{
          margin:8px 0 0;
          color:#f3e8ff
        }
        .content{
          padding:22px
        }
        .lead{
            background:var(--brand-soft); border-left:5px solid var(--brand);
            padding:14px 14px; border-radius:10px; color:#3c2a63; margin:0 0 18px;
        }
        .section{margin:22px 0}
        .section h2{
            display:flex; align-items:center; gap:10px;
            font-size:clamp(18px,2vw,22px); margin:0 0 8px; color:var(--brand);
        }
        .section h2 .badge{
            background:var(--accent); 
            color:#3b2e04; 
            font-weight:700; 
            border-radius:999px;
            padding:4px 10px; 
            font-size:12px;
        }
        ul.clean{
          margin:0; padding:0 0 0 18px
        }
        ul.clean li{
          margin:8px 0
        }
        .cta{
            background:linear-gradient(135deg,#fff 0%, #fff6e0 100%);
            border:1px solid #ffe6b3; border-radius:12px; padding:16px; text-align:center; margin-top:6px;
        }
        .cta a{
            display:inline-block; margin:10px 8px 0; padding:10px 14px; text-decoration:none;
            border-radius:10px; font-weight:600; border:1px solid #e2d2a6; color:#5b4200;
            transition:transform .05s ease-in-out;
            background:#fff3cd;
        }
        .cta a:hover{transform:translateY(-1px)}
        footer{
            padding:16px; text-align:center; color:var(--muted); font-size:14px; border-top:1px solid #eee;
        }
        footer .links a{
            color:var(--brand); text-decoration:none; font-weight:600; margin:0 6px;
        }
        .muted{
          color:var(--muted)
        }

        .tag{display:inline-block; 
        font-size:12px; 
        background:#eef2ff; 
        color:#27348b; padding:4px 8px; 
        border-radius:999px; margin-left:6px}
    </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <header class="hero">
        <h1>Terms & Conditions</h1>
        <p>Your agreement with us when you use DivyaDarshan's services.</p>
      </header>

      <div class="content">
        <p class="lead"><strong>Effective Date:</strong> {{ date('d M Y') }} — By creating an account or using our services, you agree to these terms.</p>

        <!-- Account Registration -->
        <section class="section">
          <h2>1. Account Registration <span class="badge">Your Identity</span></h2>
          <ul class="clean">
            <li>You must provide accurate and complete information during registration, including your name and government ID details for verification.</li>
            <li>You are responsible for maintaining the confidentiality of your account password.</li>
            <li>Your account is for personal, non-commercial use only.</li>
          </ul>
        </section>

        <!-- Booking & Payment -->
        <section class="section">
          <h2>2. Darshan and Seva Bookings <span class="badge">Our Services</span></h2>
          <ul class="clean">
            <li>All Darshan and Seva bookings are subject to availability and confirmation by temple authorities. DivyaDarshan is a facilitation platform and does not guarantee slots.</li>
            <li>You must provide accurate details for all devotees in your booking. Mismatches with government IDs may result in denied entry.</li>
            <li>Payments for Sevas or any associated fees must be completed to confirm your booking. All transactions are final unless otherwise stated in a specific refund policy.</li>
          </ul>
        </section>

        <!-- User Conduct -->
        <section class="section">
          <h2>3. User Conduct <span class="badge">Expected Behaviour</span></h2>
          <ul class="clean">
            <li>You agree to use our platform lawfully and respectfully, without infringing on the rights of others.</li>
            <li>You must adhere to the specific rules and dress codes of each temple, as outlined on our "Dress Code" and "Guidelines" pages.</li>
            <li>Misuse of the platform, including fraudulent bookings or spam, will result in immediate account termination.</li>
          </ul>
        </section>

        <!-- Liability -->
        <section class="section">
          <h2>4. Limitation of Liability <span class="badge">Our Role</span></h2>
          <ul class="clean">
            <li>DivyaDarshan is not liable for any changes in temple schedules, cancellations, or other decisions made by temple management.</li>
            <li>We are not responsible for your personal safety, belongings, or travel arrangements to and from the temples.</li>
            <li>Our platform is provided "as is," and we do not guarantee it will be error-free or uninterrupted.</li>
          </ul>
        </section>

        <!-- Intellectual Property -->
        <section class="section">
          <h2>5. Intellectual Property <span class="badge">Our Content</span></h2>
          <ul class="clean">
            <li>All content on this website, including text, graphics, logos, and eBooks, is the property of DivyaDarshan and is protected by copyright law.</li>
            <li>Purchased eBooks are for your personal use only and may not be redistributed or resold.</li>
          </ul>
        </section>

        <!-- CTA -->
        <section class="cta">
          Have questions about these Terms or need assistance?
          <div>
            <a href="{{ route('home') }}">Back to Home</a>
            <a href="{{ route('complaint.form') }}">Submit a Complaint</a>
            <a href="{{ route('guidelines') }}">Read Guidelines</a>
          </div>
          <div class="muted" style="margin-top:6px;">We’re here to guide you every step of your spiritual journey.</div>
        </section>
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
</body>
</html>
