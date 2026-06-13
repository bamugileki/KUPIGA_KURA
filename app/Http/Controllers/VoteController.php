<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\AssistedVote;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\VotingService;
use App\Services\FraudDetectionService;
use App\Services\NidaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class VoteController extends Controller
{
    protected VotingService $votingService;
    protected FraudDetectionService $fraudService;
    protected NidaService $nidaService;

    public function __construct(VotingService $votingService, FraudDetectionService $fraudService, NidaService $nidaService)
    {
        $this->votingService = $votingService;
        $this->fraudService = $fraudService;
        $this->nidaService = $nidaService;
    }

    public function showVoteForm($electionId)
    {
        $election = Election::findOrFail($electionId);
        $user = Auth::user();

        $voteHash = hash('sha256', $user->id . '_' . $electionId . '_' . config('app.key'));
        $existingVote = Vote::where('vote_hash', $voteHash)->first();

        if ($existingVote) {
            $candidate = Candidate::with('user')->find($existingVote->candidate_id);
            session()->flash('vote_receipt', (object)[
                'vote_id' => $existingVote->id,
                'election_title_en' => $election->title_en,
                'election_title_sw' => $election->title_sw,
                'candidate_name' => $candidate ? ($candidate->full_name ?? $candidate->user->full_name) : 'Unknown',
                'voted_at' => $existingVote->timestamp->format('d M Y H:i:s'),
            ]);
            return view('vote', ['election' => $election, 'candidates' => collect([])]);
        }

        $errors = $this->votingService->canVote($user, $election);
        if (!empty($errors)) {
            return redirect()->route('dashboard')->withErrors($errors);
        }

        $candidates = Candidate::where('election_id', $electionId)
            ->where('status', 'approved')
            ->with('user')
            ->get();

        $position = \App\Models\Position::where('slug', $election->election_type)->first();
        if ($position && $position->requires_constituency) {
            $candidates = $candidates->filter(function($c) use ($user) {
                return $c->constituency_id == $user->constituency_id;
            });
        }

        if ($candidates->isEmpty()) {
            return redirect()->route('dashboard')->with('warning', __t('no_candidates_available'));
        }

        return view('vote', compact('election', 'candidates'));
    }

    public function castVote(Request $request, $electionId)
    {
        $election = Election::findOrFail($electionId);
        $user = Auth::user();

        $errors = $this->votingService->canVote($user, $election);
        if (!empty($errors)) {
            return redirect()->route('dashboard')->withErrors($errors);
        }

        $request->validate([
            'candidate_id' => 'required|integer|exists:candidates,id',
        ]);

        $candidate = Candidate::where('id', $request->candidate_id)
            ->where('election_id', $electionId)
            ->where('status', 'approved')
            ->first();

        if (!$candidate) {
            return back()->with('error', 'Invalid candidate selected.');
        }

        $vote = $this->votingService->castVote($user, $candidate, $election);

        session()->flash('vote_receipt', (object)[
            'vote_id' => $vote->id,
            'election_title_en' => $election->title_en,
            'election_title_sw' => $election->title_sw,
            'candidate_name' => $candidate->full_name ?? $candidate->user->full_name,
            'voted_at' => $vote->timestamp->format('d M Y H:i:s'),
        ]);

        return redirect()->route('vote.form', $election->id);
    }

    public function showAssistedVoteForm(Request $request, $electionId)
    {
        $election = Election::findOrFail($electionId);
        $user = Auth::user();

        $voterId = $request->query('voter');

        if ($voterId) {
            $voter = User::findOrFail($voterId);

            if ($voter->id === $user->id) {
                return back()->with('error', 'You cannot assist yourself.');
            }

            if ($voter->status !== 'active') {
                return back()->with('error', 'The voter account is not active.');
            }

            $existingAssistedVote = AssistedVote::where('voter_id', $voter->id)
                ->where('election_id', $electionId)
                ->first();

            if ($existingAssistedVote) {
                return back()->with('error', 'This voter has already received assistance in this election.');
            }

            $voteHash = hash('sha256', $voter->id . '_' . $electionId . '_' . config('app.key'));
            $existingVote = Vote::where('vote_hash', $voteHash)->first();

            if ($existingVote) {
                return back()->with('error', 'This voter has already voted in this election.');
            }

            $candidates = Candidate::where('election_id', $electionId)
                ->where('status', 'approved')
                ->with('user')
                ->get();

            $position = \App\Models\Position::where('slug', $election->election_type)->first();
            if ($position && $position->requires_constituency) {
                $candidates = $candidates->filter(function($c) use ($voter) {
                    return $c->constituency_id == $voter->constituency_id;
                });
            }

            if ($candidates->isEmpty()) {
                return redirect()->route('dashboard')->with('warning', __t('no_candidates_available'));
            }

            return view('vote_assisted', compact('election', 'candidates', 'voter'));
        }

        // Step 1: Look up voter by identifier
        return view('vote_assisted_lookup', compact('election'));
    }

    public function lookupVoter(Request $request, $electionId)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $election = Election::findOrFail($electionId);
        $identifier = $request->identifier;

        $voter = User::where('nida_number', $identifier)
            ->orWhere('driving_licence', $identifier)
            ->orWhere('nhif_number', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$voter) {
            return back()->with('error', 'Voter not found with the provided identifier.')->withInput();
        }

        if ($voter->id === Auth::id()) {
            return back()->with('error', 'You cannot assist yourself.');
        }

        if ($voter->status !== 'active') {
            return back()->with('error', 'The voter account is not active.');
        }

        if ($voter->age < 18) {
            return back()->with('error', 'The voter must be 18 years or older.');
        }

        $existingAssistedVote = AssistedVote::where('voter_id', $voter->id)
            ->where('election_id', $electionId)
            ->first();

        if ($existingAssistedVote) {
            return back()->with('error', 'This voter has already received assistance in this election.');
        }

        $voteHash = hash('sha256', $voter->id . '_' . $electionId . '_' . config('app.key'));
        $existingVote = Vote::where('vote_hash', $voteHash)->first();

        if ($existingVote) {
            return back()->with('error', 'This voter has already voted in this election.');
        }

        return redirect()->route('vote.assisted', ['election' => $electionId, 'voter' => $voter->id]);
    }

    public function castAssistedVote(Request $request, $electionId)
    {
        $election = Election::findOrFail($electionId);
        $user = Auth::user();

        $request->validate([
            'voter_id' => 'required|integer|exists:users,id',
            'candidate_id' => 'required|integer|exists:candidates,id',
            'assistant_name' => 'required|string|max:100',
            'assistant_relationship' => 'nullable|string|max:100',
            'voter_consent' => 'required|accepted',
        ]);

        $voter = User::findOrFail($request->voter_id);

        if ($voter->id === $user->id) {
            return back()->with('error', 'You cannot assist yourself.');
        }

        if ($voter->status !== 'active') {
            return back()->with('error', 'The voter account is not active.');
        }

        $errors = $this->votingService->canVote($voter, $election);
        if (!empty($errors)) {
            return redirect()->route('dashboard')->withErrors($errors);
        }

        $existingAssisted = AssistedVote::where('voter_id', $voter->id)
            ->where('election_id', $electionId)
            ->first();

        if ($existingAssisted) {
            return back()->with('error', 'This voter has already received assistance in this election.');
        }

        $candidate = Candidate::where('id', $request->candidate_id)
            ->where('election_id', $electionId)
            ->where('status', 'approved')
            ->first();

        if (!$candidate) {
            return back()->with('error', 'Invalid candidate selected.');
        }

        $position = \App\Models\Position::where('slug', $election->election_type)->first();
        if ($position && $position->requires_constituency) {
            if ($candidate->constituency_id != $voter->constituency_id) {
                return back()->with('error', 'Candidate does not belong to the voter constituency.');
            }
        }

        // Cast the vote on behalf of the voter
        $vote = $this->votingService->castVote($voter, $candidate, $election);

        // Record the assisted vote
        AssistedVote::create([
            'voter_id' => $voter->id,
            'assistant_id' => $user->id,
            'election_id' => $electionId,
            'candidate_id' => $candidate->id,
            'assistant_name' => $request->assistant_name,
            'assistant_relationship' => $request->assistant_relationship,
            'voter_consent' => true,
            'created_at' => Carbon::now(),
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'ASSISTED_VOTE_CAST',
            'details' => "Assistant {$user->full_name} cast vote for voter {$voter->full_name} in election '{$election->title_en}'",
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        session()->flash('vote_receipt', (object)[
            'vote_id' => $vote->id,
            'election_title_en' => $election->title_en,
            'election_title_sw' => $election->title_sw,
            'candidate_name' => $candidate->full_name ?? $candidate->user->full_name,
            'voted_at' => $vote->timestamp->format('d M Y H:i:s'),
            'assisted_voter' => $voter->full_name,
        ]);

        return redirect()->route('dashboard')->with('success', 'Assisted vote cast successfully on behalf of ' . $voter->full_name);
    }
}
