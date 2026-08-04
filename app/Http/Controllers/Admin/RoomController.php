<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $query = Room::query()->with(['hostel', 'activeAllocations.user']);

        if ($search = $request->get('search')) {
            $query->where('room_number', 'like', "%{$search}%");
        }

        if ($hostelId = $request->get('hostel_id')) {
            $query->where('hostel_id', $hostelId);
        }

        if ($floor = $request->get('floor')) {
            $query->where('floor', $floor);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $rooms = $query->orderBy('room_number')->paginate(8)->withQueryString();

        return view('admin.rooms', [
            'rooms' => $rooms,
            'hostels' => Hostel::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hostel_id' => ['required', 'exists:hostels,id'],
            'room_number' => ['required', 'string', 'max:20'],
            'floor' => ['required', 'string', 'max:50'],
            'capacity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $data['status'] = 'vacant';

        Room::create($data);

        return back()->with('status', 'New room added successfully.');
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $data = $request->validate([
            'room_number' => ['required', 'string', 'max:20'],
            'capacity' => ['required', 'integer', 'min:1', 'max:10'],
            'status' => ['required', 'in:vacant,partial,full,maintenance'],
        ]);

        $room->update($data);

        return back()->with('status', 'Room details updated.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        return back()->with('status', 'Room deleted.');
    }
}
