<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('student.profile', ['student' => Auth::guard('web')->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $student = Auth::guard('web')->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$student->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'faculty' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $student->update($data);

        return back()->with('status', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $student = Auth::guard('web')->user();

        $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $student->update(['password' => Hash::make($request->password)]);

        return back()->with('status', 'Password changed successfully.');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $student = Auth::guard('web')->user();

        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        if ($student->avatar) {
            Storage::disk('public')->delete($student->avatar);
        }

        $path = $request->file('avatar')->store('avatars/students', 'public');
        $student->update(['avatar' => $path]);

        return back()->with('status', 'Profile photo updated.');
    }
}
