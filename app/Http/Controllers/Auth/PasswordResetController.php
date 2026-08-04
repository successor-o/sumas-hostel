<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Handles "forgot password" for both the student ("users") and admin
 * ("admins") brokers. No mail transport is configured out of the box
 * (MAIL_MAILER=log), so in local development the reset link is written to
 * storage/logs/laravel.log instead of actually being emailed.
 */
class PasswordResetController extends Controller
{
    public function showForgot(Request $request): View
    {
        $guard = $request->routeIs('admin.*') ? 'admin' : 'student';

        return view('auth.forgot-password', ['guard' => $guard]);
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $guard = $request->routeIs('admin.*') ? 'admin' : 'student';
        $broker = $guard === 'admin' ? 'admins' : 'users';

        $request->validate(['email' => 'required|email']);

        Password::broker($broker)->sendResetLink(
            $request->only('email')
        );

        // Always respond the same way whether or not the email exists, to
        // avoid leaking which addresses are registered.
        return back()->with('status', 'If that email is registered, a reset link has been generated.');
    }

    public function showReset(Request $request, string $token): View
    {
        $guard = $request->routeIs('admin.*') ? 'admin' : 'student';

        return view('auth.reset-password', [
            'guard' => $guard,
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $guard = $request->routeIs('admin.*') ? 'admin' : 'student';
        $broker = $guard === 'admin' ? 'admins' : 'users';
        $loginRoute = $guard === 'admin' ? 'admin.login' : 'login';

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::broker($broker)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route($loginRoute)->with('status', 'Your password has been reset. You can now log in.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
