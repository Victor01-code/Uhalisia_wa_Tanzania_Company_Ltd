<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDay;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingDayController extends Controller
{
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'day_number' => ['required', 'integer', 'min:1', 'max:60'],
            'date' => ['nullable', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'accommodation' => ['nullable', 'string', 'max:255'],
            'meals' => ['nullable', 'string', 'max:255'],
            'activities' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        // Auto-calculate date from booking start_date if date was omitted
        if (empty($validated['date']) && $booking->start_date) {
            $startDate = Carbon::parse($booking->start_date);
            $validated['date'] = (clone $startDate)->addDays($validated['day_number'] - 1)->toDateString();
        }

        $validated['booking_id'] = $booking->id;

        BookingDay::create($validated);

        return back()->with('success', "Day {$validated['day_number']} added to booking itinerary.");
    }

    public function update(Request $request, Booking $booking, BookingDay $day): RedirectResponse
    {
        if ($day->booking_id !== $booking->id) {
            abort(404);
        }

        $validated = $request->validate([
            'day_number' => ['required', 'integer', 'min:1', 'max:60'],
            'date' => ['nullable', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'accommodation' => ['nullable', 'string', 'max:255'],
            'meals' => ['nullable', 'string', 'max:255'],
            'activities' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $day->update($validated);

        return back()->with('success', "Day {$day->day_number} updated successfully.");
    }

    public function destroy(Booking $booking, BookingDay $day): RedirectResponse
    {
        if ($day->booking_id !== $booking->id) {
            abort(404);
        }

        $num = $day->day_number;
        $day->delete();

        // Renumber remaining days in sequence
        $this->resequenceDays($booking);

        return back()->with('success', "Day {$num} deleted from itinerary.");
    }

    public function reorder(Request $request, Booking $booking): RedirectResponse
    {
        $dayId = (int) $request->input('day_id');
        $direction = $request->input('direction'); // 'up' or 'down'

        $targetDay = BookingDay::where('booking_id', $booking->id)->where('id', $dayId)->first();
        if (! $targetDay) {
            return back()->with('error', 'Day not found.');
        }

        $currentNumber = $targetDay->day_number;

        if ($direction === 'up' && $currentNumber > 1) {
            $swapDay = BookingDay::where('booking_id', $booking->id)
                ->where('day_number', $currentNumber - 1)
                ->first();

            if ($swapDay) {
                $swapDay->day_number = $currentNumber;
                $swapDay->save();
                $targetDay->day_number = $currentNumber - 1;
                $targetDay->save();
            }
        } elseif ($direction === 'down') {
            $swapDay = BookingDay::where('booking_id', $booking->id)
                ->where('day_number', $currentNumber + 1)
                ->first();

            if ($swapDay) {
                $swapDay->day_number = $currentNumber;
                $swapDay->save();
                $targetDay->day_number = $currentNumber + 1;
                $targetDay->save();
            }
        }

        $this->redateDays($booking);

        return back()->with('success', 'Itinerary day sequence updated.');
    }

    public function autoRedate(Booking $booking): RedirectResponse
    {
        $this->redateDays($booking);

        return back()->with('success', 'Itinerary calendar dates synchronized with booking start date.');
    }

    private function resequenceDays(Booking $booking): void
    {
        $days = $booking->bookingDays()->orderBy('day_number')->get();
        $counter = 1;
        $startDate = $booking->start_date ? Carbon::parse($booking->start_date) : null;

        foreach ($days as $d) {
            $d->day_number = $counter;
            if ($startDate) {
                $d->date = (clone $startDate)->addDays($counter - 1)->toDateString();
            }
            $d->save();
            $counter++;
        }
    }

    private function redateDays(Booking $booking): void
    {
        if (! $booking->start_date) {
            return;
        }

        $days = $booking->bookingDays()->orderBy('day_number')->get();
        $startDate = Carbon::parse($booking->start_date);

        foreach ($days as $d) {
            $d->date = (clone $startDate)->addDays($d->day_number - 1)->toDateString();
            $d->save();
        }
    }
}
