<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Lead;
use App\Models\StatusHistory;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::with(['client', 'assignedUser']);

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('country', 'LIKE', "%{$search}%")
                  ->orWhere('safari_interest', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Source filter
        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }

        // Date range filter
        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $statuses = ['new', 'contacted', 'quoted', 'won', 'lost'];
        $sources = ['website_quote_form', 'book_form', 'manual'];

        return view('admin.leads.index', compact('leads', 'statuses', 'sources'));
    }

    public function create(): View
    {
        $users = User::where('active', true)->orderBy('name')->get();
        $tours = Tour::where('active', true)->orderBy('name')->get();

        return view('admin.leads.create', compact('users', 'tours'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'safari_interest' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
        ]);

        $cleanEmail = strtolower(trim($validated['email']));
        $client = Client::where('email', $cleanEmail)->first();

        $country = !empty($validated['country']) ? trim($validated['country']) : null;
        $safariInterest = !empty($validated['safari_interest']) ? trim($validated['safari_interest']) : null;
        $message = !empty($validated['message']) ? trim($validated['message']) : null;

        $lead = Lead::create([
            'client_id' => $client?->id,
            'name' => trim($validated['name']),
            'email' => $cleanEmail,
            'country' => $country,
            'safari_interest' => $safariInterest,
            'message' => $message,
            'source' => 'manual',
            'utm_source' => $validated['utm_source'] ?? null,
            'utm_medium' => $validated['utm_medium'] ?? null,
            'status' => 'new',
            'assigned_user_id' => $validated['assigned_user_id'] ?? Auth::id(),
            'raw_payload' => ['created_manually_by' => Auth::user()->name],
        ]);

        StatusHistory::log('lead', $lead->id, null, 'new', Auth::id(), 'Manual lead logged by ' . Auth::user()->name);

        return redirect()->route('admin.leads.show', $lead->id)
            ->with('success', "Lead for {$lead->name} created successfully.");
    }

    public function show(Lead $lead): View
    {
        $lead->load(['client', 'assignedUser', 'booking']);
        $users = User::where('active', true)->orderBy('name')->get();
        $history = StatusHistory::where('entity', 'lead')
            ->where('entity_id', $lead->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.leads.show', compact('lead', 'users', 'history'));
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,quoted,won,lost'],
            'note' => ['nullable', 'string', 'max:1000'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
        ]);

        $oldStatus = $lead->status;
        $newStatus = $validated['status'];

        $lead->status = $newStatus;
        if (isset($validated['assigned_user_id'])) {
            $lead->assigned_user_id = $validated['assigned_user_id'];
        }
        $lead->save();

        $note = $validated['note'] ?? null;
        if ($oldStatus !== $newStatus || ! empty($note)) {
            StatusHistory::log(
                'lead',
                $lead->id,
                $oldStatus,
                $newStatus,
                Auth::id(),
                $note ?: "Status updated to " . ucfirst($newStatus)
            );
        }

        return back()->with('success', "Lead status updated to " . ucfirst($newStatus));
    }

    public function convertToBooking(Lead $lead): RedirectResponse
    {
        // 1. Ensure a Client record exists
        if (! $lead->client_id) {
            $client = Client::where('email', $lead->email)->first();
            if (! $client) {
                $client = Client::create([
                    'full_name' => $lead->name,
                    'email' => $lead->email,
                    'country' => $lead->country,
                    'notes' => "Created automatically during conversion from Lead #{$lead->id}.",
                ]);
            }
            $lead->client_id = $client->id;
            $lead->save();
        }

        // 2. Mark lead as won
        if ($lead->status !== 'won') {
            $old = $lead->status;
            $lead->status = 'won';
            $lead->save();
            StatusHistory::log('lead', $lead->id, $old, 'won', Auth::id(), 'Converted to booking by ' . Auth::user()->name);
        }

        // 3. Redirect to booking creation page with lead parameters prefilled
        return redirect()->route('admin.bookings.create', [
            'lead_id' => $lead->id,
            'client_id' => $lead->client_id,
        ])->with('success', "Lead converted. Complete the booking details below.");
    }
}
