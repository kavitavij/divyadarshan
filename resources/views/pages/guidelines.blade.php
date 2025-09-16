@extends('layouts.app')

@section('title', 'Guidelines – DivyaDarshan')

@push('styles')
<style>
:root {
  --primary: #facc15;      /* yellow */
  --primary-soft: #fef9c3; /* light yellow background */
  --secondary: #0d0d0d;    /* dark bg */
  --panel: #1a1a1a;       /* card/bg panels in dark mode */
  --ink: #333;           /* default text */
  --muted: #6b7280;
  --ok: #22c55e;         /* green success */
  --warn: #ef4444;       /* red danger */
  --bg: #f8f8f8;         /* light background */
}
body {
    background: var(--bg);
    color: var(--ink);
}
.wrap {
    max-width: 1200px;
    margin: auto;
    padding: 24px 18px;
}
.card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(0,0,0,.06);
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

/* ✅ ALL STYLES BELOW ARE NOW SCOPED TO .card */
.card header.hero {
    background: linear-gradient(135deg,var(--secondary)0%,#3f3f3f80 50%,#1a1a1a00);
    color: var(--primary);
    padding: 28px 22px;
}
.card .hero h1 { margin: 0; font-size: clamp(22px, 2.4vw, 34px); }
.card .hero p { margin: 8px 0 0; color: #fef9c3; }
.card .content { padding: 22px; }
.card .lead {
    background: var(--primary-soft);
    border-left: 5px solid var(--primary);
    padding: 14px;
    border-radius: 10px;
    color: #4a4a1f;
    margin-bottom: 18px;
}
.card .section { margin: 22px 0; }
.card .section h2 {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: clamp(18px, 2vw, 22px);
    margin: 0 0 8px;
    color: var(--primary);
}
.card .section h2 .badge {
    background: var(--primary);
    color: #0d0d0d;
    font-weight: 700;
    border-radius: 999px;
    padding: 4px 10px;
    font-size: 12px;
}
.card .grid { display: grid; gap: 14px; grid-template-columns: repeat(12,1fr); }
.card .col-6 { grid-column: span 6; }
.card .col-4 { grid-column: span 4; }
.card ul.clean { margin: 0; padding-left: 18px; }
.card ul.clean li { margin: 8px 0; }
.card .tips {
    background: #fff;
    border: 1px dashed #d4d4d8;
    border-radius: 12px;
    padding: 14px;
    height: 100%;
}
.card .do-dont { display: grid; gap: 14px; }
.card .ok, .card .no {
    border-radius: 12px;
    padding: 14px;
    border: 1px solid #e5e7eb;
}
.card .ok { background: #e9f6ec; border-color: #cfe8d4; }
.card .no { background: #fdecea; border-color: #f7c7c3; }
.card .ok h3 { color: var(--ok); }
.card .no h3 { color: var(--warn); }
.card .cta {
    background: #fffbea;
    border: 1px solid #fde68a;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    margin-top: 6px;
}
.card .cta a {
    display: inline-block;
    margin: 10px 8px 0;
    padding: 10px 14px;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    border: 1px solid #facc15;
    color: #7a5e00;
    background: #fef3c7;
}
.card .cta a:hover { background: #fde68a; }
.card footer {
    text-align: center;
    padding: 16px;
    color: var(--muted);
    font-size: 14px;
    border-top: 1px solid #e5e7eb;
}
.card footer .links a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    margin: 0 6px;
}
.card .muted { color: var(--muted); }
.card .tag {
    display: inline-block;
    font-size: 12px;
    background: #eef2ff;
    color: #27348b;
    padding: 4px 8px;
    border-radius: 999px;
    margin-left: 6px;
}

@media (max-width: 820px) {
    .card .col-6, .card .col-4 { grid-column: span 12; }
}
@media (min-width: 640px) {
    .card .do-dont { grid-template-columns: 1fr 1fr; }
}
</style>
@endpush

@section('content')
<div class="wrap">
  <div class="card">
    <header class="hero">
      <h1>Guidelines for a Safe & Sacred Pilgrimage</h1>
      <p>Practical tips from DivyaDarshan to keep your yatra smooth, respectful, and fulfilling.</p>
    </header>

    <div class="content">
      <p class="lead"><strong>Note:</strong> Local temple authorities have the final say on entry, queues, and rituals. Please follow on-ground instructions at all times.</p>

      <section class="section">
        <h2>Before You Travel <span class="badge">Start Right</span></h2>
        <div class="grid">
          <div class="col-6">
            <ul class="clean">
              <li><strong>Check Bookings:</strong> Double-check your Darshan, accommodation, and travel bookings.</li>
              <li><strong>Pack Smart:</strong> Follow the <a href="{{ route('info.dress-code') }}" style="color:var(--primary);">Dress Code</a> and pack essentials.</li>
              <li><strong>Valid ID:</strong> Carry a valid government-issued photo ID.</li>
              <li><strong>Health First:</strong> Stay hydrated and consult a doctor for physically demanding yatras.</li>
            </ul>
          </div>
          <div class="col-6">
            <div class="tips">
              <strong>Health tip:</strong> Build stamina, stay hydrated, and avoid heavy meals before climbs.
            </div>
          </div>
        </div>
      </section>

      <section class="section">
        <h2>At the Temple Premises <span class="badge">Respect & Discipline</span></h2>
        <div class="grid">
          <div class="col-4"><div class="tips"><strong>Queue Etiquette:</strong> Join designated lines, avoid cutting queues.</div></div>
          <div class="col-4"><div class="tips"><strong>Silence Zones:</strong> Keep voices low near sanctum.</div></div>
          <div class="col-4"><div class="tips"><strong>Photography:</strong> Follow shrine rules for cameras.</div></div>
        </div>
        <ul class="clean" style="margin-top: 14px;">
          <li>Remove footwear in designated areas.</li>
          <li>Use simple permitted offerings; avoid plastic.</li>
          <li>Follow temple-specific dress codes.</li>
        </ul>
      </section>

      <section class="section">
        <h2>Safety & Prohibited Items <span class="badge">Stay Secure</span></h2>
        <div class="grid">
          <div class="col-6">
            <ul class="clean">
              <li>No alcohol, tobacco, or non-veg food in pilgrimage zones.</li>
              <li>Do not carry inflammables, weapons, or drones.</li>
              <li>Keep valuables minimal; use lockers.</li>
            </ul>
          </div>
          <div class="col-6">
            <ul class="clean">
              <li>Hold children’s hands in crowds.</li>
              <li>Report suspicious activity to security.</li>
              <li>Check weather advisories in hilly regions.</li>
            </ul>
          </div>
        </div>
      </section>

      <section class="section">
        <h2>Accessibility & Special Care <span class="badge">Inclusive Yatra</span></h2>
        <ul class="clean">
          <li>Arrange wheelchairs/palanquins for elderly/devotees with mobility needs.</li>
          <li>Consult doctors for chronic conditions and carry emergency meds.</li>
          <li>Parents: carry baby essentials and use baby-wearing if allowed.</li>
        </ul>
      </section>

      <section class="section">
        <h2>Digital Etiquette <span class="badge">Mindful Use</span></h2>
        <ul class="clean">
          <li>Keep phones on silent and avoid loud calls.</li>
          <li>Livestreaming inside sanctum is usually prohibited.</li>
          <li>Do not record other devotees without consent.</li>
        </ul>
      </section>

      <section class="section">
        <h2>Environment & Cleanliness <span class="badge">Yatra Seva</span></h2>
        <ul class="clean">
          <li>Use dustbins and carry back non-biodegradable waste.</li>
          <li>Prefer reusable bottles/plates.</li>
          <li>Feed animals only where permitted.</li>
        </ul>
      </section>

      <section class="section do-dont">
        <div class="ok">
          <h3>Do</h3>
          <ul class="clean">
            <li>Arrive early for darshan during peak times.</li>
            <li>Keep copies of IDs/tickets on your phone and printed.</li>
            <li>Follow volunteers’ and security instructions.</li>
          </ul>
        </div>
        <div class="no">
          <h3>Don’t</h3>
          <ul class="clean">
            <li>Don’t push in queues or block passages.</li>
            <li>Don’t bring large luggage into temple premises.</li>
            <li>Don’t engage touts or unverified agents.</li>
          </ul>
        </div>
      </section>

      <section class="cta">
        Need assistance with travel or darshan passes?
        <div>
          <a href="{{ route('home') }}">Back to Home</a>
          <a href="{{ route('complaint.form') }}">Submit a Complaint</a>
          <a href="{{ route('terms') }}">Read Terms & Conditions</a>
        </div>
        <div class="muted" style="margin-top:6px;">We’re here to help you focus on devotion—leave the logistics to us.</div>
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
@endsection
