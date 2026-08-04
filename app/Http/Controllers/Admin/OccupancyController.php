<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use Illuminate\View\View;

class OccupancyController extends Controller
{
    public function index(): View
    {
        $hostels = Hostel::with('rooms')->orderBy('name')->get();

        $totalBeds = $hostels->sum(fn ($h) => $h->totalBeds());
        $occupiedBeds = $hostels->sum(fn ($h) => $h->occupiedBeds());
        $availableBeds = max(0, $totalBeds - $occupiedBeds);
        $overallOccupancy = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 1) : 0;

        $trend = collect(range(5, 0))->map(function ($monthsAgo) use ($totalBeds) {
            $date = now()->subMonths($monthsAgo);
            $count = \App\Models\Allocation::where('status', 'active')
                ->whereDate('allocated_at', '<=', $date->endOfMonth())
                ->count();

            return [
                'label' => $date->format('M'),
                'value' => $totalBeds > 0 ? round(($count / $totalBeds) * 100) : 0,
            ];
        });

        return view('admin.occupancy', compact('hostels', 'totalBeds', 'occupiedBeds', 'availableBeds', 'overallOccupancy', 'trend'));
    }
}
