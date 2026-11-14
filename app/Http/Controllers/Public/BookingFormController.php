<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BookingFormController extends Controller
{
    public function show(Request $request): View
    {
        $tours = Tour::where('active', true)->orderBy('days')->get();

        $selectedTourSlug = $request->query('tour');
        $prefilledInterest = $request->query('interest');

        $utmSource = $request->query('utm_source');
        $utmMedium = $request->query('utm_medium');

        return view('public.book', compact('tours', 'selectedTourSlug', 'prefilledInterest', 'utmSource', 'utmMedium'));
    }

    public function submit(Request $request)
    {
        // 1. Honeypot check: website_url must be empty
        if (! empty($request->input('website_url'))) {
            // Silently redirect bot to thank-you page without saving
            return redirect()->route('book.thankyou');
        }

        // 1b. Normalize phone if submitted via country code + number fields
        if (! $request->filled('phone') && $request->filled('phone_number')) {
            $code = trim($request->input('phone_country_code', ''));
            $number = trim($request->input('phone_number'));
            $request->merge(['phone' => trim($code . ' ' . $number)]);
        }

        // 2. Server-side validation
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:100'],
            'tour_slug' => ['nullable', 'string', 'exists:tours,slug'],
            'safari_interest' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date', 'after:today'],
            'adults' => ['required', 'integer', 'min:1', 'max:50'],
            'children' => ['nullable', 'integer', 'min:0', 'max:50'],
            'message' => ['nullable', 'string', 'max:5000'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
        ]);

        $cleanEmail = strtolower(trim($validated['email']));

        // 3. Match or update/create Client
        $client = Client::where('email', $cleanEmail)->first();
        if (! $client) {
            $client = Client::create([
                'full_name' => trim($validated['name']),
                'email' => $cleanEmail,
                'phone' => ! empty($validated['phone']) ? trim($validated['phone']) : null,
                'country' => trim($validated['country']),
            ]);
        }

        // Resolve safari interest text
        $interestText = $validated['safari_interest'] ?? null;
        if (! empty($validated['tour_slug'])) {
            $tour = Tour::where('slug', $validated['tour_slug'])->first();
            if ($tour) {
                $interestText = $tour->name . ($interestText ? " ({$interestText})" : '');
            }
        }

        $fullMessage = $validated['message'] ?? '';
        $metaDetails = [];
        if (! empty($validated['start_date'])) {
            $metaDetails[] = "Preferred Start Date: {$validated['start_date']}";
        }
        $metaDetails[] = "Travelers: {$validated['adults']} Adult(s)" . (! empty($validated['children']) ? ", {$validated['children']} Child(ren)" : '');
        if (! empty($validated['phone'])) {
            $metaDetails[] = "Phone / WhatsApp: {$validated['phone']}";
        }

        if (! empty($metaDetails)) {
            $fullMessage = implode("\n", $metaDetails) . "\n\n" . $fullMessage;
        }

        // 4. Create Lead
        $lead = Lead::create([
            'client_id' => $client->id,
            'name' => trim($validated['name']),
            'email' => $cleanEmail,
            'country' => trim($validated['country']),
            'safari_interest' => $interestText,
            'message' => trim($fullMessage),
            'source' => 'book_form',
            'utm_source' => $validated['utm_source'] ?? null,
            'utm_medium' => $validated['utm_medium'] ?? null,
            'status' => 'new',
            'raw_payload' => $request->except(['_token', 'website_url']),
        ]);

        // 5. Send confirmation email to client
        $this->sendClientConfirmation($lead);

        // 6. Send notification email to the office
        $this->sendOfficeNotification($lead);

        return redirect()->route('book.thankyou')->with('lead_name', $lead->name);
    }

    public function thankYou(): View
    {
        return view('public.thank_you');
    }

    private function sendClientConfirmation(Lead $lead): void
    {
        try {
            $companyName = Setting::get('company_name', 'Uhalisia wa Tanzania Company Ltd');
            $companyEmail = Setting::get('company_email', 'info@uhalisiawatanzania.com');
            $companyPhone = Setting::get('company_phone', '+255 784 590 722');

            Mail::send('emails.booking_form_client_confirmation', [
                'lead' => $lead,
                'companyName' => $companyName,
                'companyPhone' => $companyPhone,
                'companyEmail' => $companyEmail,
            ], function ($m) use ($lead, $companyName, $companyEmail) {
                $m->to($lead->email, $lead->name)
                  ->from($companyEmail, $companyName)
                  ->subject("Thank you for your safari inquiry - {$companyName}");
            });
        } catch (\Throwable $e) {
            Log::error("Failed to send booking client confirmation: " . $e->getMessage());
        }
    }

    private function sendOfficeNotification(Lead $lead): void
    {
        try {
            $officeEmail = Setting::get('company_email', env('COMPANY_EMAIL', 'info@uhalisiawatanzania.com'));
            $companyName = Setting::get('company_name', 'Uhalisia wa Tanzania');

            Mail::send('emails.new_inquiry_office', [
                'lead' => $lead,
                'companyName' => $companyName,
            ], function ($m) use ($officeEmail, $lead) {
                $m->to($officeEmail)
                  ->subject("New Booking Inquiry from /book: {$lead->name} ({$lead->country})");
            });
        } catch (\Throwable $e) {
            Log::error("Failed to send booking office notification: " . $e->getMessage());
        }
    }
}
