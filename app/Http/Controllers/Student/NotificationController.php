<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $student = Auth::guard('web')->user();

        return view('student.notifications', [
            'notifications' => $student->notifications()->get(),
        ]);
    }

    public function markAllRead(): RedirectResponse
    {
        Auth::guard('web')->user()->notifications()->unread()->update(['read_at' => now()]);

        return back()->with('status', 'All notifications marked as read.');
    }
}
