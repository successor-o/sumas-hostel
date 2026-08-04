<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = Auth::guard('web')->user();

        $allocation = $student->allocations()
            ->where('status', 'active')
            ->with(['hostel', 'room'])
            ->latest('allocated_at')
            ->first();

        $latestApplication = $student->applications()->with('hostel')->latest()->first();

        $notifications = $student->notifications()->take(3)->get();

        $timeline = $student->applications()->with('hostel')->latest()->take(4)->get();

        return view('student.dashboard', [
            'student' => $student,
            'allocation' => $allocation,
            'latestApplication' => $latestApplication,
            'notifications' => $notifications,
            'unreadCount' => $student->notifications()->unread()->count(),
            'timeline' => $timeline,
        ]);
    }
}
