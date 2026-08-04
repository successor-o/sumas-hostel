<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Hostel;
use App\Models\User;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $facultyBreakdown = User::select('faculty')
            ->selectRaw('count(*) as total')
            ->groupBy('faculty')
            ->pluck('total', 'faculty');

        $housedCount = User::whereHas('activeAllocation')->count();
        $notHousedCount = User::count() - $housedCount;

        $hostels = Hostel::withCount('rooms')->orderBy('name')->get();

        $occupancyTrend = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            $totalBeds = Hostel::with('rooms')->get()->sum(fn ($h) => $h->totalBeds());
            $count = Allocation::where('status', 'active')->whereDate('allocated_at', '<=', $date->endOfMonth())->count();

            return [
                'label' => $date->format('M'),
                'value' => $totalBeds > 0 ? round(($count / $totalBeds) * 100) : 0,
            ];
        });

        $monthlyAllocations = collect(range(6, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return [
                'label' => $date->format('M'),
                'count' => Allocation::whereYear('allocated_at', $date->year)->whereMonth('allocated_at', $date->month)->count(),
            ];
        });

        return view('admin.reports', compact(
            'facultyBreakdown', 'housedCount', 'notHousedCount', 'hostels', 'occupancyTrend', 'monthlyAllocations'
        ));
    }
}
