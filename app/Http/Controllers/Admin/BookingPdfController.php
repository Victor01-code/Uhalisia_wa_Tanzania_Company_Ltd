<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;
use App\Models\StatusHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingPdfController extends Controller
{
    public function quotePdf(Booking $booking, Request $request, ?string $filename = null)
    {
        $booking->load(['client', 'tour', 'bookingDays', 'payments']);

        $pdf = $this->buildQuotePdf($booking);
        $downloadFilename = "UWT-Quote-{$booking->reference}.pdf";
        $isDownload = $request->boolean('download');

        return $this->pdfResponse($pdf, $downloadFilename, $isDownload);
    }

    public function itineraryPdf(Booking $booking, Request $request, ?string $filename = null)
    {
        $booking->load([
            'client',
            'tour',
            'bookingDays',
            'travelers',
            'assignments',
        ]);

        $pdf = $this->buildItineraryPdf($booking);
        $downloadFilename = "UWT-Itinerary-{$booking->reference}.pdf";
        $isDownload = $request->boolean('download');

        return $this->pdfResponse($pdf, $downloadFilename, $isDownload);
    }

    public function clientQuotePdf(string $token, Request $request, ?string $filename = null)
    {
        $booking = Booking::where('public_token', $token)
            ->with(['client', 'tour', 'bookingDays', 'payments'])
            ->firstOrFail();

        $pdf = $this->buildQuotePdf($booking);
        $downloadFilename = "Safari-Quote-{$booking->reference}.pdf";
        $isDownload = ! $request->boolean('preview');

        return $this->pdfResponse($pdf, $downloadFilename, $isDownload);
    }

    public function clientItineraryPdf(string $token, Request $request, ?string $filename = null)
    {
        $booking = Booking::where('public_token', $token)
            ->with(['client', 'tour', 'bookingDays', 'travelers', 'assignments'])
            ->firstOrFail();

        $pdf = $this->buildItineraryPdf($booking);
        $downloadFilename = "Safari-Itinerary-{$booking->reference}.pdf";
        $isDownload = ! $request->boolean('preview');

        return $this->pdfResponse($pdf, $downloadFilename, $isDownload);
    }

    /**
     * Build standard binary HTTP response for PDF streaming or downloading
     * Guarantees application/pdf MIME type, exact Content-Length, and robust Content-Disposition
     */
    private function pdfResponse($pdf, string $filename, bool $isDownload = false)
    {
        $output = $pdf->output();
        $disposition = $isDownload ? 'attachment' : 'inline';

        if (! str_ends_with(strtolower($filename), '.pdf')) {
            $filename .= '.pdf';
        }

        return response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"; filename*=UTF-8''" . rawurlencode($filename),
            'Content-Length' => strlen($output),
            'Content-Transfer-Encoding' => 'binary',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    public function sendQuoteEmail(Request $request, Booking $booking): RedirectResponse
    {
        $booking->load(['client', 'tour', 'bookingDays', 'payments']);

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $pdf = $this->buildQuotePdf($booking);
        $pdfContent = $pdf->output();

        $client = $booking->client;
        $companyName = Setting::get('company_name', 'Uhalisia wa Tanzania Company Ltd');
        $companyEmail = Setting::get('company_email', 'info@uhalisiawatanzania.com');
        $companyPhone = Setting::get('company_phone', '+255 784 590 722');
        $portalUrl = url('/client/b/' . $booking->public_token);

        try {
            Mail::send('emails.quote_sent_client', [
                'booking' => $booking,
                'client' => $client,
                'customMessage' => $validated['message'] ?? null,
                'companyName' => $companyName,
                'companyPhone' => $companyPhone,
                'portalUrl' => $portalUrl,
            ], function ($m) use ($client, $booking, $companyName, $companyEmail, $pdfContent) {
                $m->to($client->email, $client->full_name)
                  ->from($companyEmail, $companyName)
                  ->subject("Your Safari Quotation & Proposal [{$booking->reference}] - {$companyName}")
                  ->attachData($pdfContent, "Safari-Quote-{$booking->reference}.pdf", [
                      'mime' => 'application/pdf',
                  ]);
            });

            // Update status if it was inquiry
            if ($booking->status === Booking::STATUS_INQUIRY) {
                $old = $booking->status;
                $booking->status = Booking::STATUS_QUOTE_SENT;
                $booking->save();

                StatusHistory::log(
                    'booking',
                    $booking->id,
                    $old,
                    Booking::STATUS_QUOTE_SENT,
                    Auth::id(),
                    'Official quote PDF dispatched to client email: ' . $client->email
                );
            } else {
                StatusHistory::log(
                    'booking',
                    $booking->id,
                    $booking->status,
                    $booking->status,
                    Auth::id(),
                    'Quote PDF re-sent to client email: ' . $client->email
                );
            }

            return back()->with('success', "Quotation PDF dispatched successfully to {$client->email}.");
        } catch (\Throwable $e) {
            Log::error('Failed to send quote email: ' . $e->getMessage());
            return back()->with('error', 'Failed to dispatch email: ' . $e->getMessage());
        }
    }

    public function sendItineraryEmail(Request $request, Booking $booking): RedirectResponse
    {
        $booking->load(['client', 'tour', 'bookingDays', 'travelers', 'assignments']);

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $pdf = $this->buildItineraryPdf($booking);
        $pdfContent = $pdf->output();

        $client = $booking->client;
        $companyName = Setting::get('company_name', 'Uhalisia wa Tanzania Company Ltd');
        $companyEmail = Setting::get('company_email', 'info@uhalisiawatanzania.com');
        $companyPhone = Setting::get('company_phone', '+255 784 590 722');
        $portalUrl = url('/client/b/' . $booking->public_token);

        try {
            Mail::send('emails.itinerary_voucher_client', [
                'booking' => $booking,
                'client' => $client,
                'customMessage' => $validated['message'] ?? null,
                'companyName' => $companyName,
                'companyPhone' => $companyPhone,
                'portalUrl' => $portalUrl,
            ], function ($m) use ($client, $booking, $companyName, $companyEmail, $pdfContent) {
                $m->to($client->email, $client->full_name)
                  ->from($companyEmail, $companyName)
                  ->subject("Your Confirmed Safari Voucher & Itinerary [{$booking->reference}] - {$companyName}")
                  ->attachData($pdfContent, "Safari-Voucher-{$booking->reference}.pdf", [
                      'mime' => 'application/pdf',
                  ]);
            });

            StatusHistory::log(
                'booking',
                $booking->id,
                $booking->status,
                $booking->status,
                Auth::id(),
                'Safari Itinerary Voucher PDF dispatched to client email: ' . $client->email
            );

            return back()->with('success', "Full Itinerary Voucher PDF dispatched successfully to {$client->email}.");
        } catch (\Throwable $e) {
            Log::error('Failed to send itinerary email: ' . $e->getMessage());
            return back()->with('error', 'Failed to dispatch email: ' . $e->getMessage());
        }
    }

    private function buildQuotePdf(Booking $booking)
    {
        $logoBase64 = $this->getLogoBase64();
        $companyName = Setting::get('company_name', 'Uhalisia wa Tanzania Company Ltd');
        $companyEmail = Setting::get('company_email', 'info@uhalisiawatanzania.com');
        $companyPhone = Setting::get('company_phone', '+255 784 590 722');

        return Pdf::loadView('pdf.quote', [
            'booking' => $booking,
            'logoBase64' => $logoBase64,
            'companyName' => $companyName,
            'companyEmail' => $companyEmail,
            'companyPhone' => $companyPhone,
        ])->setPaper('a4', 'portrait');
    }

    private function buildItineraryPdf(Booking $booking)
    {
        $logoBase64 = $this->getLogoBase64();
        $companyName = Setting::get('company_name', 'Uhalisia wa Tanzania Company Ltd');
        $companyEmail = Setting::get('company_email', 'info@uhalisiawatanzania.com');
        $companyPhone = Setting::get('company_phone', '+255 784 590 722');

        return Pdf::loadView('pdf.itinerary', [
            'booking' => $booking,
            'logoBase64' => $logoBase64,
            'companyName' => $companyName,
            'companyEmail' => $companyEmail,
            'companyPhone' => $companyPhone,
        ])->setPaper('a4', 'portrait');
    }

    private function getLogoBase64(): ?string
    {
        $path = public_path('images/logo.png');
        if (file_exists($path)) {
            return base64_encode(file_get_contents($path));
        }
        return null;
    }
}
