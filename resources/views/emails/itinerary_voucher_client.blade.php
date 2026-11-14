<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #2B1B12; margin: 0; padding: 0; background-color: #FAF6F0; }
        .wrapper { max-width: 620px; margin: 25px auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #EFEAE4; }
        .header { background: #2B1B12; padding: 25px; text-align: center; border-bottom: 3px solid #5B6B3D; }
        .content { padding: 30px; }
        .footer { background: #FAF6F0; padding: 20px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #EFEAE4; }
        .highlight-box { background: #F4F6F0; border-left: 4px solid #5B6B3D; padding: 15px; border-radius: 4px; margin: 20px 0; }
        .btn { display: inline-block; background: #5B6B3D; color: #ffffff !important; text-decoration: none; padding: 12px 24px; border-radius: 5px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h2 style="color: #ffffff; margin: 0; font-size: 22px;">Uhalisia wa Tanzania</h2>
            <p style="color: #D98B3F; margin: 5px 0 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em;">Safaris and Tours &bull; Tanzania</p>
        </div>
        <div class="content">
            <h3 style="color: #5B6B3D; margin-top: 0;">Karibu Tanzania, {{ $client->full_name }}!</h3>
            <p>We are delighted to confirm that your Tanzanian safari expedition is officially secured! Attached to this email is your <strong>Official Itinerary Voucher & Packing Guide PDF</strong>.</p>

            @if(!empty($customMessage))
                <div style="background-color: #F8F9FA; border: 1px solid #E9ECEF; padding: 14px; border-radius: 5px; margin: 20px 0;">
                    <strong style="color: #555; font-size: 12px; text-transform: uppercase;">Message from your Tour Coordinator:</strong>
                    <p style="margin: 6px 0 0; font-style: italic; color: #333;">{!! nl2br(e($customMessage)) !!}</p>
                </div>
            @endif

            <div class="highlight-box">
                <table style="width: 100%; font-size: 14px;">
                    <tr>
                        <td style="color: #666; padding: 4px 0;">Voucher Reference:</td>
                        <td style="font-weight: bold; padding: 4px 0;">{{ $booking->reference }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666; padding: 4px 0;">Safari Title:</td>
                        <td style="font-weight: bold; padding: 4px 0;">{{ $booking->title }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666; padding: 4px 0;">Confirmed Dates:</td>
                        <td style="font-weight: bold; color: #5B6B3D; padding: 4px 0;">
                            {{ $booking->start_date ? $booking->start_date->format('d M Y') : 'TBD' }} - {{ $booking->end_date ? $booking->end_date->format('d M Y') : 'TBD' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #666; padding: 4px 0;">Duration:</td>
                        <td style="font-weight: bold; padding: 4px 0;">{{ $booking->durationDays() }} Days &bull; {{ $booking->adults }} Adults {{ $booking->children > 0 ? ', ' . $booking->children . ' Children' : '' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #666; padding: 4px 0;">24/7 Safari Hotline:</td>
                        <td style="font-weight: bold; color: #C9642C; padding: 4px 0;">{{ $companyPhone }}</td>
                    </tr>
                </table>
            </div>

            <p>Your private client portal is active. You can review your daily schedule, driver information, and emergency contacts directly on your phone throughout your journey:</p>
            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ $portalUrl }}" class="btn">Access Your Digital Travel Voucher &rarr;</a>
            </div>

            <p style="font-size: 13px; color: #666;">
                Please review the packing checklist and health advisory included in the attached document. Our safari coordinator will greet you upon arrival at Kilimanjaro (JRO) / Arusha (ARK) airport.
            </p>

            <p style="margin-top: 30px;">
                Tutaonana karibuni (See you soon in Tanzania),<br>
                <strong>The Safari Operations Team</strong><br>
                {{ $companyName }}<br>
                <span style="font-size: 13px; color: #666;">Office: Arusha, Tanzania &bull; Phone: {{ $companyPhone }}</span>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $companyName }} &bull; Tanzania Tourism Board Registered Operator<br>
            Booking Ref: {{ $booking->reference }}
        </div>
    </div>
</body>
</html>
