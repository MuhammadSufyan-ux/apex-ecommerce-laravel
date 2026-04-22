<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        if ($request->user()->status === 'blocked') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'email' => 'SECURITY ALERT: This account has been protocol-blocked. Contact system administration.',
            ]);
        }

        $request->session()->regenerate();

        // Update last login and log activity
        $user = auth()->user();
        $user->update(['last_login_at' => now()]);
        
        if ($user->role === 'admin') {
            \App\Models\ActivityLog::log('auth', 'login', "Administrator '{$user->name}' initiated a secure session.");
            return redirect()->intended(route('dashboard', absolute: false));
        } else {
            \App\Models\ActivityLog::log('auth', 'login', "Customer '{$user->name}' logged into the portal.");
        }

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
