<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isBanned()) {
            $user = Auth::user();
            $message = 'Akun Anda telah di-banned permanen oleh Admin karena pelanggaran berat.';
            
            if (!$user->is_banned && $user->banned_until) {
                $message = 'Akun Anda sedang dalam masa Suspend hingga ' . $user->banned_until->format('d M Y H:i') . ' WIB.';
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', $message);
        }

        return $next($request);
    }
}
