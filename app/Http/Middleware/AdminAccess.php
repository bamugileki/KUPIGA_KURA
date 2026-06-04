<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->hasAdminAccess()) {
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. You do not have permission to view this page.');
        }
        return $next($request);
    }
}
