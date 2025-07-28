<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login'); // Make sure this matches your login view path
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt authentication
        if (Auth::attempt($request->only('email', 'password'))) {
            // Regenerate the session to prevent session fixation
            $request->session()->regenerate();

            // Redirect the user to the intended page or default to home
            return redirect()->intended(route('medecin.dashboard', )); // Adjust route name accordingly (replace 'dashboard' with your target route)
        }

        // Return the login view with an error message if authentication fails
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log the user out
        Auth::guard('web')->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect(route('login')); // Ensure this points to your login route
    }
}
