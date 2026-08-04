<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        if (Auth::guard('web')->attempt([$field => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('student.dashboard'));
        }

        return back()
            ->withErrors(['login' => 'Those credentials do not match our records.'])
            ->onlyInput('login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
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

        User::create([
            'name' => $data['name'],
            'matric_number' => $data['matric_number'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'faculty' => $data['faculty'],
            'level' => $data['level'],
            'gender' => $data['gender'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('login')->with('status', 'Account created successfully! You can now log in.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
