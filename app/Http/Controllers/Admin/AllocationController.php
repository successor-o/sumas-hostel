<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\HostelApplication;
use App\Models\Room;
use App\Models\SystemNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AllocationController extends Controller
{
    public function index(Request $request): View
    {
        $session = config('sumas.session');

        $query = Allocation::with(['user', 'hostel', 'room'])->where('session', $session);

        if ($search = $request->get('search')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($hostelId = $request->get('hostel_id')) {
            $query->where('hostel_id', $hostelId);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $allocations = $query->latest('allocated_at')->paginate(12)->withQueryString();

        // Approved applications that don't yet have an active allocation this session.
        $awaitingAllocation = HostelApplication::approved()
            ->where('session', $session)
            ->whereDoesntHave('allocation', fn ($q) => $q->where('status', 'active'))
            ->with(['user', 'hostel'])
            ->get();

        return view('admin.allocation', [
            'allocations' => $allocations,
            'awaitingAllocation' => $awaitingAllocation,
            'hostels' => \App\Models\Hostel::orderBy('name')->get(),
        ]);
    }

    /**
     * Returns available rooms for a given hostel as JSON, used by the
     * "New Allocation" modal to populate the room dropdown dynamically.
     */
    public function availableRooms(Request $request)
    {
        $request->validate(['hostel_id' => 'required|exists:hostels,id']);

        $rooms = Room::where('hostel_id', $request->hostel_id)
            ->where('status', '!=', 'maintenance')
            ->get()
            ->filter(fn ($room) => $room->availableBeds() > 0)
            ->map(fn ($room) => [
                'id' => $room->id,
                'label' => "Room {$room->room_number} ({$room->availableBeds()} open)",
                'available_beds' => $room->availableBeds(),
            ])
            ->values();

        return response()->json($rooms);
    }

    public function store(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $session = config('sumas.session');

        $data = $request->validate([
            'hostel_application_id' => ['required', 'exists:hostel_applications,id'],
            'room_id' => ['required', 'exists:rooms,id'],
        ]);

        $application = HostelApplication::with('user')->findOrFail($data['hostel_application_id']);
        $room = Room::findOrFail($data['room_id']);

        if ($room->availableBeds() <= 0) {
            return back()->withErrors(['room_id' => 'That room has no available beds.']);
        }

        $takenBeds = $room->activeAllocations()->pluck('bed_number')->all();
        $bedNumber = collect(range(1, $room->capacity))->first(fn ($b) => ! in_array($b, $takenBeds));

        $allocation = Allocation::create([
            'user_id' => $application->user_id,
            'hostel_id' => $room->hostel_id,
            'room_id' => $room->id,
            'hostel_application_id' => $application->id,
            'bed_number' => $bedNumber,
            'session' => $session,
            'status' => 'active',
            'allocated_by' => $admin->id,
            'allocated_at' => now(),
        ]);

        $room->refreshStatus();

        SystemNotification::create([
            'user_id' => $application->user_id,
            'title' => 'Room Allocated',
            'message' => "You have been assigned Room {$room->room_number}, Bed {$bedNumber} in {$room->hostel->name}.",
            'type' => 'success',
        ]);

        return back()->with('status', 'Room allocated successfully.');
    }

    public function vacate(Allocation $allocation): RedirectResponse
    {
        $allocation->update(['status' => 'vacated']);
        $allocation->room->refreshStatus();

        return back()->with('status', 'Allocation marked as vacated.');
    }
}
