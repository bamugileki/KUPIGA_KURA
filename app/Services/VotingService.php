<?php

namespace App\Services;

use App\Models\User;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\AuditLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

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

        $voteHash = hash('sha256', $user->id . '_' . $election->id . '_' . config('app.key'));
        if (Vote::where('vote_hash', $voteHash)->exists()) {
            $errors[] = 'You have already voted in this election.';
        }

        return $errors;
    }

    public function castVote(User $user, Candidate $candidate, Election $election): Vote
    {
        $voteHash = hash('sha256', $user->id . '_' . $election->id . '_' . config('app.key'));

        $vote = Vote::create([
            'user_id' => $user->id,
            'candidate_id' => $candidate->id,
            'election_id' => $election->id,
            'timestamp' => Carbon::now(),
            'vote_hash' => $voteHash,
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
        $votes = Vote::where('election_id', $election->id)->get();

        $tally = [];
        foreach ($votes as $vote) {
            $candidateId = $vote->candidate_id;
            if (!isset($tally[$candidateId])) {
                $tally[$candidateId] = 0;
            }
            $tally[$candidateId]++;
        }
        arsort($tally);

        $totalVotes = count($votes);
        $ranked = [];
        $rank = 1;

        foreach ($tally as $candidateId => $count) {
            $candidate = Candidate::with('user')->find($candidateId);
            if (!$candidate) continue;
            $ranked[] = [
                'rank' => $rank++,
                'candidate' => $candidate,
                'vote_count' => $count,
                'percentage' => $totalVotes > 0
                    ? round(($count / $totalVotes) * 100, 1)
                    : 0,
            ];
        }

        return [
            'total_votes' => $totalVotes,
            'candidates' => $ranked,
        ];
    }
}
