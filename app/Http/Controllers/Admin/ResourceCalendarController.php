<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Driver;
use App\Models\Guide;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResourceCalendarController extends Controller
{
    public function index(Request $request): View
    {
        // Determine the date range to display (default: current month)
        $month = $request->input('month', now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = (clone $startOfMonth)->endOfMonth();

        $daysInMonth = $startOfMonth->daysInMonth;
        $monthLabel = $startOfMonth->format('F Y');

        // Navigation months
        $prevMonth = (clone $startOfMonth)->subMonth()->format('Y-m');
        $nextMonth = (clone $startOfMonth)->addMonth()->format('Y-m');

        // Load all active resources
        $vehicles = Vehicle::where('active', true)->orderBy('name')->get();
        $drivers = Driver::where('active', true)->orderBy('name')->get();
        $guides = Guide::where('active', true)->orderBy('name')->get();

        // Load all assignments that overlap this month
        $assignments = Assignment::with('booking')
            ->where('start_date', '<=', $endOfMonth->toDateString())
            ->where('end_date', '>=', $startOfMonth->toDateString())
            ->get();

        // Build a lookup: resource_type → resource_id → [assignment objects]
        $assignmentMap = [];
        foreach ($assignments as $assignment) {
            $key = $assignment->resource_type . '_' . $assignment->resource_id;
            $assignmentMap[$key][] = $assignment;
        }

        // Build calendar data for each resource
        $calendarData = [];

        foreach (['vehicle' => $vehicles, 'driver' => $drivers, 'guide' => $guides] as $type => $resources) {
            foreach ($resources as $resource) {
                $key = $type . '_' . $resource->id;
                $days = [];

                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $date = Carbon::create($startOfMonth->year, $startOfMonth->month, $d);
                    $dateStr = $date->toDateString();
                    $occupied = null;

                    if (isset($assignmentMap[$key])) {
                        foreach ($assignmentMap[$key] as $asgn) {
                            if ($asgn->start_date->lte($date) && $asgn->end_date->gte($date)) {
                                $occupied = $asgn;
                                break;
                            }
                        }
                    }

                    $days[$d] = $occupied;
                }

                $label = match ($type) {
                    'vehicle' => $resource->name . ' (' . $resource->plate_number . ')',
                    'driver'  => $resource->name,
                    'guide'   => $resource->name,
                };

                $calendarData[] = [
                    'type' => $type,
                    'type_label' => ucfirst($type),
                    'id' => $resource->id,
                    'label' => $label,
                    'days' => $days,
                ];
            }
        }

        return view('admin.resources.calendar', compact(
            'calendarData',
            'startOfMonth',
            'endOfMonth',
            'daysInMonth',
            'monthLabel',
            'prevMonth',
            'nextMonth',
            'month'
        ));
    }
}
