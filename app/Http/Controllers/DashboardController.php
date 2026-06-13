<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Announcement;
use App\Models\AuditLog;
use App\Models\Position;
use App\Models\Constituency;
use App\Models\AccessibilityLog;
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
        $closedElections = Election::with('winnerCandidate')
            ->where('status', 'closed')
            ->latest('end_time')
            ->take(3)
            ->get();
        return view('index', compact('announcements', 'activeElections', 'upcomingElections', 'closedElections'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $previewRole = session('preview_role');

        if (($user->isAdmin() || $user->isOfficer()) && !$previewRole) {
            return redirect()->route('admin.dashboard');
        }

        if ($previewRole === 'candidate' || $user->isCandidate()) {
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

    public function updateAccessibility(Request $request)
    {
        $user = Auth::user();
        $oldMode = $user->accessibility_mode;

        $user->update([
            'accessibility_enabled' => $request->boolean('accessibility_enabled'),
            'disability_type' => json_encode($request->input('disability_type', [])),
            'accessibility_mode' => $request->input('accessibility_mode', 'normal'),
            'high_contrast' => $request->boolean('high_contrast'),
            'text_size' => $request->input('text_size', 'medium'),
        ]);

        session([
            'accessibility_mode' => $user->accessibility_mode,
            'high_contrast' => $user->high_contrast,
            'text_size' => $user->text_size,
            'disability_type' => json_decode($user->disability_type ?? '[]', true),
        ]);

        AccessibilityLog::create([
            'user_id' => $user->id,
            'old_mode' => $oldMode,
            'new_mode' => $user->accessibility_mode,
            'notes' => 'Updated from profile settings',
            'changed_at' => Carbon::now(),
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'ACCESSIBILITY_UPDATED',
            'details' => "User {$user->full_name} updated accessibility settings (mode: {$oldMode} -> {$user->accessibility_mode})",
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
            'timestamp' => Carbon::now(),
        ]);

        return back()->with('success', __t('accessibility_updated'));
    }

    public function toggleContrast(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->high_contrast = !$user->high_contrast;
            $user->save();
            session(['high_contrast' => $user->high_contrast]);
        }
        return response()->json(['high_contrast' => session('high_contrast', false)]);
    }

    public function previewVoter()
    {
        session(['preview_role' => 'voter']);
        return redirect()->route('dashboard');
    }

    public function previewCandidate()
    {
        session(['preview_role' => 'candidate']);
        return redirect()->route('dashboard');
    }

    public function exitPreview()
    {
        session()->forget('preview_role');
        return redirect()->route('admin.dashboard');
    }

    public function results(Request $request)
    {
        $search = trim($request->query('search', ''));

        $elections = Election::where('status', 'closed')
            ->latest('end_time')
            ->get();

        $allRegions = Constituency::whereNotNull('region')
            ->distinct()
            ->pluck('region')
            ->toArray();
        $allConstituencies = Constituency::whereNotNull('name')
            ->pluck('name')
            ->toArray();
        $suggestions = array_unique(array_merge($allRegions, $allConstituencies));
        sort($suggestions);

        $resultsData = [];
        foreach ($elections as $election) {
            $resultsData[$election->id] = $this->getFilteredResultsForElection($election, $search);
            $resultsData[$election->id]['election'] = $election;
        }

        $positions = Position::orderBy('name_en')->get();

        return view('results', compact('resultsData', 'elections', 'positions', 'search', 'suggestions'));
    }

    private function getFilteredResultsForElection(Election $election, string $search = ''): array
    {
        $votes = Vote::where('election_id', $election->id)->get();

        $hasConstituencyFilter = $search !== '' && $election->election_type !== 'presidential';

        if ($hasConstituencyFilter) {
            $matchingCandidateIds = Candidate::where('election_id', $election->id)
                ->where(function ($q) use ($search) {
                    $q->where('constituency', 'LIKE', "%{$search}%")
                      ->orWhereHas('constituency', function ($cq) use ($search) {
                          $cq->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('region', 'LIKE', "%{$search}%");
                      });
                })
                ->pluck('id')
                ->toArray();

            $votes = $votes->filter(function ($vote) use ($matchingCandidateIds) {
                return in_array($vote->candidate_id, $matchingCandidateIds);
            });
        }

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

        $matchedConstituencies = [];
        $constituencyGroups = [];
        if ($hasConstituencyFilter) {
            $matchedConstituencies = Candidate::where('election_id', $election->id)
                ->where(function ($q) use ($search) {
                    $q->where('constituency', 'LIKE', "%{$search}%")
                      ->orWhereHas('constituency', function ($cq) use ($search) {
                          $cq->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('region', 'LIKE', "%{$search}%");
                      });
                })
                ->distinct()
                ->pluck('constituency')
                ->filter()
                ->values()
                ->toArray();

            if ($election->election_type === 'parliamentary') {
                foreach ($ranked as $item) {
                    $c = $item['candidate']->constituency ?? 'Unknown';
                    if (!isset($constituencyGroups[$c])) {
                        $constituencyGroups[$c] = [
                            'constituency' => $c,
                            'candidates' => [],
                            'total_votes' => 0,
                            'winner_id' => null,
                            'winner_name' => null,
                        ];
                    }
                    $constituencyGroups[$c]['candidates'][] = $item;
                    $constituencyGroups[$c]['total_votes'] += $item['vote_count'];
                }
                foreach ($constituencyGroups as &$group) {
                    usort($group['candidates'], fn($a, $b) => $b['vote_count'] - $a['vote_count']);
                    $rk = 1;
                    foreach ($group['candidates'] as &$cnd) {
                        $cnd['rank'] = $rk++;
                        $cnd['percentage'] = $group['total_votes'] > 0
                            ? round(($cnd['vote_count'] / $group['total_votes']) * 100, 1)
                            : 0;
                    }
                    $group['winner_id'] = $group['candidates'][0]['candidate']->id ?? null;
                    $group['winner_name'] = $group['candidates'][0]['candidate']->full_name
                        ?? $group['candidates'][0]['candidate']->user->full_name
                        ?? null;
                }
                unset($group);
            }
        }

        return [
            'total_votes' => $totalVotes,
            'candidates' => $ranked,
            'matched_constituencies' => $matchedConstituencies,
            'constituency_groups' => array_values($constituencyGroups),
            'has_filter' => $search !== '',
        ];
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

        $callback = function () use ($results, $election) {
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

        if (Auth::check()) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'RESULTS_EXPORTED',
                'details' => "Exported results for election '{$election->title_en}'",
                'ip_address' => request()->ip(),
                'device_info' => request()->userAgent(),
                'timestamp' => Carbon::now(),
            ]);
        }

        return response()->stream($callback, 200, $headers);
    }

    public function exportResultsPdf($electionId)
    {
        $election = Election::findOrFail($electionId);
        $results = $this->votingService->computeResults($election);
        $position = Position::where('slug', $election->election_type)->first();

        if (Auth::check()) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'RESULTS_EXPORTED_PDF',
                'details' => "Exported PDF results for election '{$election->title_en}'",
                'ip_address' => request()->ip(),
                'device_info' => request()->userAgent(),
                'timestamp' => Carbon::now(),
            ]);
        }

        return view('admin.results_pdf', compact('election', 'results', 'position'));
    }
}
