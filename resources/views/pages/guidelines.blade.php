<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Guidelines – DivyaDarshan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    :root{
      --brand:#4a148c;        /* deep spiritual purple */
      --brand-soft:#ede7f6;
      --accent:#ffb300;       /* warm saffron */
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

    /* Sections */
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

    /* Lists */
    ul.clean{margin:0; padding:0 0 0 18px}
    ul.clean li{margin:8px 0}
    .tips{
      background:#fff; border:1px dashed #ddd; border-radius:12px; padding:14px;
    }

    /* Do / Don’t cards */
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

    /* CTA + footer */
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
        <h1>Guidelines for a Safe & Sacred Pilgrimage</h1>
        <p>Practical tips from DivyaDarshan to keep your yatra smooth, respectful, and fulfilling.</p>
      </header>

      <div class="content">
        <p class="lead"><strong>Note:</strong> Local temple authorities have the final say on entry, queues, and rituals. Please follow on-ground instructions at all times.</p>

        <!-- Before You Travel -->
        <section class="section">
          <h2>Before You Travel <span class="badge">Start Right</span></h2>
          <div class="grid">
            <div class="col-6">
              <ul class="clean">
                <li>Carry a valid government ID (Aadhaar/Passport/Driving License) for all check-ins.</li>
                <li>Register for any required yatra/darshan passes as advised by the temple.</li>
                <li>Pack modest clothing, comfortable footwear, reusable water bottle, and light snacks.</li>
                <li>Keep medicines, basic first-aid, and any prescriptions in your cabin bag.</li>
              </ul>
            </div>
            <div class="col-6">
              <div class="tips">
                <strong>Health tip:</strong> If trekking (e.g., Vaishno Devi), build stamina, stay hydrated, and avoid heavy meals just before the climb.
              </div>
            </div>
          </div>
        </section>

        <!-- At the Temple -->
        <section class="section">
          <h2>At the Temple Premises <span class="badge">Respect & Discipline</span></h2>
          <div class="grid">
            <div class="col-4">
              <div class="tips">
                <strong>Queue Etiquette:</strong> Join designated lines, avoid cutting queues, keep calm during peak hours.
              </div>
            </div>
            <div class="col-4">
              <div class="tips">
                <strong>Silence Zones:</strong> Keep voices low near sanctum; switch phones to silent/vibrate.
              </div>
            </div>
            <div class="col-4">
              <div class="tips">
                <strong>Photography:</strong> Many shrines restrict cameras inside—always follow posted rules.
              </div>
            </div>
          </div>
          <ul class="clean">
            <li>Remove footwear in designated areas; use token systems where provided.</li>
            <li>Offerings should be simple and permitted; avoid plastic packaging.</li>
            <li>Follow dress codes where applicable (e.g., full-length, shoulders covered).</li>
          </ul>
        </section>

        <!-- Safety & Prohibited -->
        <section class="section">
          <h2>Safety & Prohibited Items <span class="badge">Stay Secure</span></h2>
          <div class="grid">
            <div class="col-6">
              <ul class="clean">
                <li>No alcohol, tobacco, drugs, or non-vegetarian food in pilgrimage zones.</li>
                <li>Do not carry inflammables, weapons, drones, or laser pointers.</li>
                <li>Keep valuables minimal; use lockers where available.</li>
              </ul>
            </div>
            <div class="col-6">
              <ul class="clean">
                <li>In crowds, hold children’s hands; set a meeting point in case someone gets separated.</li>
                <li>Report unattended objects or suspicious activity to security immediately.</li>
                <li>In hilly regions, check weather and route advisories before setting out.</li>
              </ul>
            </div>
          </div>
        </section>

        <!-- Accessibility & Special Care -->
        <section class="section">
          <h2>Accessibility & Special Care <span class="badge">Inclusive Yatra</span></h2>
          <ul class="clean">
            <li>Elderly/devotees with mobility needs: pre-arrange wheelchairs/palanquins where available.</li>
            <li>Asthma/heart patients: consult your doctor; carry inhalers and emergency meds.</li>
            <li>Parents with infants: carry essentials; use baby-wearing for queues where allowed.</li>
          </ul>
        </section>

        <!-- Digital Etiquette -->
        <section class="section">
          <h2>Digital Etiquette <span class="badge">Mindful Use</span></h2>
          <ul class="clean">
            <li>Keep phones on silent; avoid loud calls and speaker mode.</li>
            <li>Livestreaming/Reels inside sanctum is usually prohibited—respect the rules.</li>
            <li>Do not record other devotees without consent.</li>
          </ul>
        </section>

        <!-- Environment -->
        <section class="section">
          <h2>Environment & Cleanliness <span class="badge">Yatra Seva</span></h2>
          <ul class="clean">
            <li>Use dustbins; carry back non-biodegradable waste if bins are unavailable.</li>
            <li>Prefer reusable bottles/plates over single-use plastic.</li>
            <li>Feed animals only where permitted; avoid littering prasad/leaves on paths.</li>
          </ul>
        </section>

        <!-- Do / Don't -->
        <section class="section">
          <div class="do-dont">
            <div class="ok">
              <h3>Do</h3>
              <ul>
                <li>Arrive early for darshan during peak seasons (festivals, weekends).</li>
                <li>Keep copies of IDs/tickets on your phone and one printed backup.</li>
                <li>Follow volunteers’ and security staff instructions patiently.</li>
              </ul>
            </div>
            <div class="no">
              <h3>Don’t</h3>
              <ul>
                <li>Don’t push in queues or block passages for photos.</li>
                <li>Don’t bring large luggage into temple premises.</li>
                <li>Don’t engage touts or unverified agents for passes or shortcuts.</li>
              </ul>
            </div>
          </div>
        </section>

        <!-- Help / CTA -->
        <section class="cta">
          Need assistance with travel, accommodation, or darshan passes?
          <div>
            <a href="{{ route('complaint') }}">Submit a Complaint</a>
            <a href="{{ route('terms') }}">Read Terms &amp; Conditions</a>
          </div>
          <div class="muted" style="margin-top:6px;">We’re here to help you focus on devotion—leave the logistics to us.</div>
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
