<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Quote - {{ $booking->reference }}</title>
    <style>
        @page {
            margin: 1.2cm 1.2cm 1.5cm 1.2cm;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2B1B12;
            font-size: 11pt;
            line-height: 1.45;
        }

        body {
            margin: 0;
            padding: 0;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #C9642C;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-logo {
            width: 180px;
            vertical-align: middle;
        }

        .header-company {
            text-align: right;
            vertical-align: middle;
            font-size: 9pt;
            color: #555;
            line-height: 1.35;
        }

        .company-name {
            font-size: 14pt;
            font-weight: bold;
            color: #2B1B12;
            margin-bottom: 3px;
        }

        .company-tagline {
            color: #C9642C;
            font-weight: bold;
            font-size: 9.5pt;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        /* Document Title Banner */
        .title-banner {
            background-color: #FAF6F0;
            border-left: 4px solid #C9642C;
            padding: 10px 15px;
            margin-bottom: 20px;
        }

        .quote-title {
            font-size: 16pt;
            font-weight: bold;
            color: #2B1B12;
            margin: 0 0 4px 0;
        }

        .quote-ref-date {
            font-size: 10pt;
            color: #666;
        }

        /* Two Column Info Section */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-box {
            background-color: #FAFAFA;
            border: 1px solid #EFEAE4;
            padding: 12px;
            border-radius: 4px;
            vertical-align: top;
            width: 48%;
            font-size: 9.5pt;
        }

        .info-title {
            font-weight: bold;
            color: #C9642C;
            border-bottom: 1px solid #E5DFD7;
            padding-bottom: 4px;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-size: 8.5pt;
            letter-spacing: 0.05em;
        }

        .info-row {
            margin-bottom: 4px;
        }

        .info-label {
            color: #777;
            width: 120px;
            display: inline-block;
        }

        .info-val {
            font-weight: 600;
            color: #2B1B12;
        }

        /* Section Headings */
        .section-heading {
            font-size: 12pt;
            font-weight: bold;
            color: #2B1B12;
            border-bottom: 1.5px solid #D98B3F;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* Tables */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9.5pt;
        }

        .content-table th {
            background-color: #2B1B12;
            color: #FFFFFF;
            text-align: left;
            padding: 7px 10px;
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .content-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #EFEAE4;
            vertical-align: top;
        }

        .content-table tr:nth-child(even) td {
            background-color: #FDFBF7;
        }

        /* Pricing Table */
        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }

        .pricing-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #EFEAE4;
        }

        .pricing-total-row td {
            background-color: #FAF6F0;
            font-weight: bold;
            font-size: 12pt;
            color: #2B1B12;
            border-top: 2px solid #C9642C;
            border-bottom: 2px solid #C9642C;
        }

        .pricing-highlight {
            color: #C9642C;
            font-weight: bold;
        }

        /* Bank Details Box */
        .bank-box {
            background-color: #FAF6F0;
            border: 1px dashed #D98B3F;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 9pt;
            margin-bottom: 20px;
        }

        .bank-box h4 {
            margin: 0 0 8px 0;
            color: #2B1B12;
            font-size: 10pt;
            font-weight: bold;
        }

        /* Inclusions / Exclusions */
        .list-box {
            font-size: 9pt;
            line-height: 1.5;
            padding-left: 18px;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .list-box li {
            margin-bottom: 3px;
        }

        /* Footer */
        .footer-table {
            width: 100%;
            border-top: 1px solid #E5DFD7;
            padding-top: 10px;
            font-size: 8pt;
            color: #888;
            margin-top: 30px;
        }

        .page-num:before {
            content: "Page " counter(page);
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="header-logo">
                @if(!empty($logoBase64))
                    <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Uhalisia wa Tanzania Logo" style="height: 65px;">
                @else
                    <h2 style="color: #C9642C; margin: 0;">Uhalisia wa Tanzania</h2>
                @endif
            </td>
            <td class="header-company">
                <div class="company-name">{{ $companyName ?? 'Uhalisia wa Tanzania Company Ltd' }}</div>
                <div class="company-tagline">Safaris and Tours &bull; Tanzania</div>
                <div>Arusha & Dar es Salaam, Tanzania</div>
                <div>Email: {{ $companyEmail ?? 'info@uhalisiawatanzania.com' }} &bull; Web: www.uhalisiawatanzania.com</div>
                <div>24/7 Operations / WhatsApp: {{ $companyPhone ?? '+255 784 590 722' }}</div>
            </td>
        </tr>
    </table>

    <!-- Title Banner -->
    <div class="title-banner">
        <table style="width: 100%;">
            <tr>
                <td>
                    <h1 class="quote-title">OFFICIAL SAFARI PROPOSAL & QUOTATION</h1>
                    <div class="quote-ref-date">
                        Booking Reference: <strong style="color: #2B1B12;">{{ $booking->reference }}</strong>
                        &bull; Issued: <strong>{{ now()->format('d M Y') }}</strong>
                        &bull; Validity: <strong>14 Days</strong>
                    </div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <span style="background-color: #C9642C; color: #FFF; padding: 6px 12px; border-radius: 3px; font-weight: bold; font-size: 10pt; text-transform: uppercase;">
                        Status: {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Client & Safari Details -->
    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="info-box">
                <div class="info-title">Guest / Party Information</div>
                <div class="info-row"><span class="info-label">Primary Guest:</span> <span class="info-val">{{ $booking->client->full_name }}</span></div>
                <div class="info-row"><span class="info-label">Email Address:</span> <span class="info-val">{{ $booking->client->email }}</span></div>
                @if($booking->client->phone)
                    <div class="info-row"><span class="info-label">Phone / Mobile:</span> <span class="info-val">{{ $booking->client->phone }}</span></div>
                @endif
                <div class="info-row"><span class="info-label">Country:</span> <span class="info-val">{{ $booking->client->country ?? 'Not Specified' }}</span></div>
                <div class="info-row"><span class="info-label">Party Size:</span> <span class="info-val">{{ $booking->adults }} Adult(s) {{ $booking->children > 0 ? ', ' . $booking->children . ' Child(ren)' : '' }}</span></div>
            </td>
            <td style="width: 4%;"></td>
            <td class="info-box">
                <div class="info-title">Trip Overview</div>
                <div class="info-row"><span class="info-label">Safari Title:</span> <span class="info-val">{{ $booking->title }}</span></div>
                <div class="info-row"><span class="info-label">Start Date:</span> <span class="info-val">{{ $booking->start_date ? $booking->start_date->format('d M Y') : 'TBD' }}</span></div>
                <div class="info-row"><span class="info-label">End Date:</span> <span class="info-val">{{ $booking->end_date ? $booking->end_date->format('d M Y') : 'TBD' }}</span></div>
                <div class="info-row"><span class="info-label">Total Duration:</span> <span class="info-val">{{ $booking->durationDays() }} Days ({{ max(1, $booking->durationDays() - 1) }} Nights)</span></div>
                <div class="info-row"><span class="info-label">Vehicle Type:</span> <span class="info-val">4x4 Safari Land Cruiser (Pop-up Roof)</span></div>
            </td>
        </tr>
    </table>

    <!-- Itinerary Blueprint Table -->
    <div class="section-heading">Proposed Safari Schedule ({{ $booking->durationDays() }} Days)</div>

    <table class="content-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 12%;">Day</th>
                <th style="width: 25%;">Destination</th>
                <th style="width: 38%;">Highlights & Safari Activities</th>
                <th style="width: 25%;">Lodge & Meal Plan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($booking->bookingDays as $day)
                <tr>
                    <td>
                        <strong>Day {{ $day->day_number }}</strong>
                        @if($day->date)
                            <div style="font-size: 8pt; color: #888;">{{ $day->date->format('d M') }}</div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $day->destination ?? $day->title }}</strong>
                    </td>
                    <td>
                        <strong style="color: #2B1B12;">{{ $day->title }}</strong>
                        @if($day->activities)
                            <div style="font-size: 8.5pt; color: #555; margin-top: 2px;">{{ $day->activities }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="color: #C9642C; font-weight: bold;">{{ $day->accommodation ?: 'Quality Safari Camp / Lodge' }}</div>
                        <div style="font-size: 8.5pt; color: #666;">{{ $day->meals ?: 'Breakfast, Lunch, Dinner' }}</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #888; padding: 15px;">Itinerary details customized upon confirmation.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- Financial Quote Breakdown -->
    <div class="section-heading">Commercial Investment & Quotation</div>

    <table class="pricing-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 70%;">
                <strong>Total Safari Expedition Package</strong>
                <div style="font-size: 8.5pt; color: #666;">Comprehensive private safari for {{ $booking->adults }} Adult(s) {{ $booking->children > 0 ? 'and ' . $booking->children . ' Child(ren)' : '' }}</div>
            </td>
            <td style="width: 30%; text-align: right; font-weight: bold;">
                ${{ number_format($booking->total_price_usd, 2) }} USD
            </td>
        </tr>
        @if($booking->discount_usd > 0)
            <tr>
                <td>
                    <strong style="color: #28a745;">Special Courtesy Discount</strong>
                </td>
                <td style="text-align: right; color: #28a745; font-weight: bold;">
                    - ${{ number_format($booking->discount_usd, 2) }} USD
                </td>
            </tr>
        @endif
        <tr class="pricing-total-row">
            <td>
                <strong>NET PAYABLE TOTAL (USD):</strong>
            </td>
            <td style="text-align: right;" class="pricing-highlight">
                ${{ number_format($booking->netPriceUsd(), 2) }} USD
            </td>
        </tr>
        <tr>
            <td style="padding-top: 12px;">
                <strong>Deposit Required to Confirm Booking (30%):</strong>
                <div style="font-size: 8.5pt; color: #666;">Secures national park permits, concession fees, and lodge reservations</div>
            </td>
            <td style="padding-top: 12px; text-align: right; font-weight: bold; color: #C9642C; font-size: 11pt;">
                ${{ number_format($booking->netPriceUsd() * 0.30, 2) }} USD
            </td>
        </tr>
        <tr>
            <td>
                <strong>Remaining Balance (70%):</strong>
                <div style="font-size: 8.5pt; color: #666;">Payable 30 days prior to safari departure or upon arrival in Arusha</div>
            </td>
            <td style="text-align: right; font-weight: bold; color: #555;">
                ${{ number_format($booking->netPriceUsd() * 0.70, 2) }} USD
            </td>
        </tr>
    </table>

    <!-- Inclusions & Exclusions -->
    <table style="width: 100%;" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 48%; vertical-align: top;">
                <div class="section-heading" style="font-size: 10.5pt; color: #2e7d32;">Included in Safari Price:</div>
                <ul class="list-box">
                    <li>Exclusive private 4x4 Safari Land Cruiser with pop-up photographic viewing roof</li>
                    <li>Professional, certified English-speaking Tanzanian Safari Guide / Driver</li>
                    <li>All Tanzania National Park conservation & concession entry fees</li>
                    <li>Full-board accommodation as outlined in the itinerary</li>
                    <li>Unlimited game drives during park operational hours</li>
                    <li>Bottled purified mineral water throughout safari travel</li>
                    <li>Government taxes, VAT, and tourism development levies</li>
                    <li>AMREF Flying Doctors emergency medical evacuation cover</li>
                </ul>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; vertical-align: top;">
                <div class="section-heading" style="font-size: 10.5pt; color: #c62828;">Not Included:</div>
                <ul class="list-box">
                    <li>International & domestic flight tickets to/from Tanzania</li>
                    <li>Tanzania entry tourist visa (available online at $50 / $100 for US citizens)</li>
                    <li>Personal travel, baggage, and medical insurance (mandatory)</li>
                    <li>Alcoholic beverages & premium soft drinks at lodges</li>
                    <li>Optional excursions (e.g., Serengeti Hot Air Balloon Safari)</li>
                    <li>Gratuities / tips for safari driver-guide and lodge staff ($15 - $20 per day recommended)</li>
                    <li>Laundry services and personal telephone / internet expenses</li>
                </ul>
            </td>
        </tr>
    </table>

    <!-- Bank Wire Transfer Details -->
    <div class="bank-box">
        <h4>Official Company Banking Wire Details (USD Account):</h4>
        <table style="width: 100%; font-size: 9pt;">
            <tr>
                <td style="width: 25%; color: #666;">Beneficiary Name:</td>
                <td style="width: 75%; font-weight: bold;">Uhalisia wa Tanzania Company Ltd</td>
            </tr>
            <tr>
                <td style="color: #666;">Bank Name:</td>
                <td style="font-weight: bold;">CRDB Bank Plc / NMB Bank Plc</td>
            </tr>
            <tr>
                <td style="color: #666;">Bank Branch:</td>
                <td>Clock Tower Branch, Arusha, Tanzania</td>
            </tr>
            <tr>
                <td style="color: #666;">Account Currency:</td>
                <td><strong>USD (United States Dollars)</strong></td>
            </tr>
            <tr>
                <td style="color: #666;">Swift Code (BIC):</td>
                <td><strong style="font-family: monospace;">CORUTZTZXXX</strong></td>
            </tr>
            <tr>
                <td style="color: #666;">Payment Reference:</td>
                <td><strong style="color: #C9642C;">{{ $booking->reference }} / {{ $booking->client->full_name }}</strong></td>
            </tr>
        </table>
        <div style="font-size: 8pt; color: #777; margin-top: 8px;">
            * Kindly forward proof of payment (wire slip or swift transfer confirmation) to <strong>info@uhalisiawatanzania.com</strong> once initiated so we can issue your official receipt.
        </div>
    </div>

    <!-- Sign-off & Client Portal Access -->
    <div style="margin-top: 15px; font-size: 9pt; border-top: 1px solid #EFEAE4; padding-top: 10px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <strong>Online Client Portal Access:</strong><br>
                    You may view live updates, payment balance, and voucher details anytime at:<br>
                    <span style="color: #C9642C; font-family: monospace; font-size: 8.5pt;">{{ url('/client/b/' . $booking->public_token) }}</span>
                </td>
                <td style="width: 40%; text-align: right; vertical-align: top;">
                    <div style="font-weight: bold; color: #2B1B12;">Uhalisia wa Tanzania Team</div>
                    <div style="color: #C9642C; font-size: 8.5pt;">Arusha Operations Office</div>
                    <div style="font-size: 8pt; color: #666;">TALA License: Registered Safari Operator</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <table class="footer-table">
        <tr>
            <td style="width: 50%;">
                &copy; {{ date('Y') }} Uhalisia wa Tanzania Company Ltd &bull; Booking {{ $booking->reference }}
            </td>
            <td style="width: 50%; text-align: right;">
                <span class="page-num"></span>
            </td>
        </tr>
    </table>

</body>
</html>
