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
    *{box-sizing:border-box}
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
    .hero h1{margin:0; font-size:clamp(22px,2.4vw,34px); letter-spacing:.3px}
    .hero p{margin:8px 0 0; color:#f3e8ff}
    .content{padding:22px}
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
      background:var(--accent); color:#3b2e04; font-weight:700; border-radius:999px;
      padding:4px 10px; font-size:12px;
    }
    .grid{
      display:grid; gap:14px;
      grid-template-columns:repeat(12,1fr);
    }
    .col-6{grid-column:span 6}
    .col-4{grid-column:span 4}
    .col-12{grid-column:span 12}
    @media (max-width:820px){
      .col-6,.col-4{grid-column:span 12}
    }
    ul.clean{margin:0; padding:0 0 0 18px}
    ul.clean li{margin:8px 0}
    .tips{
      background:#fff; border:1px dashed #ddd; border-radius:12px; padding:14px;
    }
    .do-dont{display:grid; gap:14px}
    .ok, .no{
      border-radius:12px; padding:14px; color:#123; border:1px solid #e9e9ef;
    }
    .ok{background:#e9f6ec; border-color:#cfe8d4}
    .no{background:#fdecea; border-color:#f7c7c3}
    .ok h3{color:var(--ok); margin:0 0 8px}
    .no h3{color:var(--warn); margin:0 0 8px}
    .ok ul, .no ul{margin:0; padding-left:18px}
    .ok li, .no li{margin:6px 0}
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
    .muted{color:var(--muted)}
    .tag{display:inline-block; font-size:12px; background:#eef2ff; color:#27348b; padding:4px 8px; border-radius:999px; margin-left:6px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <header class="hero">
        <h1>Terms &amp; Conditions</h1>
        <p>Important legal and usage rules for using DivyaDarshan services.</p>
      </header>

      <div class="content">
        <p class="lead"><strong>Effective Date:</strong> {{ date('d M Y') }} — By accessing or using DivyaDarshan, you agree to abide by these Terms &amp; Conditions.</p>

        <!-- Service Use -->
        <section class="section">
          <h2>Service Usage <span class="badge">Your Responsibilities</span></h2>
          <ul class="clean">
            <li>You must be at least 18 years old or have parental consent to use our services.</li>
            <li>Provide accurate personal details for bookings, passes, or temple registrations.</li>
            <li>Use DivyaDarshan only for lawful purposes and in accordance with these Terms.</li>
          </ul>
        </section>

        <!-- Booking & Payment -->
        <section class="section">
          <h2>Bookings &amp; Payments <span class="badge">Commitments</span></h2>
          <ul class="clean">
            <li>All bookings are subject to temple authority approvals and availability.</li>
            <li>Payments are processed securely; DivyaDarshan does not store sensitive card details.</li>
            <li>Cancellations and refunds are governed by our Refund Policy.</li>
          </ul>
        </section>

        <!-- User Conduct -->
        <section class="section">
          <h2>User Conduct <span class="badge">Expected Behaviour</span></h2>
          <ul class="clean">
            <li>Respect temple rules, customs, and other devotees.</li>
            <li>Do not impersonate others or provide false information.</li>
            <li>No harassment, abusive language, or illegal activity is tolerated.</li>
          </ul>
        </section>

        <!-- Liability -->
        <section class="section">
          <h2>Liability <span class="badge">Our Limits</span></h2>
          <ul class="clean">
            <li>DivyaDarshan acts as a facilitator and is not responsible for decisions by temple authorities.</li>
            <li>We are not liable for delays, closures, or disruptions caused by weather, strikes, or unforeseen events.</li>
            <li>Personal belongings are the responsibility of the user.</li>
          </ul>
        </section>

        <!-- Privacy -->
        <section class="section">
          <h2>Privacy Policy <span class="badge">Your Data</span></h2>
          <ul class="clean">
            <li>Your data is collected and processed according to our <a href="#">Privacy Policy</a>.</li>
            <li>We do not sell your personal information to third parties.</li>
            <li>By using our services, you consent to the collection and use of your data as described.</li>
          </ul>
        </section>

        <!-- Termination -->
        <section class="section">
          <h2>Termination <span class="badge">Account Suspension</span></h2>
          <ul class="clean">
            <li>We reserve the right to suspend or terminate accounts violating these Terms.</li>
            <li>Any fraudulent activity will result in immediate ban and may be reported to authorities.</li>
          </ul>
        </section>

        <!-- Changes -->
        <section class="section">
          <h2>Changes to Terms <span class="badge">Updates</span></h2>
          <ul class="clean">
            <li>We may update these Terms at any time, with changes effective upon posting.</li>
            <li>Continued use of DivyaDarshan constitutes acceptance of updated terms.</li>
          </ul>
        </section>

        <!-- CTA -->
        <section class="cta">
          Have questions about these Terms or need assistance?
          <div>
            <a href="{{ route('complaint') }}">Submit a Complaint</a>
            <a href="{{ route('guidelines') }}">Read Guidelines</a>
          </div>
          <div class="muted" style="margin-top:6px;">We’re here to guide you every step of your spiritual journey.</div>
        </section>
      </div>

      <footer>
        <div class="links">
          <a href="{{ route('terms') }}">Terms</a> •
          <a href="{{ route('guidelines') }}">Guidelines</a> •
          <a href="{{ route('complaint') }}">Complaint</a>
        </div>
        <div class="muted">© {{ date('Y') }} DivyaDarshan <span class="tag">Pilgrimage Care</span></div>
      </footer>
    </div>
  </div>
</body>
</html>
