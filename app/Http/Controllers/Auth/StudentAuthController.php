<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Please enter your matric number or email.',
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'matric_number';

        $user = User::where($field, $credentials['login'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['login' => 'Those credentials do not match our records.'])
                ->onlyInput('login');
        }

        if ($user->status !== 'approved') {
            return back()
                ->withErrors(['login' => 'Your account is ' . $user->status . '. Please contact the hostel office.'])
                ->onlyInput('login');
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('student.dashboard'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function checkStatus(Request $request): View
    {
        $login = $request->get('login');
        $user = null;
        $statusMessage = null;

        if ($login) {
            $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'matric_number';
            $user = User::where($field, $login)->first();

            if ($user) {
                $statusMessage = match($user->status) {
                    'pending' => 'Your account is pending approval by the hostel office.',
                    'approved' => 'Your account is approved. You can now log in.',
                    'rejected' => 'Your account has been rejected. Please contact the hostel office.',
                    default => 'Account status unknown.',
                };
            } else {
                $statusMessage = 'No account found with this matric number or email.';
            }
        }

        return view('auth.register', [
            'statusCheck' => $login,
            'statusMessage' => $statusMessage,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'matric_number' => ['required', 'string', 'max:50', 'unique:users,matric_number'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'faculty' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'in:Male,Female'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'You must agree to the hostel rules to continue.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'matric_number' => $data['matric_number'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'faculty' => $data['faculty'],
            'level' => $data['level'],
            'gender' => $data['gender'],
            'password' => Hash::make($data['password']),
            'status' => 'pending',
        ]);

        Admin::each(function ($admin) use ($user) {
            SystemNotification::create([
                'admin_id' => $admin->id,
                'title' => 'New Student Registration',
                'message' => "{$user->name} ({$user->matric_number}) has registered for a student account. Please review and approve.",
                'type' => 'info',
            ]);
        });

        return redirect()->route('login')->with('status', 'Account created successfully! Your registration is pending admin approval.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
