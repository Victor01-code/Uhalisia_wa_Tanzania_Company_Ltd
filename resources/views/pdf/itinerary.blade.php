<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Itinerary Voucher - {{ $booking->reference }}</title>
    <style>
        @page {
            margin: 1.2cm 1.2cm 1.5cm 1.2cm;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2B1B12;
            font-size: 10pt;
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
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header-logo {
            width: 180px;
            vertical-align: middle;
        }

        .header-company {
            text-align: right;
            vertical-align: middle;
            font-size: 8.5pt;
            color: #555;
            line-height: 1.35;
        }

        .company-name {
            font-size: 13pt;
            font-weight: bold;
            color: #2B1B12;
            margin-bottom: 2px;
        }

        .company-tagline {
            color: #C9642C;
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 3px;
        }

        /* Banner */
        .title-banner {
            background-color: #FAF6F0;
            border-left: 4px solid #5B6B3D;
            padding: 10px 14px;
            margin-bottom: 18px;
        }

        .voucher-title {
            font-size: 15pt;
            font-weight: bold;
            color: #2B1B12;
            margin: 0 0 3px 0;
        }

        /* Key Metrics Table */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-box {
            background-color: #FAFAFA;
            border: 1px solid #EFEAE4;
            padding: 10px 12px;
            border-radius: 4px;
            vertical-align: top;
            width: 48%;
            font-size: 9pt;
        }

        .info-title {
            font-weight: bold;
            color: #5B6B3D;
            border-bottom: 1px solid #E5DFD7;
            padding-bottom: 3px;
            margin-bottom: 6px;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.05em;
        }

        .info-row {
            margin-bottom: 3px;
        }

        .info-label {
            color: #777;
            width: 110px;
            display: inline-block;
        }

        .info-val {
            font-weight: 600;
            color: #2B1B12;
        }

        /* Crew / Vehicle Highlight Box */
        .crew-box {
            background-color: #F4F6F0;
            border: 1px solid #C8D4B8;
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 18px;
            font-size: 9pt;
        }

        /* Section Headings */
        .section-heading {
            font-size: 11pt;
            font-weight: bold;
            color: #2B1B12;
            border-bottom: 1.5px solid #D98B3F;
            padding-bottom: 3px;
            margin-top: 18px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* Day block */
        .day-block {
            border: 1px solid #EAE5DE;
            border-radius: 4px;
            margin-bottom: 14px;
            background-color: #FFFFFF;
            page-break-inside: avoid;
        }

        .day-header {
            background-color: #FAF6F0;
            padding: 8px 12px;
            border-bottom: 1px solid #EAE5DE;
        }

        .day-badge {
            background-color: #C9642C;
            color: #FFF;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8.5pt;
            margin-right: 6px;
        }

        .day-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #2B1B12;
            display: inline;
        }

        .day-date {
            float: right;
            font-size: 9pt;
            color: #666;
            font-weight: 600;
        }

        .day-body {
            padding: 10px 12px;
            font-size: 9pt;
        }

        .day-meta-table {
            width: 100%;
            margin-bottom: 6px;
            font-size: 8.5pt;
            color: #555;
        }

        .day-meta-table td {
            padding: 2px 0;
        }

        .day-desc {
            color: #333;
            line-height: 1.45;
            margin-top: 4px;
            margin-bottom: 0;
            text-align: justify;
        }

        /* Packing list */
        .packing-table {
            width: 100%;
            font-size: 8.5pt;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .packing-table td {
            vertical-align: top;
            width: 33.3%;
            padding: 6px 10px;
            background-color: #FAFAFA;
            border: 1px solid #EFEAE4;
        }

        .packing-table ul {
            margin: 4px 0 0 0;
            padding-left: 15px;
            line-height: 1.4;
        }

        /* Footer */
        .footer-table {
            width: 100%;
            border-top: 1px solid #E5DFD7;
            padding-top: 8px;
            font-size: 7.5pt;
            color: #888;
            margin-top: 25px;
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
                    <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Uhalisia wa Tanzania Logo" style="height: 60px;">
                @else
                    <h2 style="color: #C9642C; margin: 0;">Uhalisia wa Tanzania</h2>
                @endif
            </td>
            <td class="header-company">
                <div class="company-name">{{ $companyName ?? 'Uhalisia wa Tanzania Company Ltd' }}</div>
                <div class="company-tagline">Safaris and Tours &bull; Tanzania</div>
                <div>Arusha Headquarters &bull; Dar es Salaam Branch Office</div>
                <div>Email: {{ $companyEmail ?? 'info@uhalisiawatanzania.com' }} &bull; Web: www.uhalisiawatanzania.com</div>
                <div><strong>24/7 Safari Duty Hotline / WhatsApp: {{ $companyPhone ?? '+255 784 590 722' }}</strong></div>
            </td>
        </tr>
    </table>

    <!-- Title Banner -->
    <div class="title-banner">
        <table style="width: 100%;">
            <tr>
                <td>
                    <h1 class="voucher-title">OFFICIAL SAFARI ITINERARY & TRAVEL VOUCHER</h1>
                    <div style="font-size: 9.5pt; color: #555;">
                        Booking Reference: <strong style="color: #2B1B12;">{{ $booking->reference }}</strong>
                        &bull; Safari: <strong>{{ $booking->title }}</strong>
                    </div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <span style="background-color: #5B6B3D; color: #FFF; padding: 5px 10px; border-radius: 3px; font-weight: bold; font-size: 9pt; text-transform: uppercase;">
                        Status: {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Overview Details Table -->
    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="info-box">
                <div class="info-title">Traveler Party Details</div>
                <div class="info-row"><span class="info-label">Lead Traveler:</span> <span class="info-val">{{ $booking->client->full_name }}</span></div>
                <div class="info-row"><span class="info-label">Contact Email:</span> <span class="info-val">{{ $booking->client->email }}</span></div>
                @if($booking->client->phone)
                    <div class="info-row"><span class="info-label">Mobile Phone:</span> <span class="info-val">{{ $booking->client->phone }}</span></div>
                @endif
                <div class="info-row"><span class="info-label">Country of Origin:</span> <span class="info-val">{{ $booking->client->country ?? 'International' }}</span></div>
                <div class="info-row"><span class="info-label">Party Size:</span> <span class="info-val">{{ $booking->adults }} Adult(s) {{ $booking->children > 0 ? ', ' . $booking->children . ' Child(ren)' : '' }}</span></div>
                
                @if($booking->travelers->isNotEmpty())
                    <div style="margin-top: 8px; border-top: 1px dotted #CCC; padding-top: 4px;">
                        <strong style="font-size: 8pt; color: #666; text-transform: uppercase;">Passenger Manifest:</strong>
                        <div style="font-size: 8.5pt;">
                            @foreach($booking->travelers as $tr)
                                &bull; {{ $tr->full_name }} ({{ $tr->nationality ?: 'Intl' }})
                                @if($tr->dietary_notes)
                                    <span style="color: #C9642C; font-size: 8pt;">[{{ $tr->dietary_notes }}]</span>
                                @endif
                                <br>
                            @endforeach
                        </div>
                    </div>
                @endif
            </td>
            <td style="width: 4%;"></td>
            <td class="info-box">
                <div class="info-title">Expedition Logistics</div>
                <div class="info-row"><span class="info-label">Start Date:</span> <span class="info-val">{{ $booking->start_date ? $booking->start_date->format('l, d M Y') : 'TBD' }}</span></div>
                <div class="info-row"><span class="info-label">End Date:</span> <span class="info-val">{{ $booking->end_date ? $booking->end_date->format('l, d M Y') : 'TBD' }}</span></div>
                <div class="info-row"><span class="info-label">Duration:</span> <span class="info-val">{{ $booking->durationDays() }} Days &bull; {{ max(1, $booking->durationDays() - 1) }} Nights</span></div>
                <div class="info-row"><span class="info-label">Safari Vehicle:</span> <span class="info-val">Custom 4x4 Safari Land Cruiser</span></div>
                <div class="info-row"><span class="info-label">Departure Base:</span> <span class="info-val">Arusha / Kilimanjaro, Tanzania</span></div>
                <div class="info-row"><span class="info-label">Emergency Cover:</span> <span class="info-val" style="color: #2e7d32;">AMREF Flying Doctors Evac Included</span></div>
            </td>
        </tr>
    </table>

    <!-- Operations / Assigned Crew Note -->
    @php
        $assignedVehicle = $booking->assignments->firstWhere('resource_type', 'vehicle')?->resource();
        $assignedDriver = $booking->assignments->firstWhere('resource_type', 'driver')?->resource();
        $assignedGuide = $booking->assignments->firstWhere('resource_type', 'guide')?->resource();
    @endphp

    @if($assignedDriver || $assignedGuide || $assignedVehicle)
        <div class="crew-box">
            <strong style="color: #5B6B3D; text-transform: uppercase; font-size: 8pt; display: block; margin-bottom: 4px;">Assigned Safari Crew & Vehicle:</strong>
            <table style="width: 100%; font-size: 8.5pt;">
                <tr>
                    @if($assignedDriver)
                        <td style="width: 33%;"><strong>Driver-Guide:</strong> {{ $assignedDriver->name }} ({{ $assignedDriver->phone }})</td>
                    @endif
                    @if($assignedGuide)
                        <td style="width: 33%;"><strong>Safari Guide:</strong> {{ $assignedGuide->name }} ({{ $assignedGuide->phone }})</td>
                    @endif
                    @if($assignedVehicle)
                        <td style="width: 33%;"><strong>Safari Vehicle:</strong> {{ $assignedVehicle->name }} [{{ $assignedVehicle->plate_number }}]</td>
                    @endif
                </tr>
            </table>
        </div>
    @endif

    <!-- Day by Day Itinerary -->
    <div class="section-heading">Detailed Day-by-Day Safari Itinerary</div>

    @forelse($booking->bookingDays as $day)
        <div class="day-block">
            <div class="day-header">
                <span class="day-badge">DAY {{ $day->day_number }}</span>
                <span class="day-title">{{ $day->title }}</span>
                @if($day->date)
                    <span class="day-date">{{ $day->date->format('D, d M Y') }}</span>
                @endif
            </div>
            <div class="day-body">
                <table class="day-meta-table">
                    <tr>
                        @if($day->destination)
                            <td style="width: 33%;"><strong>Destination:</strong> {{ $day->destination }}</td>
                        @endif
                        @if($day->accommodation)
                            <td style="width: 40%;"><strong>Lodge / Camp:</strong> <span style="color: #C9642C; font-weight: bold;">{{ $day->accommodation }}</span></td>
                        @endif
                        @if($day->meals)
                            <td style="width: 27%;"><strong>Meal Plan:</strong> {{ $day->meals }}</td>
                        @endif
                    </tr>
                </table>

                @if($day->activities)
                    <div style="font-size: 8.5pt; color: #5B6B3D; font-weight: 600; margin-bottom: 4px;">
                        Safari Focus: {{ $day->activities }}
                    </div>
                @endif

                @if($day->description)
                    <p class="day-desc">{{ $day->description }}</p>
                @endif
            </div>
        </div>
    @empty
        <div style="text-align: center; color: #888; padding: 20px;">Detailed schedule confirmed upon arrival.</div>
    @endforelse

    <div class="page-break"></div>

    <!-- Essential Travel Guidelines & Packing List -->
    <div class="section-heading">Essential Tanzania Safari Packing Guide & Important Advice</div>

    <table class="packing-table" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <strong style="color: #C9642C;">1. Safari Clothing & Footwear</strong>
                <ul>
                    <li>Neutral-colored clothing (khaki, beige, olive, brown). Avoid dark blue and black (attracts tsetse flies).</li>
                    <li>Lightweight, breathable long-sleeve shirts and trousers for sun and insect protection.</li>
                    <li>Warm fleece, jacket, or sweater (Ngorongoro Crater rim temperatures drop to 10&deg;C / 50&deg;F at night).</li>
                    <li>Comfortable walking shoes or lightweight hiking boots plus casual sandals for camp.</li>
                    <li>Wide-brimmed safari hat and polarized UV sunglasses.</li>
                </ul>
            </td>
            <td>
                <strong style="color: #C9642C;">2. Photographic & Electronics</strong>
                <ul>
                    <li>Camera with telephoto optical zoom lens (minimum 200mm recommended for wildlife).</li>
                    <li>Quality binoculars (8x42 or 10x42 magnification recommended per person).</li>
                    <li>Spare memory cards and camera batteries (cold mornings drain batteries quickly).</li>
                    <li>UK standard 3-pin rectangular plug adapter (Type G) used throughout Tanzania (230V).</li>
                    <li>Portable USB power bank (our 4x4 Land Cruisers also feature in-vehicle USB charging ports).</li>
                </ul>
            </td>
            <td>
                <strong style="color: #C9642C;">3. Health, Currency & Logistics</strong>
                <ul>
                    <li>Consult travel doctor for Malaria prophylaxis prior to arrival. Insect repellent with DEET.</li>
                    <li>Yellow Fever vaccination certificate required if arriving from endemic countries.</li>
                    <li>US Dollar bills must be dated <strong>2009 or newer</strong>; older bills are not accepted by banks/lodges.</li>
                    <li>Cash is essential for gratuities (tipping guidelines: $15-$20/day for your driver-guide).</li>
                    <li>Soft-sided duffel bags are required for domestic bush flights (max 15kg/33lbs per guest).</li>
                </ul>
            </td>
        </tr>
    </table>

    <!-- Emergency Contacts & Company Notice -->
    <div style="background-color: #FAF6F0; border: 1px solid #D98B3F; padding: 12px 16px; border-radius: 4px; font-size: 8.5pt; margin-top: 15px;">
        <h4 style="margin: 0 0 6px 0; color: #2B1B12; font-size: 9.5pt;">24/7 Operations Duty Manager & Emergency Contacts:</h4>
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <strong>UWT Arusha Operations Desk:</strong> {{ $companyPhone ?? '+255 784 590 722' }}<br>
                    <strong>Email:</strong> {{ $companyEmail ?? 'info@uhalisiawatanzania.com' }}<br>
                    <strong>Chief Safari Coordinator:</strong> +255 784 590 722
                </td>
                <td style="width: 50%;">
                    <strong>AMREF Flying Doctors 24hr Emergency Evacuation:</strong><br>
                    Air Ambulance Dispatch: +254 20 699 2000 / +254 733 639 088<br>
                    Coverage: In-flight intensive care evacuation to Nairobi/Dar hospitals.
                </td>
            </tr>
        </table>
    </div>

    <!-- Client Portal Link -->
    <div style="margin-top: 15px; font-size: 8.5pt; text-align: center; color: #666;">
        Live digital itinerary, lodge vouchers, and payment records can be accessed anytime on your smartphone via:<br>
        <strong style="color: #C9642C; font-family: monospace;">{{ url('/client/b/' . $booking->public_token) }}</strong>
    </div>

    <!-- Footer -->
    <table class="footer-table">
        <tr>
            <td style="width: 60%;">
                &copy; {{ date('Y') }} Uhalisia wa Tanzania Company Ltd &bull; Voucher Reference: {{ $booking->reference }}
            </td>
            <td style="width: 40%; text-align: right;">
                <span class="page-num"></span>
            </td>
        </tr>
    </table>

</body>
</html>
