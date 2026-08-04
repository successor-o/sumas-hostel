<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Hostel;
use App\Models\HostelApplication;
use App\Models\Room;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = User::count();
        $totalHostels = Hostel::count();
        $totalRooms = Room::count();
        $occupiedRooms = Room::whereIn('status', ['partial', 'full'])->count();
        $availableRooms = Room::where('status', 'vacant')->count();
        $pendingApplications = HostelApplication::pending()->count();
        $approvedApplications = HostelApplication::approved()->count();
        $rejectedApplications = HostelApplication::rejected()->count();

        $hostels = Hostel::withCount('rooms')->orderBy('name')->get();

        $occupancyLabels = $hostels->map(fn ($h) => str_replace(['Male Hostel - ', 'Female Hostel - ', 'Postgraduate Annex'], ['', '', 'PG Annex'], $h->name));
        $occupiedSeries = $hostels->map(fn ($h) => $h->occupiedBeds());
        $availableSeries = $hostels->map(fn ($h) => $h->availableBeds());

        $monthlyAllocations = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            return [
                'label' => $date->format('M'),
                'count' => Allocation::whereYear('allocated_at', $date->year)->whereMonth('allocated_at', $date->month)->count(),
            ];
        });

        $recentApplications = HostelApplication::with(['user', 'hostel'])->latest()->take(5)->get();

        $recentActivities = collect()
            ->concat(
                HostelApplication::with('user')->latest('reviewed_at')->whereNotNull('reviewed_at')->take(3)->get()
                    ->map(fn ($a) => [
                        'type' => $a->status === 'approved' ? 'success' : 'danger',
                        'icon' => $a->status === 'approved' ? 'fa-circle-check' : 'fa-circle-xmark',
                        'text' => ($a->user->name ?? 'A student')." application was {$a->status}.",
                        'time' => $a->reviewed_at,
                    ])->all()
            )
            ->concat(
                Allocation::with('user')->latest('allocated_at')->take(3)->get()
                    ->map(fn ($al) => [
                        'type' => 'info',
                        'icon' => 'fa-key',
                        'text' => 'Room allocated to '.($al->user->name ?? 'a student').'.',
                        'time' => $al->allocated_at,
                    ])->all()
            )
            ->sortByDesc('time')
            ->take(5);

        return view('admin.dashboard', compact(
            'totalStudents', 'totalHostels', 'totalRooms', 'occupiedRooms', 'availableRooms',
            'pendingApplications', 'approvedApplications', 'rejectedApplications',
            'occupancyLabels', 'occupiedSeries', 'availableSeries', 'monthlyAllocations',
            'recentApplications', 'recentActivities'
        ));
    }
}
