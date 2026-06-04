<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Constituency;
use App\Models\PoliticalParty;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $electionId = $request->query('election_id');
        $election = null;
        $candidates = [];

        if ($electionId) {
            $election = Election::findOrFail($electionId);

            if (!$election->isVisible()) {
                return redirect()->route('dashboard')
                    ->with('error', 'This election is not currently available.');
            }

            $candidates = Candidate::where('election_id', $electionId)
                ->where('status', 'approved')
                ->with('user')
                ->get();
        }

        return view('candidates', compact('election', 'candidates'));
    }

    public function apply()
    {
        $user = auth()->user();

        if ($user->is_candidate || Candidate::where('user_id', $user->id)->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'You are already registered as a candidate.');
        }

        $elections = Election::whereIn('status', ['nomination_open', 'published', 'campaign_period'])
            ->orderBy('start_time', 'desc')
            ->get();

        $parties = PoliticalParty::where('status', 'active')->get();
        $constituencies = Constituency::orderBy('name')->get();

        return view('candidates.apply', compact('elections', 'parties', 'constituencies'));
    }

    public function storeApplication(Request $request)
    {
        $user = auth()->user();

        if ($user->is_candidate || Candidate::where('user_id', $user->id)->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'You are already registered as a candidate.');
        }

        $validated = $request->validate([
            'election_id' => 'required|integer|exists:elections,id',
            'position' => 'required|in:presidential,parliamentary,councillor',
            'party_id' => 'required|integer|exists:political_parties,id',
            'constituency_id' => 'required_if:position,parliamentary,councillor|nullable|integer|exists:constituencies,id',
            'manifesto' => 'nullable|string|max:5000',
            'biography' => 'nullable|string|max:5000',
            'education' => 'nullable|string|max:5000',
            'political_experience' => 'nullable|string|max:5000',
            'terms_accepted' => 'required|accepted',
        ]);

        $election = Election::findOrFail($validated['election_id']);

        if ($election->election_type !== $validated['position']) {
            return back()->with('error', 'Selected position does not match the election type.')
                ->withInput();
        }

        $party = PoliticalParty::findOrFail($validated['party_id']);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'election_id' => $validated['election_id'],
            'position' => $validated['position'],
            'full_name' => $user->full_name,
            'gender' => $user->gender ?? null,
            'date_of_birth' => $user->date_of_birth ?? null,
            'nationality' => 'Tanzanian',
            'phone' => $user->phone,
            'email' => $user->email,
            'nida_number' => $user->nida_number,
            'party_id' => $validated['party_id'],
            'party_name' => $party->name,
            'party_abbreviation' => $party->abbreviation,
            'constituency_id' => $validated['constituency_id'] ?? null,
            'manifesto' => $validated['manifesto'] ?? null,
            'biography' => $validated['biography'] ?? null,
            'education' => $validated['education'] ?? null,
            'political_experience' => $validated['political_experience'] ?? null,
            'status' => 'pending',
            'terms_accepted' => true,
            'nomination_submitted_at' => Carbon::now(),
        ]);

        $user->update(['is_candidate' => true]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'candidate_applied',
            'details' => "Applied as {$validated['position']} candidate for election #{$validated['election_id']}",
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Your candidate application has been submitted for review.');
    }
}
