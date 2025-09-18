@extends('layouts.app')

@section('title', 'Terms & Conditions – DivyaDarshan')

@section('content')
<style>
:root{
  --primary:#facc15;
  --primary-soft:#fef9c3;
  --secondary:#0d0d0d;
  --ink:#333;
  --muted:#6b7280;
  --ok:#22c55e;
  --warn:#ef4444;
  --bg:#f8f8f8;
}
body{background:var(--bg);color:var(--ink);line-height:1.6;}
.wrap{
    max-width:1200px;
    margin:auto;
    padding:24px 18px;
}
.card{
  background:#fff;border-radius:14px;
  box-shadow:0 8px 24px rgba(0,0,0,.06);
  overflow:hidden;border:1px solid #e5e7eb;
}
header.hero{
  background:linear-gradient(135deg,var(--secondary)0%,#3f3f3f80 55%,#1a1a1a00 100%);
  color:var(--primary);padding:28px 22px;
}
.hero h1{margin:0;font-size:clamp(22px,2.4vw,34px);}
.hero p{margin:8px 0 0;color:#fef9c3;}
.content{padding:22px;}
.lead{
  background:var(--primary-soft);
  border-left:5px solid var(--primary);
  padding:14px;border-radius:10px;
  color:#4a4a1f;margin:0 0 18px;
}
.section{margin:22px 0;}
.section h2{
  display:flex;align-items:center;gap:10px;
  font-size:clamp(18px,2vw,22px);
  margin:0 0 8px;color:var(--primary);
}
.section h2 .badge{
  background:var(--primary);
  color:#0d0d0d;font-weight:700;
  border-radius:999px;padding:4px 10px;font-size:12px;
}
ul.clean{margin:0;padding-left:18px;}
ul.clean li{margin:8px 0;}
.cta{
  background:#fffbea;border:1px solid #fde68a;
  border-radius:12px;padding:16px;text-align:center;
  margin-top:6px;
}
.cta a{
  display:inline-block;margin:10px 8px 0;
  padding:10px 14px;text-decoration:none;
  border-radius:10px;font-weight:600;
  border:1px solid #facc15;color:#7a5e00;
  background:#fef3c7;transition:background .15s;
}
.cta a:hover{background:#fde68a;}
footer{
  padding:16px;text-align:center;color:var(--muted);
  font-size:14px;border-top:1px solid #e5e7eb;
}
footer .links a{
  color:var(--primary);text-decoration:none;
  font-weight:600;margin:0 6px;
}
.muted{color:var(--muted);}
.tag{
  display:inline-block;font-size:12px;
  background:#eef2ff;color:#27348b;
  padding:4px 8px;border-radius:999px;margin-left:6px;
}
</style>

<div class="wrap">
  <div class="card">
    <header class="hero">
      <h1>Terms & Conditions</h1>
      <p>Your agreement with us when you use DivyaDarshan's services.</p>
    </header>

    <div class="content">
      <p class="lead">
        <strong>Effective Date:</strong> {{ date('d M Y') }} — By creating an account or using our services, you agree to these terms.
      </p>

      <section class="section">
        <h2>1. Account Registration <span class="badge">Your Identity</span></h2>
        <ul class="clean">
          {{-- <li>You must provide accurate and complete information during registration, including your name and government ID details for verification.</li>
          <li>You are responsible for maintaining the confidentiality of your account password.</li>
          <li>Your account is for personal, non-commercial use only.</li>
        </ul> --}}
      </section>

      <section class="section">
        <h2>2. Darshan and Seva Bookings <span class="badge">Our Services</span></h2>
        {{-- <ul class="clean">
          <li>All Darshan and Seva bookings are subject to availability and confirmation by temple authorities. DivyaDarshan is a facilitation platform and does not guarantee slots.</li>
          <li>You must provide accurate details for all devotees in your booking. Mismatches with government IDs may result in denied entry.</li>
          <li>Payments for Sevas or any associated fees must be completed to confirm your booking. All transactions are final unless otherwise stated in a specific refund policy.</li>
        </ul> --}}
      </section>

      <section class="section">
        <h2>3. User Conduct <span class="badge">Expected Behaviour</span></h2>
        {{-- <ul class="clean">
          <li>You agree to use our platform lawfully and respectfully, without infringing on the rights of others.</li>
          <li>You must adhere to the specific rules and dress codes of each temple, as outlined on our "Dress Code" and "Guidelines" pages.</li>
          <li>Misuse of the platform, including fraudulent bookings or spam, will result in immediate account termination.</li>
        </ul> --}}
      </section>

      <section class="section">
        <h2>4. Limitation of Liability <span class="badge">Our Role</span></h2>
        {{-- <ul class="clean">
          <li>DivyaDarshan is not liable for any changes in temple schedules, cancellations, or other decisions made by temple management.</li>
          <li>We are not responsible for your personal safety, belongings, or travel arrangements to and from the temples.</li>
          <li>Our platform is provided "as is," and we do not guarantee it will be error-free or uninterrupted.</li>
        </ul> --}}
      </section>

      <section class="section">
        <h2>5. Intellectual Property <span class="badge">Our Content</span></h2>
        {{-- <ul class="clean">
          <li>All content on this website, including text, graphics, logos, and eBooks, is the property of DivyaDarshan and is protected by copyright law.</li>
          <li>Purchased eBooks are for your personal use only and may not be redistributed or resold.</li>
        </ul> --}}
      </section>

      <section class="cta">
        Have questions about these Terms or need assistance?
        <div>
          <a href="{{ route('home') }}">Back to Home</a>
          <a href="{{ route('complaint.form') }}">Submit a Complaint</a>
          <a href="{{ route('guidelines') }}">Read Guidelines</a>
        </div>
        <div class="muted" style="margin-top:6px;">
          We’re here to guide you every step of your spiritual journey.
        </div>
      </section>
    </div>

    <footer>
      <div class="links">
        <a href="{{ route('terms') }}">Terms</a> •
        <a href="{{ route('guidelines') }}">Guidelines</a> •
        <a href="{{ route('complaint.form') }}">Complaint</a>
      </div>
      <div class="muted">
        © {{ date('Y') }} DivyaDarshan <span class="tag">Pilgrimage Care</span>
      </div>
    </footer>
  </div>
</div>
@endsection
