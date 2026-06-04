<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;

class CheckAccountLock
{
    public function handle(Request $request, Closure $next)
    {
        $identifier = $request->input('identifier');
        if (!$identifier) {
            return $next($request);
        }

        $user = User::where('nida_number', $identifier)
            ->orWhere('driving_licence', $identifier)
            ->orWhere('nhif_number', $identifier)
            ->first();

        if ($user && $user->isLocked()) {
            return back()->withErrors([
                'identifier' => 'Account is locked due to too many failed login attempts. Please try again later.',
            ]);
        }

        return $next($request);
    }
}
