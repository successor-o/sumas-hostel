<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Hostel;
use App\Models\SystemNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function create(): View
    {
        $student = Auth::guard('web')->user();
        $session = config('sumas.session');

        $existingApplication = $student->applications()->where('session', $session)->with('hostel')->first();

        $hostels = Hostel::where('status', 'Active')
            ->where('category', $student->gender === 'Male' ? 'Male' : ($student->gender === 'Female' ? 'Female' : 'Postgraduate'))
            ->orWhere('category', 'Postgraduate')
            ->with('rooms')
            ->get()
            ->filter(fn ($h) => $h->category === $student->gender || $h->category === 'Postgraduate')
            ->values();

        return view('student.application', [
            'student' => $student,
            'hostels' => $hostels,
            'existingApplication' => $existingApplication,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $student = Auth::guard('web')->user();
        $session = config('sumas.session');

        $data = $request->validate([
            'hostel_id' => ['required', 'exists:hostels,id'],
            'reason' => ['required', 'string', 'max:255'],
            'preferred_roommate' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'agree_rules' => ['accepted'],
        ], [
            'agree_rules.accepted' => 'You must agree to the hostel rules before submitting.',
        ]);

        $application = $student->applications()->updateOrCreate(
            ['session' => $session],
            [
                'hostel_id' => $data['hostel_id'],
                'reason' => $data['reason'],
                'preferred_roommate' => $data['preferred_roommate'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]
        );

        Admin::each(function ($admin) use ($student, $application) {
            SystemNotification::create([
                'admin_id' => $admin->id,
                'title' => 'New Application Received',
                'message' => "{$student->name} ({$student->matric_number}) has submitted a hostel application for {$application->hostel->name}.",
                'type' => 'info',
            ]);
        });

        return redirect()->route('student.application')->with('status', 'Application submitted! You can track its status from your dashboard.');
    }
}
