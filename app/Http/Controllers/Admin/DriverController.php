<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function index(): View
    {
        $drivers = Driver::withCount('assignments')
            ->orderBy('name')
            ->get();

        return view('admin.drivers.index', compact('drivers'));
    }

    public function create(): View
    {
        return view('admin.drivers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'languages' => ['nullable', 'string', 'max:255'],
            'licence_number' => ['nullable', 'string', 'max:100'],
            'active' => ['nullable', 'boolean'],
        ]);

        $validated['active'] = $request->boolean('active', true);

        Driver::create($validated);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Safari driver added successfully.');
    }

    public function edit(Driver $driver): View
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'languages' => ['nullable', 'string', 'max:255'],
            'licence_number' => ['nullable', 'string', 'max:100'],
            'active' => ['nullable', 'boolean'],
        ]);

        $validated['active'] = $request->boolean('active');

        $driver->update($validated);

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Safari driver updated successfully.');
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        if ($driver->assignments()->exists()) {
            return back()->with('error', 'Cannot delete driver because they have booking assignment records.');
        }

        $driver->delete();

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver removed from directory.');
    }
}
