<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HostelController extends Controller
{
    public function index(): View
    {
        $hostels = Hostel::withCount('rooms')->orderBy('name')->get();

        return view('admin.hostels', ['hostels' => $hostels]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:hostels,name'],
            'category' => ['required', 'in:Male,Female,Postgraduate'],
            'warden' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hostels', 'public');
        }

        Hostel::create($data);

        return back()->with('status', 'New hostel block added.');
    }

    public function update(Request $request, Hostel $hostel): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:hostels,name,'.$hostel->id],
            'category' => ['required', 'in:Male,Female,Postgraduate'],
            'warden' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:Active,Under Maintenance,Closed'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            if ($hostel->image) {
                Storage::disk('public')->delete($hostel->image);
            }
            $data['image'] = $request->file('image')->store('hostels', 'public');
        }

        $hostel->update($data);

        return back()->with('status', 'Hostel details updated.');
    }

    public function destroy(Hostel $hostel): RedirectResponse
    {
        $hostel->delete();

        return back()->with('status', 'Hostel block deleted.');
    }
}
