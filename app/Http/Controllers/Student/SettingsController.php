<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('student.settings', ['student' => Auth::guard('web')->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'email_notifications' => ['nullable', 'boolean'],
            'sms_alerts' => ['nullable', 'boolean'],
            'maintenance_alerts' => ['nullable', 'boolean'],
        ]);

        $student = Auth::guard('web')->user();

        // Merge so any other keys already stored on the user (e.g. future
        // privacy preferences) are preserved, not wiped.
        $student->update([
            'settings' => array_merge($student->settings ?? [], [
                'email_notifications' => $request->boolean('email_notifications'),
                'sms_alerts' => $request->boolean('sms_alerts'),
                'maintenance_alerts' => $request->boolean('maintenance_alerts'),
            ]),
        ]);

        return back()->with('status', 'Settings saved.');
    }
}
