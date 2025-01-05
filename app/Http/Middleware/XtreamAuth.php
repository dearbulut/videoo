<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XtreamAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('xtream_dns') || !session('xtream_username') || !session('xtream_password')) {
            return redirect()->route('login')->withErrors(['message' => 'Please login first']);
        }

        return $next($request);
    }
}