<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AllocationController extends Controller
{
    public function show(): View
    {
        $student = Auth::guard('web')->user();

        $allocation = $student->allocations()
            ->where('status', 'active')
            ->with(['hostel', 'room'])
            ->latest('allocated_at')
            ->first();

        $roommates = collect();

        if ($allocation) {
            $roommates = $allocation->room->activeAllocations()
                ->where('user_id', '!=', $student->id)
                ->with('user')
                ->get()
                ->pluck('user');
        }

        return view('student.allocation', [
            'student' => $student,
            'allocation' => $allocation,
            'roommates' => $roommates,
        ]);
    }
}
