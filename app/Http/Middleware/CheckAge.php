<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\NidaService;

class CheckAge
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('nida_number')) {
            $nidaService = app(NidaService::class);
            $age = $nidaService->calculateAge($request->nida_number);

            if ($age === null) {
                return back()->withErrors(['nida_number' => 'Invalid NIDA number format or date of birth.'])->withInput();
            }

            if ($age < 18) {
                return back()->withErrors(['nida_number' => 'You must be 18 years or older to register.'])->withInput();
            }

            $request->merge(['_computed_age' => $age]);
        }

        return $next($request);
    }
}
