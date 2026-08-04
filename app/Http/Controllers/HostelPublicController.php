<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use Illuminate\View\View;

class HostelPublicController extends Controller
{
    public function index(): View
    {
        $hostels = Hostel::with('rooms')->orderBy('name')->get();

        return view('public.hostels', ['hostels' => $hostels]);
    }
}
