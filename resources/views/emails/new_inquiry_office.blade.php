<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Safari Inquiry</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #FAF2EB; color: #2B1B12; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 8px; border: 1px solid #E5D6C7; padding: 30px; }
        .header { border-bottom: 2px solid #C9642C; padding-bottom: 15px; margin-bottom: 20px; text-align: center; }
        .header h1 { color: #C9642C; font-size: 22px; margin: 0; }
        .badge { background-color: #5B6B3D; color: #FFFFFF; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table td { padding: 8px 12px; border-bottom: 1px solid #F3DCC5; font-size: 14px; }
        .table td.label { font-weight: bold; width: 35%; color: #2B1B12; background: #FCF8F5; }
        .message-box { background: #FCF8F5; border-left: 4px solid #C9642C; padding: 15px; margin-top: 20px; font-size: 14px; white-space: pre-line; }
        .footer { margin-top: 25px; font-size: 12px; color: #777; text-align: center; border-top: 1px solid #EEE; padding-top: 15px; }
        .btn { display: inline-block; background: #C9642C; color: #FFF; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $companyName }} - Portal Notification</h1>
            <p style="margin: 5px 0 0 0; color: #555;">New Tour & Safari Lead Received</p>
        </div>

        <p>A new visitor inquiry has just been received on the booking system:</p>

        <table class="table">
            <tr>
                <td class="label">Client Name</td>
                <td><strong>{{ $lead->name }}</strong></td>
            </tr>
            <tr>
                <td class="label">Email Address</td>
                <td><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></td>
            </tr>
            <tr>
                <td class="label">Country</td>
                <td>{{ $lead->country ?? 'Not specified' }}</td>
            </tr>
            <tr>
                <td class="label">Tour / Interest</td>
                <td><span class="badge">{{ $lead->safari_interest ?? 'General Safari' }}</span></td>
            </tr>
            <tr>
                <td class="label">Lead Source</td>
                <td>{{ $lead->source }}</td>
            </tr>
            @if($lead->utm_source)
            <tr>
                <td class="label">Marketing Campaign</td>
                <td>{{ $lead->utm_source }} / {{ $lead->utm_medium }}</td>
            </tr>
            @endif
        </table>

        @if($lead->message)
        <div class="message-box">
            <strong>Inquiry Message:</strong><br>
            {{ $lead->message }}
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ url('/admin/leads/' . $lead->id) }}" class="btn">View & Convert Lead in Portal</a>
        </div>

        <div class="footer">
            Uhalisia wa Tanzania Safaris and Tours Management Portal<br>
            Arusha & Dar es Salaam, Tanzania | +255 784 590 722
        </div>
    </div>
</body>
</html>
