<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    protected int $timeoutSeconds = 6000;

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        // 🔹 Handle session/ping separately
        if ($request->is('session/ping')) {
            session(['last_activity_time' => time()]);
            return response()->json(['status' => 'ok']);
        }

        $lastActivity = session('last_activity_time');

        // ✅ Fix: handle both Carbon objects (old) and int (new)
        if ($lastActivity) {
            $lastActivityInt = is_int($lastActivity)
                ? $lastActivity
                : (int) $lastActivity->timestamp; // Carbon → int

            if ((time() - $lastActivityInt) >= $this->timeoutSeconds) {
                $this->logoutUser($request);

                if ($request->expectsJson()) {
                    return response()->json(['timeout' => true], 401);
                }

                return redirect()->route('login')->withErrors([
                    'login' => 'Session expired due to inactivity. Please login again.',
                ]);
            }
        }

        // ✅ Always store as plain int from now on
        session(['last_activity_time' => time()]);

        return $next($request);
    }

    protected function logoutUser(Request $request): void
    {
        $user = Auth::user();

        if ($user) {
            $user->update([
                'is_loged'      => 0,
                'last_login_at' => now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}