<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('shared.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account has been deactivated.',
                ]);
            }

            $request->session()->regenerate();

            return $this->redirectBasedOnRole($user);
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Helper to redirect user based on role.
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->isSupervisor()) {
            return redirect()->intended(route('supervisor.dashboard'));
        }

        if ($user->isMarketing()) {
            return redirect()->intended(route('marketing.orders.index'));
        }

        return redirect()->to('/');
    }
}
