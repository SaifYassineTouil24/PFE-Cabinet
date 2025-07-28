<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LoginController extends Controller
{
    private const ROLE_ADMIN = 'admin';
    private const ROLE_NURSE = 'nurse';

    public function authenticated(Request $request, $user)
    {
        return $this->redirectBasedOnRole($user);
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->role === self::ROLE_ADMIN) {
            return redirect()->route('medecin.dashboard');
        }

        if ($user->role === self::ROLE_NURSE) {
            return redirect()->route('appointments.index');
        }

        return redirect()->route('medecin.dashboard'); // Par défaut, redirection vers une page générale
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout the user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect()->route('login'); // Redirect to login page
    }
}
