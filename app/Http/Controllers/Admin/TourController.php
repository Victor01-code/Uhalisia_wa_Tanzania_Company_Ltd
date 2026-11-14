<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(): View
    {
        $tours = Tour::withCount('tourDays')
            ->orderBy('days')
            ->get();

        return view('admin.tours.index', compact('tours'));
    }

    public function create(): View
    {
        return view('admin.tours.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tours,slug'],
            'type' => ['required', 'in:safari,climb,beach,cultural,combo'],
            'days' => ['required', 'integer', 'min:1', 'max:60'],
            'base_price_usd' => ['required', 'numeric', 'min:0'],
            'summary' => ['nullable', 'string'],
            'active' => ['boolean'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['active'] = $request->boolean('active', true);

        $tour = Tour::create($validated);

        return redirect()->route('admin.tours.show', $tour->id)
            ->with('success', "Tour template [{$tour->name}] created successfully.");
    }

    public function show(Tour $tour): View
    {
        $tour->load(['tourDays']);

        return view('admin.tours.show', compact('tour'));
    }

    public function edit(Tour $tour): View
    {
        return view('admin.tours.edit', compact('tour'));
    }

    public function update(Request $request, Tour $tour): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:tours,slug,' . $tour->id],
            'type' => ['required', 'in:safari,climb,beach,cultural,combo'],
            'days' => ['required', 'integer', 'min:1', 'max:60'],
            'base_price_usd' => ['required', 'numeric', 'min:0'],
            'summary' => ['nullable', 'string'],
        ]);

        $validated['active'] = $request->boolean('active');

        $tour->update($validated);

        return redirect()->route('admin.tours.show', $tour->id)
            ->with('success', "Tour template updated successfully.");
    }

    public function destroy(Tour $tour): RedirectResponse
    {
        $name = $tour->name;
        $tour->delete();

        return redirect()->route('admin.tours.index')
            ->with('success', "Tour template [{$name}] deleted.");
    }
}
