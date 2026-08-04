<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'institution_name' => ['nullable', 'string', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'academic_session' => ['nullable', 'string', 'max:50'],
        ]);

        // Only persist the fields that were actually submitted (each tab
        // posts independently). Saved values override config('sumas.*')
        // at boot, so the whole app picks them up immediately.
        if ($request->filled('institution_name')) {
            Setting::set('institution_name', $request->institution_name);
        }

        if ($request->filled('support_email')) {
            Setting::set('support_email', $request->support_email);
        }

        if ($request->filled('academic_session')) {
            Setting::set('session', $request->academic_session);
        }

        // Reflect the changes immediately (the next request picks them up at
        // boot anyway, but the config is already loaded in this one).
        Setting::applyToConfig();

        return back()->with('status', 'Settings saved.');
    }
}
