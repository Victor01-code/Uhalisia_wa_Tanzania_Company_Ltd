<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Karibu Tanzania - We received your inquiry</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #FAF2EB; color: #2B1B12; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 8px; border: 1px solid #E5D6C7; padding: 30px; }
        .header { border-bottom: 2px solid #C9642C; padding-bottom: 20px; margin-bottom: 25px; text-align: center; }
        .header h1 { color: #C9642C; font-size: 24px; margin: 0; }
        .tagline { color: #D98B3F; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 5px; font-weight: bold; }
        .content { font-size: 15px; line-height: 1.6; }
        .summary-box { background: #FCF8F5; border-radius: 6px; padding: 15px 20px; margin: 20px 0; border: 1px solid #F3DCC5; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; border-top: 1px solid #EEE; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $companyName }}</h1>
            <div class="tagline">Safaris and Tours &bull; Tanzania</div>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $lead->name }}</strong>,</p>

            <p><em>Jambo na Karibu Tanzania!</em> (Hello and welcome to Tanzania!)</p>

            <p>Thank you for contacting <strong>{{ $companyName }}</strong>. We have successfully received your inquiry for <strong>{{ $lead->safari_interest ?? 'your Tanzania adventure' }}</strong>.</p>

            <div class="summary-box">
                <h4 style="margin: 0 0 10px 0; color: #C9642C;">Inquiry Summary</h4>
                <p style="margin: 4px 0;"><strong>Name:</strong> {{ $lead->name }}</p>
                <p style="margin: 4px 0;"><strong>Country:</strong> {{ $lead->country }}</p>
                <p style="margin: 4px 0;"><strong>Tour Interest:</strong> {{ $lead->safari_interest }}</p>
            </div>

            <p>Our safari and tour specialists in Arusha are reviewing your preferred dates and requirements. One of our senior trip planners will get back to you within 24 hours with a comprehensive customized itinerary and pricing in USD.</p>

            <p>If you have urgent questions or would like to chat directly, you can reach us on WhatsApp or call us at <strong>{{ $companyPhone }}</strong>.</p>

            <p style="margin-top: 25px;">
                Warm regards,<br>
                <strong>The Safari Team</strong><br>
                {{ $companyName }}<br>
                Arusha & Dar es Salaam, Tanzania
            </p>
        </div>

        <div class="footer">
            Website: <a href="https://www.uhalisiawatanzania.com" style="color: #C9642C;">www.uhalisiawatanzania.com</a> | Email: {{ $companyEmail }} | Phone: {{ $companyPhone }}
        </div>
    </div>
</body>
</html>
