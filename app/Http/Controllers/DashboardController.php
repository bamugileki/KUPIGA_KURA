<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Announcement;
use App\Models\AuditLog;
use App\Services\VotingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    protected VotingService $votingService;

    public function __construct(VotingService $votingService)
    {
        $this->votingService = $votingService;
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $announcements = Announcement::published()
            ->latest('published_at')
            ->take(3)
            ->get();
        $activeElections = Election::where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->orderBy('end_time')
            ->with('candidates')
            ->get();
        $upcomingElections = Election::where('status', 'published')
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->get();
        return view('index', compact('announcements', 'activeElections', 'upcomingElections'));
    }

    public function dashboard()
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isOfficer()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isCandidate()) {
            return $this->candidateDashboard($user);
        }

        if ($user->isObserver()) {
            return $this->observerDashboard($user);
        }

        return $this->voterDashboard($user);
    }

    protected function voterDashboard($user)
    {
        $now = Carbon::now();

        $activeElections = Election::visible()->get();

        $userVotes = Vote::where('user_id', $user->id)->with('election')->get();
        $votedElectionIds = $userVotes->pluck('election_id')->toArray();

        $lastLogin = AuditLog::where('user_id', $user->id)
            ->where('action', 'LOGIN_SUCCESS')
            ->latest('timestamp')
            ->first();

        $recentAnnouncements = Announcement::published()
            ->latest('published_at')
            ->take(5)
            ->get();

        $candidacy = Candidate::where('user_id', $user->id)->first();

        return view('dashboards.voter', compact(
            'user', 'now', 'activeElections', 'userVotes', 'votedElectionIds', 'lastLogin', 'recentAnnouncements', 'candidacy'
        ));
    }

    protected function candidateDashboard($user)
    {
        $now = Carbon::now();

        $candidacy = Candidate::where('user_id', $user->id)->first();

        $myElections = Election::whereIn('id', function($q) use ($user) {
                $q->select('election_id')->from('candidates')->where('user_id', $user->id);
            })->get();

        $totalVotesReceived = 0;
        if ($candidacy) {
            $totalVotesReceived = Vote::whereIn('election_id', $myElections->pluck('id'))
                ->where('candidate_id', $candidacy->id)
                ->count();
        }

        $rankings = [];
        foreach ($myElections as $election) {
            $results = $this->votingService->computeResults($election);
            $rankings[$election->id] = $results;
        }

        $approvalLogs = AuditLog::where('user_id', $user->id)
            ->whereIn('action', ['CANDIDATE_APPROVED', 'CANDIDATE_REJECTED', 'CANDIDATE_REGISTERED_BY_ADMIN'])
            ->latest('timestamp')
            ->get();

        $adminMessages = AuditLog::where('action', 'ADMIN_MESSAGE_TO_CANDIDATE')
            ->where('details', 'like', "%\"user_id\":{$user->id}%")
            ->latest('timestamp')
            ->get();

        $activeElections = Election::visible()->get();

        return view('dashboards.candidate', compact(
            'user', 'candidacy', 'now', 'myElections', 'totalVotesReceived',
            'rankings', 'approvalLogs', 'adminMessages', 'activeElections'
        ));
    }

    protected function observerDashboard($user)
    {
        $elections = Election::whereIn('status', ['active', 'closed'])
            ->latest()
            ->get();

        $announcements = Announcement::published()
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('dashboards.agent_observer', compact('user', 'elections', 'announcements'));
    }

    public function profile()
    {
        $user = Auth::user();
        $userCandidacy = Candidate::where('user_id', $user->id)->first();
        return view('profile', compact('user', 'userCandidacy'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', __t('incorrect_password'));
        }

        $user->password = $request->new_password;
        $user->save();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'PASSWORD_CHANGED',
            'details' => "User {$user->full_name} changed their password",
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return back()->with('success', __t('password_updated'));
    }

    public function results()
    {
        $elections = Election::where('status', 'closed')
            ->latest('end_time')
            ->get();

        $resultsData = [];
        foreach ($elections as $election) {
            $resultsData[$election->id] = $this->votingService->computeResults($election);
            $resultsData[$election->id]['election'] = $election;
        }

        return view('results', compact('resultsData', 'elections'));
    }
}
