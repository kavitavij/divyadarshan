<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Contact Form Submission</title>
<style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        background-color: #f4f6f8;
        margin: 0;
        padding: 20px;
        color: #333;
    }
    .container {
        max-width: 650px;
        margin: auto;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .header {
        background: #2c3e50;
        color: #fff;
        padding: 20px;
        text-align: center;
    }
    .header h2 {
        margin: 0;
        font-size: 22px;
    }
    .body-content {
        padding: 20px;
    }
    p {
        margin-bottom: 20px;
        font-size: 15px;
        color: #555;
    }
    .content-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    .content-table th,
    .content-table td {
        padding: 12px 15px;
        border: 1px solid #eaeaea;
        text-align: left;
    }
    .content-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        width: 180px;
        color: #444;
    }
    .content-table td {
        background-color: #fff;
    }
    .message-block {
        padding: 15px;
        background-color: #fcfcfc;
        border: 1px solid #eaeaea;
        border-radius: 6px;
        font-size: 14px;
        line-height: 1.6;
        white-space: pre-wrap;
        margin-top: 10px;
    }
    .footer {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        font-size: 13px;
        color: #888;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2> New Contact Form Submission</h2>
        </div>

        <div class="body-content">
            <p>You have received a new message from your website’s contact form. The details are as follows:</p>

            <table class="content-table">
                @if(isset($formData['name']))
                <tr>
                    <th>Name</th>
                    <td>{{ e($formData['name']) }}</td>
                </tr>
                @endif
                @if(isset($formData['email']))
                <tr>
                    <th>Email</th>
                    <td>{{ e($formData['email']) }}</td>
                </tr>
                @endif
                @if(isset($formData['subject']))
                <tr>
                    <th>Subject</th>
                    <td>{{ e($formData['subject']) }}</td>
                </tr>
                @endif
            </table>

            @if(isset($formData['message']))
                <p style="margin-top: 20px; font-weight: bold;">Message:</p>
                <div class="message-block">
                    {{ e($formData['message']) }}
                </div>
            @endif
        </div>

        <div class="footer">
            © {{ date('Y') }} DivyaDarshan. All rights reserved.
        </div>
    </div>
</body>
</html>
