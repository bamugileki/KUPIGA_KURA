<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleVoter
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->canVote()) {
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. Voting privileges required.');
        }
        return $next($request);
    }
}
