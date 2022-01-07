<?php

namespace App\Http\Middleware;

use Str;
use Closure;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->banned_until && now()->lessThan(auth()->user()->banned_until)) {
            $banned_days = now()->diffInDays(auth()->user()->banned_until);
            auth()->logout();

            if ($banned_days > 14) {
                $message = 'Your account has been suspended. Please contact administrator';
            } else {
                $message = 'Your account has been suspended for ' . $banned_days . ' ' . Str::plural('day', $banned_days) . '. Please contact administrator';
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'data' => [], 'message' => $message], 200);
            } else {
                return redirect()->route('login')->withMessage($message);
            }
        }

        return $next($request);
    }
}
