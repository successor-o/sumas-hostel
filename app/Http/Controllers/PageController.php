<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\User;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $hostels = Hostel::with('rooms')->get();

        $stats = [
            'hostels' => $hostels->count(),
            'rooms' => $hostels->sum(fn ($h) => $h->rooms->count()),
            'beds' => $hostels->sum(fn ($h) => $h->totalBeds()),
            'students' => User::count(),
        ];

        return view('public.home', [
            'stats' => $stats,
            'previewHostels' => $hostels->sortByDesc(fn ($h) => $h->availableBeds())->take(3)->values(),
        ]);
    }

    public function about(): View
    {
        return view('public.about', [
            'hostelCount' => Hostel::count(),
            'studentCount' => User::count(),
        ]);
    }

    public function facilities(): View
    {
        return view('public.facilities');
    }

    public function gallery(): View
    {
        return view('public.gallery');
    }

    public function faq(): View
    {
        return view('public.faq');
    }
}
