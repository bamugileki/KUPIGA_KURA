<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Vote;
use App\Models\AssistedVote;
use App\Models\AuditLog;
use App\Models\SuspiciousLog;
use App\Models\Setting;
use App\Models\Constituency;
use App\Models\Objection;
use App\Models\CodeConductViolation;
use App\Models\PoliticalParty;
use App\Models\NominationSupport;
use App\Models\Position;
use App\Models\AccessibilityLog;
use App\Services\VotingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    protected VotingService $votingService;

    public function __construct(VotingService $votingService)
    {
        $this->votingService = $votingService;
    }

    public function dashboard()
    {
        $now = Carbon::now();

        // Users & Voters
        $totalUsers = User::count();
        $newRegistrationsToday = User::whereDate('created_at', $now->toDateString())->count();
        $verifiedUsers = User::where('is_verified', true)->count();
        $unverifiedUsers = User::where('is_verified', false)->count();
        $suspendedAccounts = User::where('status', 'rejected')->orWhere(function($q) {
            $q->whereNotNull('locked_until')->where('locked_until', '>', Carbon::now());
        })->count();

        // Elections Overview
        $activeElections = Election::where('status', 'active')->where('start_time', '<=', $now)->where('end_time', '>=', $now)->count();
        $upcomingElections = Election::where('status', 'published')->count();
        $completedElections = Election::where('status', 'closed')->count();
        $draftElections = Election::where('status', 'draft')->count();
        $electionsByStatus = [
            'live' => $activeElections,
            'upcoming' => $upcomingElections,
            'completed' => $completedElections,
            'draft' => $draftElections,
        ];

        // Candidates
        $totalCandidates = Candidate::count();
        $pendingCandidates = Candidate::where('status', 'pending')->count();
        $approvedCandidates = Candidate::where('status', 'approved')->count();
        $rejectedCandidates = Candidate::where('status', 'rejected')->count();

        // Voting Activity
        $totalVotes = Vote::count();
        $totalEligible = User::where('status', 'active')->count();
        $turnoutPercentage = $totalEligible > 0 ? round(($totalVotes / $totalEligible) * 100, 1) : 0;

        $votesPerElection = Vote::selectRaw('election_id, count(*) as total')
            ->groupBy('election_id')
            ->with('election')
            ->get()
            ->map(fn($v) => [
                'election' => $v->election->title_en ?? 'Unknown',
                'total' => $v->total,
            ]);

        // Security Alerts
        $loginAttemptsToday = AuditLog::where('action', 'LOGIN_FAILED')
            ->whereDate('timestamp', $now->toDateString())->count();
        $suspiciousActivities = SuspiciousLog::whereDate('timestamp', $now->toDateString())->count();
        $duplicateDetections = AuditLog::where('action', 'DUPLICATE_DETECTED')
            ->whereDate('timestamp', $now->toDateString())->count();
        $failedAuthToday = AuditLog::where('action', 'LOGIN_FAILED')
            ->whereDate('timestamp', $now->toDateString())->count();

        $securityAlerts = [
            'login_attempts' => $loginAttemptsToday,
            'suspicious_activities' => $suspiciousActivities,
            'duplicate_detections' => $duplicateDetections,
            'failed_auth' => $failedAuthToday,
        ];

        // Recent Activity
        $recentLogs = AuditLog::latest()->take(10)->get();

        // System Status
        $systemStatus = $suspiciousActivities > 5 || $failedAuthToday > 10 ? 'alert' : 'safe';
        session(['system_status' => $systemStatus]);

        return view('admin.dashboard', compact(
            'totalUsers', 'newRegistrationsToday', 'verifiedUsers', 'unverifiedUsers', 'suspendedAccounts',
            'electionsByStatus', 'activeElections', 'upcomingElections', 'completedElections', 'draftElections',
            'totalCandidates', 'pendingCandidates', 'approvedCandidates', 'rejectedCandidates',
            'totalVotes', 'totalEligible', 'turnoutPercentage', 'votesPerElection',
            'securityAlerts',
            'recentLogs', 'systemStatus'
        ));
    }

    public function users(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $query = User::query();
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        $users = $query->latest()->get();
        return view('admin.users', compact('users', 'statusFilter'));
    }

    public function approveUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->status = 'active';
        $user->is_verified = true;
        $user->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'USER_APPROVED',
            'details' => "Approved user {$user->full_name} (ID: {$user->id})",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'User has been approved successfully.');
    }

    public function rejectUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->status = 'rejected';
        $user->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'USER_REJECTED',
            'details' => "Rejected user {$user->full_name} (ID: {$user->id})",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'User has been rejected.');
    }

    public function createUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'full_name' => 'required|string|max:100',
                'email' => 'required|email|max:120|unique:users,email',
                'phone' => ['nullable', 'regex:/^(?:\+255[67]\d{8}|0[67]\d{8})$/'],
                'nida_number' => 'nullable|string|max:25|unique:users,nida_number',
                'driving_licence' => 'nullable|string|max:30|unique:users,driving_licence',
                'nhif_number' => 'nullable|string|max:20|unique:users,nhif_number',
                'password' => 'required|string|min:8',
                'role' => 'required|in:voter,candidate,admin,officer,observer',
                'status' => 'required|in:pending,active,rejected',
                'age' => 'nullable|integer|min:18',
            ], [
                'phone.regex' => 'Phone must be in Tanzania format (07XXXXXXXX, 06XXXXXXXX, +2557XXXXXXXX, +2556XXXXXXXX)',
            ]);

            $roleFlags = $this->mapRoleToFlags($request->role);

            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'nida_number' => $request->nida_number,
                'driving_licence' => $request->driving_licence,
                'nhif_number' => $request->nhif_number,
                'password' => $request->password,
                'role' => $request->role,
                'is_voter' => $roleFlags['is_voter'],
                'is_candidate' => $roleFlags['is_candidate'],
                'is_admin' => $roleFlags['is_admin'],
                'is_officer' => $roleFlags['is_officer'],
                'is_observer' => $roleFlags['is_observer'],
                'status' => $request->status,
                'language' => 'en',
                'age' => $request->age,
                'is_verified' => $request->status === 'active',
                'accessibility_enabled' => $request->boolean('accessibility_enabled'),
                'accessibility_mode' => $request->input('accessibility_mode', 'normal'),
                'high_contrast' => $request->boolean('high_contrast'),
                'text_size' => $request->input('text_size', 'medium'),
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'USER_CREATED',
                'details' => "Created user {$user->full_name} with role {$request->role}",
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);

            return redirect()->route('admin.users')->with('success', 'User has been created successfully.');
        }

        return view('admin.user_form');
    }

    public function editUser(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        if ($request->isMethod('post')) {
            $request->validate([
                'full_name' => 'required|string|max:100',
                'email' => 'required|email|max:120|unique:users,email,' . $userId,
                'phone' => ['nullable', 'regex:/^(?:\+255[67]\d{8}|0[67]\d{8})$/'],
                'nida_number' => 'nullable|string|max:25|unique:users,nida_number,' . $userId,
                'driving_licence' => 'nullable|string|max:30|unique:users,driving_licence,' . $userId,
                'nhif_number' => 'nullable|string|max:20|unique:users,nhif_number,' . $userId,
                'role' => 'required|in:voter,candidate,admin,officer,observer',
                'status' => 'required|in:pending,active,rejected',
                'age' => 'nullable|integer|min:18',
            ], [
                'phone.regex' => 'Phone must be in Tanzania format (07XXXXXXXX, 06XXXXXXXX, +2557XXXXXXXX, +2556XXXXXXXX)',
            ]);

            $roleFlags = $this->mapRoleToFlags($request->role);

            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->nida_number = $request->nida_number;
            $user->driving_licence = $request->driving_licence;
            $user->nhif_number = $request->nhif_number;
            $user->role = $request->role;
            $user->is_voter = $roleFlags['is_voter'];
            $user->is_candidate = $roleFlags['is_candidate'];
            $user->is_admin = $roleFlags['is_admin'];
            $user->is_officer = $roleFlags['is_officer'];
            $user->is_observer = $roleFlags['is_observer'];
            $user->status = $request->status;
            $user->age = $request->age;
            $user->is_verified = $request->status === 'active';
            $user->accessibility_enabled = $request->boolean('accessibility_enabled');
            $user->accessibility_mode = $request->input('accessibility_mode', 'normal');
            $user->high_contrast = $request->boolean('high_contrast');
            $user->text_size = $request->input('text_size', 'medium');
            $user->save();

            if ($request->filled('password')) {
                $user->password = $request->password;
                $user->save();
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'USER_UPDATED',
                'details' => "Updated user {$user->full_name} (ID: {$user->id})",
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);

            return redirect()->route('admin.users')->with('success', 'User has been updated successfully.');
        }

        return view('admin.user_form', compact('user'));
    }

    private function mapRoleToFlags(string $role): array
    {
        return match ($role) {
            'voter' => ['is_voter' => true, 'is_candidate' => false, 'is_admin' => false, 'is_officer' => false, 'is_observer' => false],
            'candidate' => ['is_voter' => true, 'is_candidate' => true, 'is_admin' => false, 'is_officer' => false, 'is_observer' => false],
            'admin' => ['is_voter' => true, 'is_candidate' => false, 'is_admin' => true, 'is_officer' => false, 'is_observer' => false],
            'officer' => ['is_voter' => false, 'is_candidate' => false, 'is_admin' => false, 'is_officer' => true, 'is_observer' => false],
            'observer' => ['is_voter' => false, 'is_candidate' => false, 'is_admin' => false, 'is_officer' => false, 'is_observer' => true],
            default => ['is_voter' => true, 'is_candidate' => false, 'is_admin' => false, 'is_officer' => false, 'is_observer' => false],
        };
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account.');
        }

        if ($user->votes()->exists()) {
            return redirect()->route('admin.users')->with('error', 'Cannot delete user who has cast votes. Votes are immutable.');
        }

        $userName = $user->full_name;

        $user->candidate()->delete();
        AuditLog::where('user_id', $userId)->update(['user_id' => null]);
        SuspiciousLog::where('user_id', $userId)->update(['user_id' => null]);

        $user->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'USER_DELETED',
            'details' => "Deleted user {$userName} (ID: {$userId})",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'User has been deleted successfully.');
    }

    public function candidates(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $query = Candidate::with('user');
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        $candidates = $query->latest()->get();
        $elections = Election::latest()->get();
        return view('admin.candidates', compact('candidates', 'statusFilter', 'elections'));
    }

    public function approveCandidate(Request $request, $candidateId)
    {
        $candidate = Candidate::findOrFail($candidateId);
        $electionId = $request->input('election_id', $candidate->election_id);

        if (!$electionId) {
            return redirect()->route('admin.candidates')->with('error', 'Please select an election for this candidate.');
        }

        $election = Election::find($electionId);
        if (!$election) {
            return redirect()->route('admin.candidates')->with('error', 'Invalid election.');
        }

        $candidate->status = 'approved';
        $candidate->election_id = $electionId;
        $candidate->approved_at = Carbon::now();
        $candidate->save();

        if ($candidate->user) {
            $candidate->user->role = 'candidate';
            $candidate->user->save();
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CANDIDATE_APPROVED',
            'details' => "Approved candidate {$candidate->user->full_name} for election '{$election->title_en}'",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.candidates')->with('success', 'Candidate has been approved successfully.');
    }

    public function rejectCandidate(Request $request, $candidateId)
    {
        $candidate = Candidate::findOrFail($candidateId);
        $candidate->status = 'rejected';
        $candidate->rejection_reason = $request->input('rejection_reason') ?: $request->query('rejection_reason');
        $candidate->save();

        if ($candidate->user) {
            $candidate->user->role = 'voter';
            $candidate->user->save();
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CANDIDATE_REJECTED',
            'details' => "Rejected candidate {$candidate->user->full_name}" . ($candidate->rejection_reason ? ": {$candidate->rejection_reason}" : ''),
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.candidates')->with('success', 'Candidate has been rejected.');
    }

    public function elections()
    {
        $elections = Election::latest()->get();
        return view('admin.elections', compact('elections'));
    }

    public function createElection(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'title_en' => 'required|string|max:200',
                'title_sw' => 'required|string|max:200',
                'election_type' => 'required|exists:positions,slug',
                'nomination_start' => 'nullable|date',
                'nomination_end' => 'nullable|date|after:nomination_start',
                'campaign_start' => 'nullable|date',
                'campaign_end' => 'nullable|date|after:campaign_start',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]);

            $election = Election::create([
                'title_en' => $request->title_en,
                'title_sw' => $request->title_sw,
                'election_type' => $request->election_type,
                'nomination_start' => $request->nomination_start ? Carbon::parse($request->nomination_start) : null,
                'nomination_end' => $request->nomination_end ? Carbon::parse($request->nomination_end) : null,
                'campaign_start' => $request->campaign_start ? Carbon::parse($request->campaign_start) : null,
                'campaign_end' => $request->campaign_end ? Carbon::parse($request->campaign_end) : null,
                'start_time' => Carbon::parse($request->start_time),
                'end_time' => Carbon::parse($request->end_time),
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'ELECTION_CREATED',
                'details' => "Created election '{$election->title_en}' ({$election->election_type})",
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);

            return redirect()->route('admin.elections')->with('success', 'Election created successfully.');
        }

        return view('admin.election_form');
    }

    public function editElection(Request $request, $electionId)
    {
        $election = Election::findOrFail($electionId);

        if ($request->isMethod('post')) {
            $request->validate([
                'title_en' => 'required|string|max:200',
                'title_sw' => 'required|string|max:200',
                'nomination_start' => 'nullable|date',
                'nomination_end' => 'nullable|date|after:nomination_start',
                'campaign_start' => 'nullable|date',
                'campaign_end' => 'nullable|date|after:campaign_start',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]);

            $election->title_en = $request->title_en;
            $election->title_sw = $request->title_sw;
            $election->nomination_start = $request->nomination_start ? Carbon::parse($request->nomination_start) : null;
            $election->nomination_end = $request->nomination_end ? Carbon::parse($request->nomination_end) : null;
            $election->campaign_start = $request->campaign_start ? Carbon::parse($request->campaign_start) : null;
            $election->campaign_end = $request->campaign_end ? Carbon::parse($request->campaign_end) : null;
            $election->start_time = Carbon::parse($request->start_time);
            $election->end_time = Carbon::parse($request->end_time);
            $election->save();

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'ELECTION_UPDATED',
                'details' => "Updated election '{$election->title_en}'",
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);

            return redirect()->route('admin.elections')->with('success', 'Election updated successfully.');
        }

        return view('admin.election_form', compact('election'));
    }

    public function transitionStatus($electionId, $targetStatus)
    {
        $election = Election::findOrFail($electionId);
        $validTransitions = [
            'draft' => ['nomination_open'],
            'nomination_open' => ['published'],
            'published' => ['campaign_period'],
            'campaign_period' => ['active'],
            'active' => ['closed'],
            'closed' => ['objection_period'],
            'returned' => ['draft'],
        ];

        $allowed = $validTransitions[$election->status] ?? [];
        if (!in_array($targetStatus, $allowed)) {
            return redirect()->route('admin.elections')->with('error',
                "Cannot transition from {$election->status} to {$targetStatus}.");
        }

        $election->status = $targetStatus;
        if ($targetStatus === 'active') {
            $election->voting_enabled = true;
        }
        if ($targetStatus === 'published') {
            $election->candidates_published = true;
        }
        if ($targetStatus === 'closed') {
            $election->voting_enabled = false;
            $election->objection_deadline = Carbon::now()->addDays(7);
            $results = $this->votingService->computeResults($election);
            if (!empty($results['candidates'])) {
                $winner = $results['candidates'][0];
                $election->winner_declared = true;
                $election->winner_candidate_id = $winner['candidate']->id;
            }
        }
        if ($targetStatus === 'objection_period') {
            if (!$election->objection_deadline) {
                $election->objection_deadline = Carbon::now()->addDays(7);
            }
        }
        if ($targetStatus === 'draft' && $election->status === 'returned') {
            $election->objection_triggered = false;
            $election->objection_deadline = null;
            $election->voting_enabled = false;
        }
        $election->save();

        $actionMap = [
            'nomination_open' => 'NOMINATION_OPENED',
            'published' => 'CANDIDATES_PUBLISHED',
            'campaign_period' => 'CAMPAIGN_STARTED',
            'active' => 'VOTING_OPENED',
            'closed' => 'VOTING_CLOSED',
            'objection_period' => 'OBJECTION_PERIOD_STARTED',
            'draft' => 'ELECTION_REOPENED',
        ];

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $actionMap[$targetStatus] ?? 'STATUS_CHANGED',
            'details' => "Election '{$election->title_en}' status changed to {$targetStatus}",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        $msgMap = [
            'nomination_open' => 'Nominations have been opened successfully.',
            'published' => 'Candidates have been published successfully.',
            'campaign_period' => 'Campaign period has been started successfully.',
            'active' => 'Voting has been opened successfully.',
            'closed' => 'Voting has been closed successfully. 7-day objection period has started.',
            'objection_period' => 'Objection period has been opened successfully.',
            'draft' => 'Election has been reopened for a new round.',
        ];

        return redirect()->route('admin.elections')->with('success', $msgMap[$targetStatus] ?? 'Status updated successfully.');
    }

    public function closeVoting($electionId)
    {
        $election = Election::findOrFail($electionId);
        if ($election->status !== 'active') {
            return redirect()->route('admin.elections')->with('error', 'Voting is not currently open.');
        }
        $election->status = 'closed';
        $election->voting_enabled = false;
        $election->objection_deadline = Carbon::now()->addDays(7);
        $election->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'VOTING_CLOSED',
            'details' => "Closed voting for election '{$election->title_en}'",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.elections')->with('success', 'Voting has been closed successfully. 7-day objection period started.');
    }

    public function generateResults($electionId)
    {
        return redirect()->route('admin.elections')->with('info', 'Results are generated automatically from votes. No manual generation needed.');
    }

    public function declareWinner($electionId)
    {
        $election = Election::findOrFail($electionId);
        if (!in_array($election->status, ['closed', 'objection_period'])) {
            return redirect()->route('admin.elections')->with('error', 'Winner can only be declared for closed elections.');
        }

        $results = $this->votingService->computeResults($election);
        if (empty($results['candidates'])) {
            return redirect()->route('admin.elections')->with('error', 'No votes recorded for this election.');
        }

        $winner = $results['candidates'][0];

        $election->winner_declared = true;
        $election->winner_candidate_id = $winner['candidate']->id;
        $election->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'WINNER_DECLARED',
            'details' => "Declared winner for election '{$election->title_en}': {$winner['candidate']->full_name} ({$winner['vote_count']} votes, {$winner['percentage']}%)",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.elections')
            ->with('success', "Winner declared: {$winner['candidate']->full_name} with {$winner['vote_count']} votes ({$winner['percentage']}%).");
    }

    public function revokeWinner($electionId)
    {
        $election = Election::findOrFail($electionId);
        $election->winner_declared = false;
        $election->winner_candidate_id = null;
        $election->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'WINNER_REVOKED',
            'details' => "Revoked winner declaration for election '{$election->title_en}'",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.elections')->with('success', 'Winner declaration has been revoked.');
    }

    public function registerCandidate(Request $request, $position = null)
    {
        $validPositions = Position::pluck('slug')->toArray();
        if ($position && !in_array($position, $validPositions)) {
            abort(404);
        }

        if ($request->isMethod('post')) {
            $pos = Position::where('slug', $request->position)->first();
            $isPresidential = $pos && $pos->requires_running_mate;
            $minAge = $pos ? $pos->min_age : 18;

            $rules = [
                'user_id' => 'required|integer|exists:users,id',
                'election_id' => 'required|integer|exists:elections,id',
                'position' => 'required|exists:positions,slug',
                'manifesto' => 'nullable|string',
                'full_name' => 'required|string|max:100',
                'gender' => 'required|in:male,female,other',
                'date_of_birth' => 'required|date|before:'.now()->subYears($minAge)->format('Y-m-d'),
                'nationality' => 'required|string|max:50',
                'phone' => ['required', 'regex:/^(?:\+255[67]\d{8}|0[67]\d{8})$/'],
                'email' => 'required|email|max:120',
                'nida_number' => 'required|string|size:25|unique:candidates,nida_number',
                'party_id' => 'required|integer|exists:political_parties,id',
                'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'party_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
                'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'biography' => 'nullable|string',
                'education' => 'nullable|string',
                'political_experience' => 'nullable|string',
            ];

            if ($pos && $pos->requires_running_mate) {
                $rules['running_mate_name'] = 'required|string|max:100';
                $rules['running_mate_photo'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
            }
            if ($pos && $pos->requires_constituency) {
                $rules['constituency_id'] = 'required|integer|exists:constituencies,id';
                $rules['residential_address'] = 'required|string|max:255';
                $rules['party_membership_number'] = 'nullable|string|max:50';
                $rules['ward_name'] = 'nullable|string|max:100';
            }

            $request->validate($rules, [
                'phone.regex' => 'Phone must be in Tanzania format (07XXXXXXXX, 06XXXXXXXX, +2557XXXXXXXX, +2556XXXXXXXX)',
                'date_of_birth.before' => "Candidate must be at least {$minAge} years old.",
                'photo.max' => 'Photo must not exceed 2MB.',
                'party_logo.max' => 'Party logo must not exceed 2MB.',
                'documents.*.max' => 'Each document must not exceed 5MB.',
            ]);

            $user = User::findOrFail($request->user_id);
            $election = Election::findOrFail($request->election_id);
            $party = PoliticalParty::findOrFail($request->party_id);

            if ($election->election_type !== $request->position) {
                return back()->with('error', 'Election type must match the candidate position.')
                    ->withInput();
            }

            $existing = Candidate::where('user_id', $request->user_id)->first();
            if ($existing) {
                return back()->with('error', 'This user is already registered as a candidate.')
                    ->withInput();
            }

            // One party per position for running-mate positions
            if ($pos && $pos->requires_running_mate) {
                $dupParty = Candidate::where('party_id', $request->party_id)
                    ->where('election_id', $request->election_id)
                    ->where('position', $request->position)
                    ->exists();
                if ($dupParty) {
                    return back()->with('error', 'This party already has a candidate for this position in this election.')
                        ->withInput();
                }
            }

            // One candidate per party per constituency
            if ($pos && $pos->requires_constituency) {
                $dupConstituency = Candidate::where('party_id', $request->party_id)
                    ->where('constituency_id', $request->constituency_id)
                    ->whereIn('position', ['parliamentary', 'councillor'])
                    ->exists();
                if ($dupConstituency) {
                    return back()->with('error', 'This party already has a candidate registered for this constituency.')
                        ->withInput();
                }
            }

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = 'uploads/candidates/photos/' . $request->photo->hashName();
                $request->photo->move(public_path('uploads/candidates/photos'), $request->photo->hashName());
            }

            $partyLogoPath = null;
            if ($request->hasFile('party_logo')) {
                $partyLogoPath = 'uploads/candidates/logos/' . $request->party_logo->hashName();
                $request->party_logo->move(public_path('uploads/candidates/logos'), $request->party_logo->hashName());
            }

            $runningMatePhotoPath = null;
            if ($request->hasFile('running_mate_photo')) {
                $runningMatePhotoPath = 'uploads/candidates/photos/' . $request->running_mate_photo->hashName();
                $request->running_mate_photo->move(public_path('uploads/candidates/photos'), $request->running_mate_photo->hashName());
            }

            $documentPaths = [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $doc) {
                    $docName = $doc->hashName();
                    $doc->move(public_path('uploads/candidates/documents'), $docName);
                    $documentPaths[] = 'uploads/candidates/documents/' . $docName;
                }
            }

            $constituencyName = null;
            if ($request->constituency_id) {
                $const = Constituency::find($request->constituency_id);
                $constituencyName = $const ? $const->name : null;
            }

            $candidate = Candidate::create([
                'user_id' => $request->user_id,
                'election_id' => $request->election_id,
                'position' => $request->position,
                'party_id' => $request->party_id,
                'constituency' => $constituencyName,
                'constituency_id' => $request->constituency_id,
                'ward_name' => $request->ward_name,
                'manifesto' => $request->manifesto,
                'status' => 'approved',
                'terms_accepted' => true,
                'approved_at' => Carbon::now(),
                'nomination_submitted_at' => Carbon::now(),
                'full_name' => $request->full_name,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'nationality' => $request->nationality,
                'phone' => $request->phone,
                'email' => $request->email,
                'nida_number' => $request->nida_number,
                'residential_address' => $request->residential_address,
                'party_name' => $party->name,
                'party_abbreviation' => $party->abbreviation,
                'party_membership_number' => $request->party_membership_number,
                'party_leader' => $party->name,
                'party_registration_number' => $party->registration_number,
                'running_mate_name' => $request->running_mate_name,
                'running_mate_photo' => $runningMatePhotoPath,
                'photo' => $photoPath,
                'party_logo' => $partyLogoPath,
                'documents' => $documentPaths,
                'biography' => $request->biography,
                'education' => $request->education,
                'political_experience' => $request->political_experience,
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'CANDIDATE_REGISTERED_BY_ADMIN',
                'details' => "Admin registered {$request->full_name} as {$request->position} candidate for election '{$election->title_en}'",
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);

            return redirect()->route('admin.candidates')
                ->with('success', "Candidate {$request->full_name} has been registered successfully.");
        }

        $presetPosition = $position;
        $users = User::where('status', 'active')
            ->when($presetPosition,
                fn($q) => $q->whereDoesntHave('candidate', fn($cq) => $cq->where('position', $presetPosition)),
                fn($q) => $q->whereDoesntHave('candidate')
            )
            ->get();
        $elections = Election::whereIn('status', ['draft', 'published', 'nomination_open'])
            ->when($presetPosition, fn($q) => $q->where('election_type', $presetPosition))
            ->get();
        $constituencies = Constituency::orderBy('region')->orderBy('name')->get();
        $parties = PoliticalParty::where('status', 'active')->orderBy('name')->get();
        $positions = Position::orderBy('sort_order')->get();
        return view('admin.register_candidate', compact('users', 'elections', 'presetPosition', 'constituencies', 'parties', 'positions'));
    }

    // ========== Nomination Support Management ==========

    public function nominationSupport($candidateId)
    {
        $candidate = Candidate::with('nominationSupport')->findOrFail($candidateId);
        $regions = Constituency::distinct('region')->pluck('region')->sort();
        return view('admin.nomination_support', compact('candidate', 'regions'));
    }

    public function addNominationSupport(Request $request, $candidateId)
    {
        $candidate = Candidate::findOrFail($candidateId);

        $request->validate([
            'region' => 'required|string|max:100',
            'supporter_name' => 'required|string|max:100',
            'supporter_nida' => 'nullable|string|max:25',
        ]);

        NominationSupport::create([
            'candidate_id' => $candidateId,
            'region' => $request->region,
            'supporter_name' => $request->supporter_name,
            'supporter_nida' => $request->supporter_nida,
            'notes' => $request->notes,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'NOMINATION_SUPPORT_ADDED',
            'details' => "Added nomination support from {$request->region} for candidate {$candidate->full_name}",
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.candidates.nomination_support', $candidateId)
            ->with('success', 'Nomination supporter added successfully.');
    }

    public function auditLogs(Request $request)
    {
        $logs = AuditLog::latest()->paginate(50);
        return view('admin.audit_logs', compact('logs'));
    }

    public function suspiciousLogs(Request $request)
    {
        $logs = SuspiciousLog::latest()->paginate(50);
        return view('admin.suspicious_logs', compact('logs'));
    }

    public function votesManage()
    {
        $elections = Election::with('votes')->latest()->get();
        $votes = Vote::with(['voter', 'candidate.user', 'election'])->latest()->take(100)->get();
        $voterCount = User::where('status', 'active')->count();
        return view('admin.votes', compact('elections', 'votes', 'voterCount'));
    }

    public function resultsManage()
    {
        $elections = Election::with('candidates.votes')->latest()->get();
        $resultsData = [];
        foreach ($elections as $election) {
            $resultsData[$election->id] = $this->votingService->computeResults($election);
            $resultsData[$election->id]['election'] = $election;
        }
        return view('admin.results', compact('resultsData', 'elections'));
    }

    public function exportResultsPdf($electionId)
    {
        $election = Election::findOrFail($electionId);
        $results = $this->votingService->computeResults($election);
        $position = Position::where('slug', $election->election_type)->first();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'RESULTS_EXPORTED_PDF',
            'details' => "Exported PDF results for election '{$election->title_en}'",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return view('admin.results_pdf', compact('election', 'results', 'position'));
    }

    public function exportResults($electionId)
    {
        $election = Election::findOrFail($electionId);
        $results = $this->votingService->computeResults($election);

        $filename = 'results_' . str_replace(' ', '_', $election->title_en) . '_' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($results, $election) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Tume Huru ya Taifa ya Uchaguzi Tanzania — Election Results Report']);
            fputcsv($handle, [$election->title_en . ' | ' . ($election->title_sw ?? '')]);
            fputcsv($handle, ['Generated: ' . now()->format('d M Y H:i:s')]);
            fputcsv($handle, ['Status: ' . ucfirst($election->status) . ($election->winner_declared ? ' | WINNER DECLARED' : '')]);
            fputcsv($handle, []);

            fputcsv($handle, ['Rank', 'Candidate', 'Party', 'Running Mate', 'Constituency', 'Votes', 'Percentage', 'Status']);

            foreach ($results['candidates'] as $item) {
                $candidate = $item['candidate'];
                $isWinner = $election->winner_declared && $item['rank'] === 1;
                fputcsv($handle, [
                    $item['rank'],
                    $candidate->full_name ?? $candidate->user->full_name,
                    $candidate->party_abbreviation ?? '',
                    $candidate->running_mate_name ?? '',
                    $candidate->constituency ?? '',
                    $item['vote_count'],
                    $item['percentage'] . '%',
                    $isWinner ? 'WINNER' : '',
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Total Votes', $results['total_votes']]);
            fputcsv($handle, ['Total Candidates', count($results['candidates'])]);

            fclose($handle);
        };

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'RESULTS_EXPORTED',
            'details' => "Exported results for election '{$election->title_en}'",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return response()->stream($callback, 200, $headers);
    }

    public function settings(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'max_failed_attempts' => 'required|integer|min:1|max:10',
                'lock_minutes_base' => 'required|integer|min:1|max:1440',
                'lock_multiplier' => 'required|integer|min:1|max:10',
                'default_language' => 'required|in:en,sw',
                'session_timeout' => 'required|integer|min:5|max:1440',
            ]);

            foreach ($validated as $key => $value) {
                Setting::setValue($key, $value);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'SETTINGS_UPDATED',
                'details' => 'System settings updated',
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);

            return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
        }

        $settings = Setting::getAll();
        return view('admin.settings', compact('settings'));
    }

    // ========== Positions Management ==========

    public function positions()
    {
        $positions = Position::orderBy('sort_order')->get();
        return view('admin.positions', compact('positions'));
    }

    public function createPosition(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'slug' => 'required|string|max:50|unique:positions,slug',
                'name_en' => 'required|string|max:100',
                'name_sw' => 'required|string|max:100',
                'description' => 'nullable|string',
                'min_age' => 'required|integer|min:1|max:150',
                'requires_constituency' => 'boolean',
                'requires_running_mate' => 'boolean',
                'sort_order' => 'required|integer|min:0',
            ]);

            Position::create([
                'slug' => $request->slug,
                'name_en' => $request->name_en,
                'name_sw' => $request->name_sw,
                'description' => $request->description,
                'min_age' => $request->min_age,
                'requires_constituency' => $request->boolean('requires_constituency'),
                'requires_running_mate' => $request->boolean('requires_running_mate'),
                'sort_order' => $request->sort_order,
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'POSITION_CREATED',
                'details' => "Created position '{$request->name_en}' ({$request->slug})",
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);

            return redirect()->route('admin.positions')->with('success', 'Position created successfully.');
        }

        return view('admin.position_form');
    }

    public function editPosition(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'slug' => 'required|string|max:50|unique:positions,slug,' . $id,
                'name_en' => 'required|string|max:100',
                'name_sw' => 'required|string|max:100',
                'description' => 'nullable|string',
                'min_age' => 'required|integer|min:1|max:150',
                'requires_constituency' => 'boolean',
                'requires_running_mate' => 'boolean',
                'sort_order' => 'required|integer|min:0',
            ]);

            $position->update([
                'slug' => $request->slug,
                'name_en' => $request->name_en,
                'name_sw' => $request->name_sw,
                'description' => $request->description,
                'min_age' => $request->min_age,
                'requires_constituency' => $request->boolean('requires_constituency'),
                'requires_running_mate' => $request->boolean('requires_running_mate'),
                'sort_order' => $request->sort_order,
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'POSITION_UPDATED',
                'details' => "Updated position '{$position->name_en}'",
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
                'timestamp' => Carbon::now(),
            ]);

            return redirect()->route('admin.positions')->with('success', 'Position updated successfully.');
        }

        return view('admin.position_form', compact('position'));
    }

    public function deletePosition($id)
    {
        $position = Position::findOrFail($id);

        $electionCount = Election::where('election_type', $position->slug)->count();
        $candidateCount = Candidate::where('position', $position->slug)->count();

        if ($electionCount > 0 || $candidateCount > 0) {
            return redirect()->route('admin.positions')
                ->with('error', "Cannot delete '{$position->name_en}': {$electionCount} elections and {$candidateCount} candidates use this position.");
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'POSITION_DELETED',
            'details' => "Deleted position '{$position->name_en}' ({$position->slug})",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        $position->delete();

        return redirect()->route('admin.positions')->with('success', 'Position deleted successfully.');
    }

    // ========== Objections Management ==========

    public function objections()
    {
        $objections = Objection::with(['objector', 'candidate', 'election'])->latest()->get();
        return view('admin.objections', compact('objections'));
    }

    public function viewObjection($id)
    {
        $objection = Objection::with(['objector', 'candidate.user', 'election'])->findOrFail($id);
        return view('admin.objection_show', compact('objection'));
    }

    public function resolveObjection(Request $request, $id)
    {
        $objection = Objection::findOrFail($id);

        $request->validate([
            'status' => 'required|in:upheld,dismissed',
            'admin_notes' => 'nullable|string',
        ]);

        $objection->status = $request->status;
        $objection->admin_notes = $request->admin_notes;
        $objection->resolved_by = Auth::id();
        $objection->resolved_at = Carbon::now();
        $objection->save();

        // If nomination objection is upheld, reject the candidate
        if ($objection->type === 'nomination' && $request->status === 'upheld' && $objection->candidate) {
            $candidate = $objection->candidate;
            $candidate->status = 'rejected';
            $candidate->save();

            if ($candidate->user) {
                $candidate->user->role = 'voter';
                $candidate->user->save();
            }
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'OBJECTION_RESOLVED',
            'details' => "Objection #{$objection->id} resolved as {$request->status}",
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.objections')->with('success', 'Objection has been resolved.');
    }

    // ========== Code of Conduct Violations Management ==========

    public function violations()
    {
        $violations = CodeConductViolation::with(['reporter', 'accused', 'candidate'])->latest()->get();
        return view('admin.violations', compact('violations'));
    }

    public function viewViolation($id)
    {
        $violation = CodeConductViolation::with(['reporter', 'accused', 'candidate.user'])->findOrFail($id);
        return view('admin.violation_show', compact('violation'));
    }

    public function resolveViolation(Request $request, $id)
    {
        $violation = CodeConductViolation::findOrFail($id);

        $request->validate([
            'status' => 'required|in:investigated,substantiated,dismissed',
            'resolution_notes' => 'nullable|string',
        ]);

        $violation->status = $request->status;
        $violation->resolution_notes = $request->resolution_notes;
        $violation->resolved_by = Auth::id();
        $violation->resolved_at = Carbon::now();
        $violation->save();

        if ($request->status === 'substantiated' && $violation->candidate_id) {
            $candidate = $violation->candidate;
            if ($candidate && $candidate->status === 'approved') {
                $candidate->status = 'rejected';
                $candidate->save();

                if ($candidate->user) {
                    $candidate->user->role = 'voter';
                    $candidate->user->save();
                }
            }
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'VIOLATION_RESOLVED',
            'details' => "Violation #{$violation->id} resolved as {$request->status}",
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.violations')->with('success', 'Violation has been resolved.');
    }

    // ========== Assisted Votes Management ==========

    public function assistedVotes()
    {
        $assistedVotes = AssistedVote::with(['voter', 'assistant', 'election', 'candidate'])
            ->latest('created_at')
            ->get();
        return view('admin.assisted_votes', compact('assistedVotes'));
    }

    public function accessibilityLogs()
    {
        $logs = AccessibilityLog::with('user')
            ->latest('changed_at')
            ->get();
        return view('admin.accessibility_logs', compact('logs'));
    }

    // ========== Delete All Candidates ==========

    public function deleteAllCandidates()
    {
        $candidates = Candidate::all();
        $count = 0;

        foreach ($candidates as $candidate) {
            $user = $candidate->user;
            if ($user && $user->isAdmin()) {
                continue;
            }

            if ($candidate->votes()->exists()) {
                continue;
            }

            $candidate->nominationSupport()->delete();
            $candidate->delete();

            if ($user) {
                $userName = $user->full_name;
                AuditLog::where('user_id', $user->id)->update(['user_id' => null]);
                SuspiciousLog::where('user_id', $user->id)->update(['user_id' => null]);
                $user->delete();
            }

            $count++;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'ALL_CANDIDATES_DELETED',
            'details' => "Deleted {$count} candidates and their user accounts.",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.candidates')->with('success', "{$count} candidate(s) deleted successfully.");
    }

    // ========== Delete Election ==========

    public function deleteElection($electionId)
    {
        $election = Election::findOrFail($electionId);

        if ($election->status === 'active') {
            return redirect()->route('admin.elections')->with('error', 'Cannot delete an active election. Close it first.');
        }

        if ($election->votes()->count() > 0) {
            return redirect()->route('admin.elections')->with('error', 'Cannot delete an election that has votes. Votes are immutable.');
        }

        $title = $election->title_en;

        AssistedVote::where('election_id', $election->id)->delete();

        foreach ($election->candidates as $candidate) {
            $candidate->nominationSupport()->delete();
            $candidate->delete();
        }

        $election->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'ELECTION_DELETED',
            'details' => "Deleted election '{$title}' (ID: {$electionId})",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.elections')->with('success', 'Election deleted successfully.');
    }

    // ========== Delete All Votes ==========

    public function deleteAllVotes()
    {
        return redirect()->route('admin.votes')->with('error', 'Votes are immutable and cannot be deleted.');
    }

    // ========== Clear All Results ==========

    public function clearResults()
    {
        return redirect()->route('admin.results')->with('error', 'Results cannot be cleared. Votes are immutable.');
    }

    // ========== Delete All Audit Logs ==========

    public function deleteAllAuditLogs()
    {
        $count = AuditLog::count();
        AuditLog::truncate();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'ALL_AUDIT_LOGS_DELETED',
            'details' => "Deleted all {$count} audit log records.",
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return redirect()->route('admin.audit_logs')->with('success', "All {$count} audit logs deleted successfully.");
    }

    // ========== Delete All Suspicious Logs ==========

    public function deleteAllSuspiciousLogs()
    {
        $count = SuspiciousLog::count();
        SuspiciousLog::truncate();
        return redirect()->route('admin.suspicious_logs')->with('success', "All {$count} suspicious logs deleted successfully.");
    }
}
