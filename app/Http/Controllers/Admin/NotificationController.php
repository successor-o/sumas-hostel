<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.notifications', [
            'notifications' => $admin->notifications()->get(),
        ]);
    }

    public function markAllRead(): RedirectResponse
    {
        Auth::guard('admin')->user()->notifications()->unread()->update(['read_at' => now()]);

        return back()->with('status', 'All notifications marked as read.');
    }
}
