<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.profile', ['admin' => Auth::guard('admin')->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email,'.$admin->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $admin->update($data);

        return back()->with('status', 'Profile information updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $admin->update(['password' => Hash::make($request->password)]);

        return back()->with('status', 'Password changed successfully.');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $request->validate(['avatar' => ['required', 'image', 'max:2048']]);

        if ($admin->avatar) {
            Storage::disk('public')->delete($admin->avatar);
        }

        $path = $request->file('avatar')->store('avatars/admins', 'public');
        $admin->update(['avatar' => $path]);

        return back()->with('status', 'Profile photo updated.');
    }
}
