<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessibilityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->accessibility_enabled) {
                session([
                    'accessibility_mode' => $user->accessibility_mode,
                    'high_contrast' => $user->high_contrast,
                    'text_size' => $user->text_size,
                    'disability_type' => json_decode($user->disability_type ?? '[]', true),
                ]);
            } else {
                session([
                    'accessibility_mode' => 'normal',
                    'high_contrast' => false,
                    'text_size' => 'medium',
                    'disability_type' => [],
                ]);
            }
        }

        return $next($request);
    }
}
