<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDay;
use App\Models\Client;
use App\Models\Lead;
use App\Models\StatusHistory;
use App\Models\Tour;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Guide;
use App\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['client', 'tour']);

        // Keyword search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhere('title', 'LIKE', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('full_name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Tour filter
        if ($tourId = $request->input('tour_id')) {
            $query->where('tour_id', $tourId);
        }

        // Payment mode filter
        if ($mode = $request->input('payment_mode')) {
            $query->where('payment_mode', $mode);
        }

        // Date filter
        if ($from = $request->input('date_from')) {
            $query->whereDate('start_date', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('start_date', '<=', $to);
        }

        $bookings = $query->orderBy('start_date', 'desc')->paginate(15)->withQueryString();

        $tours = Tour::where('active', true)->orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'tours'));
    }

    public function create(Request $request): View
    {
        $clients = Client::orderBy('full_name')->get();
        $tours = Tour::where('active', true)->orderBy('name')->get();

        $selectedClientId = $request->input('client_id');
        $selectedLeadId = $request->input('lead_id');

        $lead = $selectedLeadId ? Lead::find($selectedLeadId) : null;
        $client = $selectedClientId ? Client::find($selectedClientId) : null;

        return view('admin.bookings.create', compact('clients', 'tours', 'selectedClientId', 'selectedLeadId', 'lead', 'client'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'lead_id' => ['nullable', 'exists:leads,id'],
            'tour_id' => ['nullable', 'exists:tours,id'],
            'title' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'adults' => ['required', 'integer', 'min:1', 'max:50'],
            'children' => ['required', 'integer', 'min:0', 'max:50'],
            'status' => ['required', 'in:' . implode(',', Booking::STATUSES)],
            'payment_mode' => ['required', 'in:company_handles,client_direct'],
            'total_price_usd' => ['required', 'numeric', 'min:0'],
            'discount_usd' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['discount_usd'] = $validated['discount_usd'] ?? 0.00;
        $validated['created_by'] = Auth::id();

        $booking = Booking::create($validated);

        // Auto-build initial itinerary if a tour was chosen
        if (! empty($validated['tour_id'])) {
            $tour = Tour::with('tourDays')->find($validated['tour_id']);
            if ($tour && $tour->tourDays->isNotEmpty()) {
                $startDate = Carbon::parse($booking->start_date);
                foreach ($tour->tourDays as $td) {
                    $dayDate = (clone $startDate)->addDays($td->day_number - 1);
                    BookingDay::create([
                        'booking_id' => $booking->id,
                        'day_number' => $td->day_number,
                        'date' => $dayDate,
                        'title' => $td->title,
                        'description' => $td->description,
                        'destination' => $td->destination,
                        'accommodation' => $td->accommodation_note,
                        'meals' => $td->meals,
                        'activities' => $td->activities,
                    ]);
                }
            }
        }

        StatusHistory::log('booking', $booking->id, null, $booking->status, Auth::id(), 'Booking created with reference ' . $booking->reference);

        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', "Booking {$booking->reference} created successfully.");
    }

    public function show(Booking $booking): View
    {
        $booking->load([
            'client',
            'lead',
            'tour',
            'bookingDays',
            'travelers',
            'payments.receiver',
            'assignments',
            'expenses',
            'documents.uploader',
            'creator',
        ]);

        $history = StatusHistory::where('entity', 'booking')
            ->where('entity_id', $booking->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $vehicles = Vehicle::where('active', true)->orderBy('name')->get();
        $drivers = Driver::where('active', true)->orderBy('name')->get();
        $guides = Guide::where('active', true)->orderBy('name')->get();
        $currentExchangeRate = ExchangeRate::getRateFor((int)date('Y'), (int)date('n'));

        return view('admin.bookings.show', compact('booking', 'history', 'vehicles', 'drivers', 'guides', 'currentExchangeRate'));
    }

    public function edit(Booking $booking): View
    {
        $clients = Client::orderBy('full_name')->get();
        $tours = Tour::where('active', true)->orderBy('name')->get();

        return view('admin.bookings.edit', compact('booking', 'clients', 'tours'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'tour_id' => ['nullable', 'exists:tours,id'],
            'title' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'adults' => ['required', 'integer', 'min:1', 'max:50'],
            'children' => ['required', 'integer', 'min:0', 'max:50'],
            'payment_mode' => ['required', 'in:company_handles,client_direct'],
            'total_price_usd' => ['required', 'numeric', 'min:0'],
            'discount_usd' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['discount_usd'] = $validated['discount_usd'] ?? 0.00;

        $booking->update($validated);

        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', "Booking details updated successfully.");
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', Booking::STATUSES)],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $booking->status;
        $newStatus = $validated['status'];

        $booking->status = $newStatus;
        $booking->save();

        $note = $validated['note'] ?? null;
        if ($oldStatus !== $newStatus || ! empty($note)) {
            StatusHistory::log(
                'booking',
                $booking->id,
                $oldStatus,
                $newStatus,
                Auth::id(),
                $note ?: "Status updated to " . ucfirst(str_replace('_', ' ', $newStatus))
            );
        }

        return back()->with('success', "Booking status updated to " . ucfirst(str_replace('_', ' ', $newStatus)));
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $ref = $booking->reference;
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', "Booking {$ref} archived.");
    }
}
