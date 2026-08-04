<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelApplication;
use App\Models\SystemNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $session = config('sumas.session');

        $base = HostelApplication::where('session', $session)->with(['user', 'hostel']);

        if ($search = $request->get('search')) {
            $base->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('matric_number', 'like', "%{$search}%");
            });
        }

        if ($hostelId = $request->get('hostel_id')) {
            $base->where('hostel_id', $hostelId);
        }

        $sort = $request->get('sort', 'newest');
        $base->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc');

        return view('admin.applications', [
            'pending' => (clone $base)->pending()->paginate(10, ['*'], 'pending_page'),
            'approved' => (clone $base)->approved()->paginate(10, ['*'], 'approved_page'),
            'rejected' => (clone $base)->rejected()->paginate(10, ['*'], 'rejected_page'),
            'pendingCount' => HostelApplication::where('session', $session)->pending()->count(),
            'approvedCount' => HostelApplication::where('session', $session)->approved()->count(),
            'rejectedCount' => HostelApplication::where('session', $session)->rejected()->count(),
            'totalCount' => HostelApplication::where('session', $session)->count(),
            'hostels' => \App\Models\Hostel::orderBy('name')->get(),
        ]);
    }

    public function approve(HostelApplication $application): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $application->update([
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        SystemNotification::create([
            'user_id' => $application->user_id,
            'title' => 'Application Approved!',
            'message' => "Your hostel application for {$application->hostel->name} has been approved. Visit Hostel Allocation soon to see your assigned room.",
            'type' => 'success',
        ]);

        return back()->with('status', 'Application approved and ready for room allocation.');
    }

    public function reject(Request $request, HostelApplication $application): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $application->update([
            'status' => 'rejected',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => $data['rejection_reason'] ?? 'Did not meet hostel eligibility criteria.',
        ]);

        SystemNotification::create([
            'user_id' => $application->user_id,
            'title' => 'Application Rejected',
            'message' => 'Your hostel application was not approved this session. Reason: '.$application->rejection_reason,
            'type' => 'danger',
        ]);

        return back()->with('status', 'Application rejected.');
    }
}
