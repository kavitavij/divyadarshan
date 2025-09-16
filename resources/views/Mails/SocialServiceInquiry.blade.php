<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Social Service Inquiry</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h2 { color: #0056b3; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f9f9f9; width: 30%; }
        .footer { margin-top: 20px; font-size: 0.9em; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Social Service Inquiry Received</h2>
        <p>A new inquiry has been submitted through the DivyaDarshan website. Please find the details below:</p>

        <table>
            <tr>
                <th>Temple:</th>
                <td>{{ $temple->name }}</td>
            </tr>
            <tr>
                <th>Service of Interest:</th>
                <td>{{ ucfirst(str_replace('_', ' ', $inquiryData['service_type'])) }}</td>
            </tr>
            <tr>
                <th>Inquirer's Name:</th>
                <td>{{ $inquiryData['name'] }}</td>
            </tr>
            <tr>
                <th>Inquirer's Email:</th>
                <td><a href="mailto:{{ $inquiryData['email'] }}">{{ $inquiryData['email'] }}</a></td>
            </tr>
            <tr>
                <th>Inquirer's Phone:</th>
                <td><a href="tel:{{ $inquiryData['phone'] }}">{{ $inquiryData['phone'] }}</a></td>
            </tr>
            <tr>
                <th>Message:</th>
                <td>{{ $inquiryData['message'] ?? 'No message provided.' }}</td>
            </tr>
        </table>

        <p>Please contact this individual at your earliest convenience to discuss their interest in participating in this social service.</p>
    </div>
    <div class="footer">
        <p>This is an automated notification from DivyaDarshan.</p>
    </div>
</body>
</html>
