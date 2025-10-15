<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Log successful login
            try {
                AccessLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'login',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => 'POST',
                    'description' => 'Login realizado com sucesso',
                ]);
            } catch (\Exception $e) {
                // Don't fail the login if logging fails
                \Log::error('Failed to log login: ' . $e->getMessage());
            }

            return redirect()->intended(route('dashboard'));
        }

        // Log failed login attempt
        try {
            AccessLog::create([
                'user_id' => null,
                'action' => 'login_failed',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => 'POST',
                'description' => 'Tentativa de login falhou para: ' . $request->email,
            ]);
        } catch (\Exception $e) {
            // Don't fail the request if logging fails
        }

        throw ValidationException::withMessages([
            'email' => __('As credenciais fornecidas não correspondem aos nossos registros.'),
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        // Log logout before logging out
        try {
            AccessLog::create([
                'user_id' => auth()->id(),
                'action' => 'logout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => 'POST',
                'description' => 'Logout realizado',
            ]);
        } catch (\Exception $e) {
            // Don't fail the logout if logging fails
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
