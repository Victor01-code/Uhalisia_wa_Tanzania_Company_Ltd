<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Document;

class ClientPortalController extends Controller
{
    public function showLoginForm(): View
    {
        return view('client.login');
    }

    public function requestLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($request->input('email')));

        // Find client and recent booking
        $client = Client::where('email', $email)->first();

        if ($client) {
            $booking = Booking::where('client_id', $client->id)
                ->where('status', '!=', Booking::STATUS_CANCELLED)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($booking) {
                // Ensure valid token
                if (empty($booking->public_token)) {
                    $booking->public_token = Str::random(64);
                    $booking->save();
                }

                $this->emailClientAccessLink($client, $booking);
            }
        }

        // Never reveal whether email exists for security and privacy
        return back()->with('status', 'If your email address is associated with a booking in our system, a private access link has been dispatched to your inbox. Please check your inbox and spam folder.');
    }

    public function showBooking(string $token): View
    {
        $booking = Booking::where('public_token', $token)
            ->with([
                'client',
                'bookingDays',
                'travelers',
                'assignments',
                'documents' => fn ($q) => $q->where('visible_to_client', true),
                'payments',
            ])
            ->firstOrFail();

        return view('client.show', compact('booking'));
    }

    public function downloadDocument(string $token, Document $document): StreamedResponse
    {
        $booking = Booking::where('public_token', $token)->firstOrFail();

        if ($document->booking_id !== $booking->id || ! $document->visible_to_client) {
            abort(404, 'Document not available or permission denied.');
        }

        if (! Storage::exists($document->file_path)) {
            abort(404, 'Document file not found on server.');
        }

        $extension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
        $mime = Storage::mimeType($document->file_path);

        if (empty($extension)) {
            if ($mime === 'application/pdf') {
                $extension = 'pdf';
            } elseif ($mime === 'image/jpeg') {
                $extension = 'jpg';
            } elseif ($mime === 'image/png') {
                $extension = 'png';
            }
        }

        $cleanTitle = $document->title;
        if (! empty($extension)) {
            $cleanTitle = preg_replace('/\.' . preg_quote($extension, '/') . '$/i', '', $cleanTitle);
        }
        $slug = Str::slug($cleanTitle);
        if (empty($slug)) {
            $slug = 'document-' . $document->id;
        }

        $downloadName = $slug . ($extension ? '.' . $extension : '');

        $headers = [
            'Content-Disposition' => "attachment; filename=\"{$downloadName}\"; filename*=UTF-8''" . rawurlencode($downloadName),
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public',
        ];

        if ($extension === 'pdf' || $mime === 'application/pdf') {
            $headers['Content-Type'] = 'application/pdf';
        }

        return Storage::download($document->file_path, $downloadName, $headers);
    }

    private function emailClientAccessLink(Client $client, Booking $booking): void
    {
        try {
            $companyName = Setting::get('company_name', 'Uhalisia wa Tanzania Company Ltd');
            $companyEmail = Setting::get('company_email', 'info@uhalisiawatanzania.com');
            $accessUrl = url('/client/b/' . $booking->public_token);

            Mail::send('emails.client_access_link', [
                'client' => $client,
                'booking' => $booking,
                'accessUrl' => $accessUrl,
                'companyName' => $companyName,
            ], function ($m) use ($client, $companyName, $companyEmail) {
                $m->to($client->email, $client->full_name)
                  ->from($companyEmail, $companyName)
                  ->subject("Your Private Itinerary Access Link - {$companyName}");
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send client access link email: ' . $e->getMessage());
        }
    }
}
