<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #2B1B12; margin: 0; padding: 0; background-color: #FAF6F0; }
        .wrapper { max-width: 620px; margin: 25px auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #EFEAE4; }
        .header { background: #2B1B12; padding: 25px; text-align: center; border-bottom: 3px solid #C9642C; }
        .content { padding: 30px; }
        .footer { background: #FAF6F0; padding: 20px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #EFEAE4; }
        .highlight-box { background: #FAF6F0; border-left: 4px solid #C9642C; padding: 15px; border-radius: 4px; margin: 20px 0; }
        .btn { display: inline-block; background: #C9642C; color: #ffffff !important; text-decoration: none; padding: 12px 24px; border-radius: 5px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h2 style="color: #ffffff; margin: 0; font-size: 22px;">Uhalisia wa Tanzania</h2>
            <p style="color: #D98B3F; margin: 5px 0 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em;">Safaris and Tours &bull; Tanzania</p>
        </div>
        <div class="content">
            <h3 style="color: #C9642C; margin-top: 0;">Jambo {{ $client->full_name }},</h3>
            <p>Thank you for choosing <strong>{{ $companyName }}</strong> for your upcoming Tanzanian safari adventure.</p>
            <p>We have carefully crafted your personalized safari quotation and proposal. Please find your official <strong>Quotation PDF</strong> attached to this email.</p>

            @if(!empty($customMessage))
                <div style="background-color: #F8F9FA; border: 1px solid #E9ECEF; padding: 14px; border-radius: 5px; margin: 20px 0;">
                    <strong style="color: #555; font-size: 12px; text-transform: uppercase;">Message from your Tour Consultant:</strong>
                    <p style="margin: 6px 0 0; font-style: italic; color: #333;">{!! nl2br(e($customMessage)) !!}</p>
                </div>
            @endif

            <div class="highlight-box">
                <table style="width: 100%; font-size: 14px;">
                    <tr>
                        <td style="color: #666; padding: 4px 0;">Booking Reference:</td>
                        <td style="font-weight: bold; padding: 4px 0;">{{ $booking->reference }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666; padding: 4px 0;">Safari Expedition:</td>
                        <td style="font-weight: bold; padding: 4px 0;">{{ $booking->title }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666; padding: 4px 0;">Travel Dates:</td>
                        <td style="font-weight: bold; padding: 4px 0;">
                            {{ $booking->start_date ? $booking->start_date->format('d M Y') : 'TBD' }} - {{ $booking->end_date ? $booking->end_date->format('d M Y') : 'TBD' }} ({{ $booking->durationDays() }} Days)
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #666; padding: 4px 0;">Party Size:</td>
                        <td style="font-weight: bold; padding: 4px 0;">{{ $booking->adults }} Adult(s) {{ $booking->children > 0 ? ', ' . $booking->children . ' Child(ren)' : '' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666; padding: 4px 0;">Net Quoted Price:</td>
                        <td style="font-weight: bold; color: #C9642C; font-size: 16px; padding: 4px 0;">${{ number_format($booking->netPriceUsd(), 2) }} USD</td>
                    </tr>
                </table>
            </div>

            <p>You can also review your itinerary online and track your trip details anytime via our private client portal:</p>
            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ $portalUrl }}" class="btn">View Your Itinerary Online &rarr;</a>
            </div>

            <p style="font-size: 13px; color: #666;">
                To accept this proposal and reserve your safari dates, a 30% deposit is requested as outlined in the attached quotation. Should you wish to customize any lodges, dates, or destinations, simply reply directly to this email.
            </p>

            <p style="margin-top: 30px;">
                Warm regards from Tanzania,<br>
                <strong>The Safari Operations Team</strong><br>
                {{ $companyName }}<br>
                <span style="font-size: 13px; color: #666;">Phone / WhatsApp: {{ $companyPhone }}</span>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $companyName }} &bull; Arusha & Dar es Salaam, Tanzania<br>
            Booking Ref: {{ $booking->reference }}
        </div>
    </div>
</body>
</html>
