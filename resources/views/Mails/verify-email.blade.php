<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Verify Your Email Address</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    @media only screen and (max-width:600px){
      .container { width:100% !important; padding:16px !important; }
      .content { padding:20px !important; }
      .button { width:100% !important; box-sizing:border-box; }
      .logo { max-width:120px !important; height:auto !important; }
      .stack { display:block !important; width:100% !important; }
    }
    a[x-apple-data-detectors] { color:inherit !important; text-decoration:none !important; }
  </style>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f6; font-family:Arial, Helvetica, sans-serif; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">

  <div style="display:none; font-size:1px; color:#f4f4f6; line-height:1px; max-height:0px; max-width:0px; opacity:0; overflow:hidden;">
    Please confirm your email to activate your DivyaDarshan account.
  </div>

  <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f4f4f6; padding:24px 0;">
    <tr>
      <td align="center">
        <table role="presentation" cellpadding="0" cellspacing="0" width="600" class="container" style="background-color:#ffffff; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); overflow:hidden; width:600px;">

          <tr>
            <td style="background:linear-gradient(90deg,#9b0b0b,#6b0202); padding:20px 24px; text-align:center;">

              <img src="{{ asset('images/logo-dark.png') }}" alt="DivyaDarshan" width="140" style="display:block; margin:0 auto 8px; border:0; outline:none; text-decoration:none;" class="logo">
              <h1 style="margin:0; font-size:20px; color:#fff; font-weight:700; letter-spacing:0.2px;">Welcome to DivyaDarshan</h1>
            </td>
          </tr>

          <tr>
            <td class="content" style="padding:32px 40px; color:#444444;">
              <p style="margin:0 0 16px; font-size:16px;">Hi,</p>

              <p style="margin:0 0 18px; font-size:16px; line-height:1.6; color:#555;">
                Thanks for registering with DivyaDarshan. To complete your registration and activate your account, please confirm your email address by clicking the button below.
              </p>

              <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin:22px 0;">
                <tr>
                  <td align="center">
                    <a href="{{ $verificationUrl }}" class="button" target="_blank" rel="noopener"
                       style="display:inline-block; padding:14px 22px; background-color:#d32b2b; color:#ffffff; text-decoration:none; font-weight:600; border-radius:6px; font-size:16px;">
                      Verify Email Address
                    </a>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 12px; font-size:14px; color:#666;">
                If the button doesn't work, copy and paste the following link into your browser:
              </p>
              <p style="word-break:break-all; font-size:13px; color:#0b5394; margin:0 0 18px;">
                <a href="{{ $verificationUrl }}" target="_blank" rel="noopener" style="color:#0b5394; text-decoration:underline;">{{ $verificationUrl }}</a>
              </p>

              <hr style="border:none; border-top:1px solid #eee; margin:22px 0;">

              <p style="margin:0 0 8px; font-size:14px; color:#666;">
                If you did not create this account, you can safely ignore this message—no action is required.
              </p>

              <p style="margin:18px 0 0; font-size:14px; color:#444;">Thank you,<br><strong>The DivyaDarshan Team</strong></p>
            </td>
          </tr>

          <tr>
            <td style="background-color:#fafafa; padding:18px 24px; text-align:center; font-size:12px; color:#999;">
              <div style="max-width:520px; margin:0 auto;">
                <p style="margin:0 0 6px;">&copy; {{ date('Y') }} DivyaDarshan. All rights reserved.</p>
                <p style="margin:0; line-height:1.4;">
                  <span style="color:#777;">Need help?</span>
                  <a href="mailto:support@divyadarshan.com" style="color:#0b5394; text-decoration:underline;">support@divyadarshan.example</a>
                </p>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
