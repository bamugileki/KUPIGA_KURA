<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Election;
use Illuminate\Support\Carbon;

class CheckElectionStatus
{
    public function handle(Request $request, Closure $next)
    {
        $electionId = $request->route('election_id');
        if (!$electionId) {
            return $next($request);
        }

        $election = Election::find($electionId);
        if (!$election) {
            return redirect()->route('dashboard')->with('error', 'Election not found.');
        }

        if ($election->status !== 'voting_open') {
            return redirect()->route('dashboard')->with('warning', 'Voting is not currently open for this election.');
        }

        $now = Carbon::now();
        if ($now->lt($election->start_time)) {
            return redirect()->route('dashboard')->with('warning', 'Voting has not started yet.');
        }
        if ($now->gt($election->end_time)) {
            return redirect()->route('dashboard')->with('warning', 'Voting has already ended.');
        }

        return $next($request);
    }
}
