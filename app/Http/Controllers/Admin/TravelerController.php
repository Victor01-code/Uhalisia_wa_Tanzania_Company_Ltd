<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\Traveler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TravelerController extends Controller
{
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'passport_number' => ['nullable', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date'],
            'dietary_notes' => ['nullable', 'string', 'max:1000'],
            'emergency_contact' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['booking_id'] = $booking->id;

        $traveler = Traveler::create($validated);

        if (! empty($traveler->passport_number)) {
            AuditLog::log(
                'record_passport',
                'traveler',
                $traveler->id,
                "Registered traveler {$traveler->full_name} with passport on booking {$booking->reference}"
            );
        }

        return back()->with('success', "Traveler {$validated['full_name']} registered to booking.");
    }

    public function update(Request $request, Booking $booking, Traveler $traveler): RedirectResponse
    {
        if ($traveler->booking_id !== $booking->id) {
            abort(404);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'passport_number' => ['nullable', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date'],
            'dietary_notes' => ['nullable', 'string', 'max:1000'],
            'emergency_contact' => ['nullable', 'string', 'max:1000'],
        ]);

        $traveler->update($validated);

        if (! empty($traveler->passport_number)) {
            AuditLog::log(
                'update_passport',
                'traveler',
                $traveler->id,
                "Updated passport / details for traveler {$traveler->full_name} on booking {$booking->reference}"
            );
        }

        return back()->with('success', "Traveler record for {$traveler->full_name} updated.");
    }

    public function destroy(Booking $booking, Traveler $traveler): RedirectResponse
    {
        if ($traveler->booking_id !== $booking->id) {
            abort(404);
        }

        $name = $traveler->full_name;
        $travelerId = $traveler->id;
        $traveler->delete();

        AuditLog::log(
            'delete_traveler',
            'traveler',
            $travelerId,
            "Deleted traveler {$name} from booking {$booking->reference}"
        );

        return back()->with('success', "Traveler {$name} removed.");
    }
}
