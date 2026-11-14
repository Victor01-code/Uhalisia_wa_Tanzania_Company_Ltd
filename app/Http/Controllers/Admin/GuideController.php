<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuideController extends Controller
{
    public function index(): View
    {
        $guides = Guide::withCount('assignments')
            ->orderBy('name')
            ->get();

        return view('admin.guides.index', compact('guides'));
    }

    public function create(): View
    {
        return view('admin.guides.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'languages' => ['nullable', 'string', 'max:255'],
            'permit_number' => ['nullable', 'string', 'max:100'],
            'active' => ['nullable', 'boolean'],
        ]);

        $validated['active'] = $request->boolean('active', true);

        Guide::create($validated);

        return redirect()->route('admin.guides.index')
            ->with('success', 'Safari & Mountain Guide added successfully.');
    }

    public function edit(Guide $guide): View
    {
        return view('admin.guides.edit', compact('guide'));
    }

    public function update(Request $request, Guide $guide): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'languages' => ['nullable', 'string', 'max:255'],
            'permit_number' => ['nullable', 'string', 'max:100'],
            'active' => ['nullable', 'boolean'],
        ]);

        $validated['active'] = $request->boolean('active');

        $guide->update($validated);

        return redirect()->route('admin.guides.index')
            ->with('success', 'Safari & Mountain Guide updated successfully.');
    }

    public function destroy(Guide $guide): RedirectResponse
    {
        if ($guide->assignments()->exists()) {
            return back()->with('error', 'Cannot delete guide because they have booking assignment records.');
        }

        $guide->delete();

        return redirect()->route('admin.guides.index')
            ->with('success', 'Guide removed from directory.');
    }
}
