<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourDay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TourDayController extends Controller
{
    public function store(Request $request, Tour $tour): RedirectResponse
    {
        $validated = $request->validate([
            'day_number' => ['required', 'integer', 'min:1', 'max:60'],
            'title' => ['required', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'accommodation_note' => ['nullable', 'string', 'max:255'],
            'meals' => ['nullable', 'string', 'max:255'],
            'activities' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $validated['tour_id'] = $tour->id;

        TourDay::create($validated);

        // Update tour duration days if added day exceeds current days count
        if ($validated['day_number'] > $tour->days) {
            $tour->days = $validated['day_number'];
            $tour->save();
        }

        return back()->with('success', "Day {$validated['day_number']} added to tour template.");
    }

    public function update(Request $request, Tour $tour, TourDay $tourDay): RedirectResponse
    {
        if ($tourDay->tour_id !== $tour->id) {
            abort(404);
        }

        $validated = $request->validate([
            'day_number' => ['required', 'integer', 'min:1', 'max:60'],
            'title' => ['required', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'accommodation_note' => ['nullable', 'string', 'max:255'],
            'meals' => ['nullable', 'string', 'max:255'],
            'activities' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        $tourDay->update($validated);

        return back()->with('success', "Day {$tourDay->day_number} updated.");
    }

    public function destroy(Tour $tour, TourDay $tourDay): RedirectResponse
    {
        if ($tourDay->tour_id !== $tour->id) {
            abort(404);
        }

        $dayNum = $tourDay->day_number;
        $tourDay->delete();

        // Renumber remaining days in sequence
        $remaining = $tour->tourDays()->orderBy('day_number')->get();
        $counter = 1;
        foreach ($remaining as $d) {
            if ($d->day_number !== $counter) {
                $d->day_number = $counter;
                $d->save();
            }
            $counter++;
        }

        return back()->with('success', "Day {$dayNum} removed from tour template.");
    }
}
