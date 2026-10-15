<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\AdminUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $admin = AdminUser::query()->where('admin_email', $credentials['email'])->first();

        // password_verify accepts both $2y$ and $2b$ bcrypt hashes stored by the legacy admin.
        if (! $admin || ! password_verify($credentials['password'], (string) $admin->admin_password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid email or password.']);
        }

        if (! array_key_exists($admin->admin_role, config('admin.roles'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'You are not authorized to access the admin panel.']);
        }

        Auth::guard('admin')->login($admin, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        // Only the admin guard is signed out; a member logged in on the
        // public site in the same browser stays signed in.
        Auth::guard('admin')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }
}
