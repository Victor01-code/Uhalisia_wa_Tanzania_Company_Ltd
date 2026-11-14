<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Guide;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'resource_type' => ['required', 'in:vehicle,driver,guide'],
            'resource_id' => ['required', 'integer'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        // Validate that resource actually exists
        $resourceExists = match ($validated['resource_type']) {
            'vehicle' => Vehicle::where('id', $validated['resource_id'])->exists(),
            'driver' => Driver::where('id', $validated['resource_id'])->exists(),
            'guide' => Guide::where('id', $validated['resource_id'])->exists(),
            default => false,
        };

        if (! $resourceExists) {
            return back()->withInput()->with('error', 'Selected resource does not exist.');
        }

        // Hard-block overlap detection check
        $conflict = Assignment::findOverlap(
            $validated['resource_type'],
            (int) $validated['resource_id'],
            $validated['start_date'],
            $validated['end_date']
        );

        if ($conflict) {
            $resourceName = match ($validated['resource_type']) {
                'vehicle' => Vehicle::find($validated['resource_id'])?->name . ' (' . Vehicle::find($validated['resource_id'])?->plate_number . ')',
                'driver' => Driver::find($validated['resource_id'])?->name,
                'guide' => Guide::find($validated['resource_id'])?->name,
                default => 'Resource',
            };

            $conflictingBookingRef = $conflict->booking?->reference ?? 'Another Booking';
            $conflictingBookingTitle = $conflict->booking?->title ?? '';
            $conflictStart = $conflict->start_date ? $conflict->start_date->format('d M Y') : $conflict->start_date;
            $conflictEnd = $conflict->end_date ? $conflict->end_date->format('d M Y') : $conflict->end_date;

            $errorMsg = "Schedule Conflict! {$resourceName} is already assigned to {$conflictingBookingRef} ({$conflictingBookingTitle}) from {$conflictStart} to {$conflictEnd}. Double-booking is strictly prohibited.";

            return back()->withInput()->with('error', $errorMsg);
        }

        $booking->assignments()->create([
            'resource_type' => $validated['resource_type'],
            'resource_id' => $validated['resource_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return back()->with('success', 'Resource assigned to booking successfully.');
    }

    public function destroy(Booking $booking, Assignment $assignment): RedirectResponse
    {
        if ($assignment->booking_id !== $booking->id) {
            abort(403, 'Unauthorized assignment removal.');
        }

        $assignment->delete();

        return back()->with('success', 'Resource assignment removed successfully.');
    }
}
