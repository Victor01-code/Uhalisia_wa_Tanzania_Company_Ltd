<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx,webp'],
            'visible_to_client' => ['nullable', 'boolean'],
        ]);

        $uploadedFile = $request->file('file');
        $storedPath = $uploadedFile->store("documents/{$booking->id}");

        $booking->documents()->create([
            'title' => $validated['title'],
            'file_path' => $storedPath,
            'visible_to_client' => $request->boolean('visible_to_client', true),
            'uploaded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Document uploaded and attached to booking successfully.');
    }

    public function download(Booking $booking, Document $document): StreamedResponse
    {
        if ($document->booking_id !== $booking->id) {
            abort(403, 'Unauthorized access to document.');
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

        // Clean title: strip existing extension from title if present before slugging
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

        AuditLog::log(
            'download_document',
            'document',
            $document->id,
            "Downloaded file {$downloadName} ({$document->title}) for booking {$booking->reference}"
        );

        return Storage::download($document->file_path, $downloadName, $headers);
    }

    public function toggleVisibility(Booking $booking, Document $document): RedirectResponse
    {
        if ($document->booking_id !== $booking->id) {
            abort(403, 'Unauthorized access to document.');
        }

        $document->visible_to_client = ! $document->visible_to_client;
        $document->save();

        $status = $document->visible_to_client ? 'now visible to client' : 'now hidden from client';
        return back()->with('success', "Document is {$status}.");
    }

    public function destroy(Booking $booking, Document $document): RedirectResponse
    {
        if ($document->booking_id !== $booking->id) {
            abort(403, 'Unauthorized access to document.');
        }

        $title = $document->title;
        $docId = $document->id;

        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        AuditLog::log(
            'delete_document',
            'document',
            $docId,
            "Deleted document {$title} from booking {$booking->reference}"
        );

        return back()->with('success', 'Document removed successfully.');
    }
}
