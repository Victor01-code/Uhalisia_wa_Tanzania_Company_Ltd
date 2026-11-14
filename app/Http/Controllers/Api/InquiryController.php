<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // 1. Reject payloads larger than 20 KB (20,480 bytes)
        $content = $request->getContent();
        if (strlen($content) > 20480) {
            return response()->json([
                'ok' => false,
                'error' => 'Payload too large. Maximum size is 20 KB.',
            ], 413);
        }

        // 2. Verify token from Authorization Bearer header, X-API-TOKEN header, or query param
        $expectedToken = env('INQUIRY_API_TOKEN', 'uwt_secret_quote_token_2026');
        $providedToken = $request->bearerToken() 
            ?: $request->header('X-API-TOKEN') 
            ?: $request->query('token') 
            ?: $request->input('token');

        if (empty($expectedToken) || empty($providedToken) || ! hash_equals((string) $expectedToken, (string) $providedToken)) {
            return response()->json([
                'ok' => false,
                'error' => 'Unauthorized: Invalid or missing inquiry token.',
            ], 401);
        }

        $allData = $request->all();

        // 3. Honeypot check: website_url must be empty
        $honeypot = $this->extractField($allData, ['website_url', 'website', '_hp']);
        if (! empty($honeypot)) {
            // Silently reject spam with 200 OK so bots don't adapt
            return response()->json([
                'ok' => true,
                'lead_id' => 0,
            ]);
        }

        // 4. Tolerant field extraction (flat or nested)
        $name = $this->extractField($allData, ['name', 'full_name', 'your-name', 'client_name', 'fullname', 'first_name']);
        $email = $this->extractField($allData, ['email', 'your-email', 'client_email', 'email_address']);
        $phone = $this->extractField($allData, ['phone', 'your-phone', 'telephone', 'mobile', 'whatsapp']);
        $country = $this->extractField($allData, ['country', 'your-country', 'nationality']);
        $safari = $this->extractField($allData, ['safari', 'safari_interest', 'tour', 'package', 'interest', 'trip']);
        $message = $this->extractField($allData, ['message', 'your-message', 'comments', 'notes', 'details', 'inquiry']);
        $utmSource = $this->extractField($allData, ['utm_source', 'source']);
        $utmMedium = $this->extractField($allData, ['utm_medium', 'medium']);

        // 5. Validation
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'country' => $country,
            'safari_interest' => $safari,
            'message' => $message,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'safari_interest' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // 6. Match existing client by email or create new client record
        $cleanEmail = strtolower(trim($email));
        $client = Client::where('email', $cleanEmail)->first();
        if (! $client) {
            $client = Client::create([
                'full_name' => trim($name),
                'email' => $cleanEmail,
                'phone' => $phone ? trim($phone) : null,
                'country' => $country ? trim($country) : null,
            ]);
        }

        // 7. Create Lead
        $lead = Lead::create([
            'client_id' => $client->id,
            'name' => trim($name),
            'email' => $cleanEmail,
            'country' => $country ? trim($country) : null,
            'safari_interest' => $safari ? trim($safari) : null,
            'message' => $message ? trim($message) : null,
            'source' => 'website_quote_form',
            'utm_source' => $utmSource ? substr(trim($utmSource), 0, 100) : null,
            'utm_medium' => $utmMedium ? substr(trim($utmMedium), 0, 100) : null,
            'status' => 'new',
            'raw_payload' => $allData,
        ]);

        // 8. Send notification email to the office
        $this->notifyOffice($lead);

        return response()->json([
            'ok' => true,
            'lead_id' => $lead->id,
        ], 201);
    }

    private function extractField(array $data, array $candidateKeys): ?string
    {
        // 1. Direct key match (case-insensitive)
        foreach ($candidateKeys as $key) {
            foreach ($data as $k => $v) {
                if (strcasecmp($k, $key) === 0 && is_scalar($v) && trim((string)$v) !== '') {
                    return trim((string)$v);
                }
            }
        }

        // 2. Elementor Pro format: fields[name][value] or form_fields[name]
        $containers = ['fields', 'form_fields', 'form_data'];
        foreach ($containers as $c) {
            if (isset($data[$c]) && is_array($data[$c])) {
                foreach ($candidateKeys as $key) {
                    if (isset($data[$c][$key])) {
                        $val = $data[$c][$key];
                        if (is_array($val) && isset($val['value'])) {
                            return trim((string)$val['value']);
                        }
                        if (is_scalar($val) && trim((string)$val) !== '') {
                            return trim((string)$val);
                        }
                    }
                }
            }
        }

        return null;
    }

    private function notifyOffice(Lead $lead): void
    {
        try {
            $officeEmail = Setting::get('company_email', env('COMPANY_EMAIL', 'info@uhalisiawatanzania.com'));
            $companyName = Setting::get('company_name', 'Uhalisia wa Tanzania');

            Mail::send('emails.new_inquiry_office', ['lead' => $lead, 'companyName' => $companyName], function ($m) use ($officeEmail, $lead) {
                $m->to($officeEmail)
                  ->subject("New Safari Inquiry from {$lead->name} ({$lead->country})");
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send office notification email for lead ' . $lead->id . ': ' . $e->getMessage());
        }
    }
}
