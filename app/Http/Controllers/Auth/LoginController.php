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
        \Log::info('Login attempt', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'has_token' => $request->has('_token'),
            'session_id' => $request->session()->getId(),
        ]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            \Log::info('Login successful', [
                'user_id' => auth()->id(),
                'email' => auth()->user()->email,
            ]);

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
        \Log::warning('Login failed', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
        ]);

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
