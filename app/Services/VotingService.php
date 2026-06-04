<?php

namespace App\Services;

use App\Models\User;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\AuditLog;
use Illuminate\Support\Carbon;

class VotingService
{
    public function canVote(User $user, Election $election): array
    {
        $errors = [];

        if (!$user->canVote()) {
            $errors[] = 'You do not have voting privileges.';
        }

        if ($user->status !== 'active') {
            $errors[] = 'Account is not active.';
        }

        if ($user->age < 18) {
            $errors[] = 'You must be 18 years or older to vote.';
        }

        if ($election->status !== 'active') {
            $errors[] = 'Voting is not currently open for this election.';
        }

        $now = Carbon::now();
        if ($now->lt($election->start_time)) {
            $errors[] = 'Voting has not started yet.';
        }
        if ($now->gt($election->end_time)) {
            $errors[] = 'Voting has already ended.';
        }

        if (Vote::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->exists()) {
            $errors[] = 'You have already voted in this election.';
        }

        return $errors;
    }

    public function castVote(User $user, Candidate $candidate, Election $election): Vote
    {
        $vote = Vote::create([
            'user_id' => $user->id,
            'candidate_id' => $candidate->id,
            'election_id' => $election->id,
            'timestamp' => Carbon::now(),
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'VOTE_CAST',
            'details' => "Voted in election '{$election->title_en}'",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return $vote;
    }

    public function computeResults(Election $election): array
    {
        $results = Vote::where('election_id', $election->id)
            ->selectRaw('candidate_id, COUNT(*) as total')
            ->groupBy('candidate_id')
            ->orderByDesc('total')
            ->get();

        $totalVotes = $results->sum('total');
        $ranked = [];

        foreach ($results as $index => $row) {
            $candidate = Candidate::with('user')->find($row->candidate_id);
            if (!$candidate) continue;
            $ranked[] = [
                'rank' => $index + 1,
                'candidate' => $candidate,
                'vote_count' => $row->total,
                'percentage' => $totalVotes > 0
                    ? round(($row->total / $totalVotes) * 100, 1)
                    : 0,
            ];
        }

        return [
            'total_votes' => $totalVotes,
            'candidates' => $ranked,
        ];
    }
}
