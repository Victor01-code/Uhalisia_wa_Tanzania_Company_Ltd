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
        .detail-row { padding: 6px 0; border-bottom: 1px solid #f0ebe5; }
        .label { color: #888; font-size: 13px; text-transform: uppercase; letter-spacing: 0.03em; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h2 style="color: #ffffff; margin: 0; font-size: 22px;">Uhalisia wa Tanzania</h2>
            <p style="color: #D98B3F; margin: 5px 0 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em;">Safaris and Tours &bull; Tanzania</p>
        </div>
        <div class="content">
            <h3 style="color: #C9642C; margin-top: 0;">Jambo {{ $client->full_name }}! 🌍</h3>

            <p>Your Tanzanian safari adventure is just <strong>{{ $daysUntil }} days away!</strong> We are excited to welcome you.</p>

            <div class="highlight-box">
                <div class="detail-row">
                    <span class="label">Trip Reference:</span><br>
                    <strong>{{ $booking->reference }}</strong>
                </div>
                <div class="detail-row">
                    <span class="label">Safari Package:</span><br>
                    <strong>{{ $booking->title ?: ($booking->tour->name ?? 'Custom Safari') }}</strong>
                </div>
                <div class="detail-row">
                    <span class="label">Start Date:</span><br>
                    <strong>{{ $booking->start_date->format('l, F j, Y') }}</strong>
                </div>
                <div class="detail-row">
                    <span class="label">Duration:</span><br>
                    <strong>{{ $booking->durationDays() }} days / {{ max(1, $booking->durationDays() - 1) }} nights</strong>
                </div>
                <div class="detail-row">
                    <span class="label">Guests:</span><br>
                    <strong>{{ $booking->adults }} adult(s){{ $booking->children ? ', ' . $booking->children . ' child(ren)' : '' }}</strong>
                </div>
                @if($booking->travelers->count())
                <div class="detail-row">
                    <span class="label">Registered Travelers:</span><br>
                    <strong>{{ $booking->travelers->pluck('full_name')->join(', ') }}</strong>
                </div>
                @endif
            </div>

            <h4 style="color: #2B1B12; margin-bottom: 8px;">📋 Before You Travel – Quick Checklist</h4>
            <ul style="color: #444; padding-left: 22px;">
                <li>Confirm all passport details and travel documents are up to date</li>
                <li>Review your itinerary for pickup times and meeting points</li>
                <li>Pack comfortable safari clothing (neutral colors recommended)</li>
                <li>Bring sunscreen, binoculars, and a camera with extra memory cards</li>
                <li>Keep a copy of travel insurance details accessible</li>
            </ul>

            <p>You can review your full itinerary, documents, and trip details anytime through your personal portal:</p>

            <p style="text-align: center;">
                <a href="{{ $portalUrl }}" class="btn">View My Safari Portal</a>
            </p>

            <p style="margin-top: 25px; color: #555;">
                If you have any questions or last-minute requests, don't hesitate to reach out. We're here to ensure your experience is nothing short of extraordinary.
            </p>

            <p style="color: #888; font-size: 13px;">
                📞 <strong>{{ $companyPhone }}</strong><br>
                Karibu Tanzania — see you soon!
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $companyName }}. All Rights Reserved.</p>
            <p>Arusha, Tanzania &bull; <a href="https://uhalisiawatanzania.com" style="color: #C9642C;">uhalisiawatanzania.com</a></p>
        </div>
    </div>
</body>
</html>
