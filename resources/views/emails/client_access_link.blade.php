<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Safari Itinerary Link</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #FAF2EB; color: #2B1B12; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 8px; border: 1px solid #E5D6C7; padding: 30px; }
        .header { border-bottom: 2px solid #C9642C; padding-bottom: 20px; margin-bottom: 25px; text-align: center; }
        .header h1 { color: #C9642C; font-size: 24px; margin: 0; }
        .content { font-size: 15px; line-height: 1.6; }
        .btn { display: inline-block; background-color: #C9642C; color: #FFFFFF !important; font-weight: bold; text-decoration: none; padding: 12px 28px; border-radius: 6px; margin: 20px 0; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; border-top: 1px solid #EEE; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $companyName }}</h1>
            <p style="color: #D98B3F; margin: 5px 0 0; text-transform: uppercase; font-size: 13px; font-weight: bold;">Safaris and Tours &bull; Client Portal</p>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $client->full_name }}</strong>,</p>

            <p>Here is your private, direct access link to view your live safari itinerary and booking details for <strong>{{ $booking->title }} ({{ $booking->reference }})</strong>:</p>

            <div style="text-align: center;">
                <a href="{{ $accessUrl }}" class="btn">View My Safari Itinerary &rarr;</a>
            </div>

            <p style="font-size: 13px; color: #666;">
                Or copy and paste this link into your browser:<br>
                <a href="{{ $accessUrl }}" style="color: #C9642C; word-break: break-all;">{{ $accessUrl }}</a>
            </p>

            <p style="font-size: 13px; color: #888;">
                This link allows you to review your day-by-day lodges, assigned driver/guide contact information, and download trip documents. No password is required.
            </p>

            <p style="margin-top: 25px;">
                Warm regards from Arusha,<br>
                <strong>{{ $companyName }} Team</strong>
            </p>
        </div>

        <div class="footer">
            Uhalisia wa Tanzania Safaris and Tours &bull; Arusha & Dar es Salaam<br>
            Phone / WhatsApp: +255 784 590 722 &bull; Email: info@uhalisiawatanzania.com
        </div>
    </div>
</body>
</html>
