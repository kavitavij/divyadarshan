<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Complaint Submitted</title>
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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
            <h2> New Complaint Received</h2>
        </div>

        <div class="body-content">
            <p>Hello Admin,</p>
            <p>A user has submitted a new complaint with the following details:</p>

            <table class="content-table">
                @foreach($formData as $key => $value)
                <tr>
                    <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                    <td>{{ nl2br(e($value)) }}</td>
                </tr>
                @endforeach
            </table>

            <p style="margin-top:20px;">Please review and take the necessary action.</p>
        </div>

        <div class="footer">
            © {{ date('Y') }} DivyaDarshan. All rights reserved.
        </div>
    </div>
</body>
</html>
