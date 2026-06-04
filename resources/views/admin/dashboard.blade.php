@extends('layouts.admin')
@section('content')
<div class="space-y-6">
    {{-- Users & Voters --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ __t('users') }} {{ __t('and') }} {{ __t('voter') }}s
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-blue-900 stagger-1">
                <div class="text-2xl font-bold text-blue-900">{{ $totalUsers }}</div>
                <div class="text-xs text-gray-600">{{ __t('total_registered') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-green-500 stagger-2">
                <div class="text-2xl font-bold text-green-600">{{ $newRegistrationsToday }}</div>
                <div class="text-xs text-gray-600">{{ __t('new_today') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-blue-500 stagger-3">
                <div class="text-2xl font-bold text-blue-600">{{ $verifiedUsers }}</div>
                <div class="text-xs text-gray-600">{{ __t('verified') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-red-500 stagger-4">
                <div class="text-2xl font-bold text-red-600">{{ $unverifiedUsers }}</div>
                <div class="text-xs text-gray-600">{{ __t('unverified') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500 stagger-5">
                <div class="text-2xl font-bold text-yellow-600">{{ $suspendedAccounts }}</div>
                <div class="text-xs text-gray-600">{{ __t('suspended_flagged') }}</div>
            </div>
        </div>
    </div>

    {{-- Elections Overview --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            {{ __t('elections') }} {{ __t('overview') }}
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-green-500 stagger-1">
                <div class="text-2xl font-bold text-green-600">{{ $electionsByStatus['live'] }}</div>
                <div class="text-xs text-gray-600">{{ __t('live') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-blue-500 stagger-2">
                <div class="text-2xl font-bold text-blue-600">{{ $electionsByStatus['upcoming'] }}</div>
                <div class="text-xs text-gray-600">{{ __t('upcoming') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-gray-500 stagger-3">
                <div class="text-2xl font-bold text-gray-600">{{ $electionsByStatus['completed'] }}</div>
                <div class="text-xs text-gray-600">{{ __t('completed') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500 stagger-4">
                <div class="text-2xl font-bold text-yellow-600">{{ $electionsByStatus['draft'] }}</div>
                <div class="text-xs text-gray-600">{{ __t('draft') }}</div>
            </div>
        </div>
    </div>

    {{-- Candidates --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            {{ __t('candidates') }}
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-blue-900 stagger-1">
                <div class="text-2xl font-bold text-blue-900">{{ $totalCandidates }}</div>
                <div class="text-xs text-gray-600">{{ __t('total_candidates') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500 stagger-2">
                <div class="text-2xl font-bold text-yellow-600">{{ $pendingCandidates }}</div>
                <div class="text-xs text-gray-600">{{ __t('pending_approval') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-green-500 stagger-3">
                <div class="text-2xl font-bold text-green-600">{{ $approvedCandidates }}</div>
                <div class="text-xs text-gray-600">{{ __t('approved') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-red-500 stagger-4">
                <div class="text-2xl font-bold text-red-600">{{ $rejectedCandidates }}</div>
                <div class="text-xs text-gray-600">{{ __t('rejected') }}</div>
            </div>
        </div>
    </div>

    {{-- Voting Activity --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __t('voting_activity') }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-blue-900 stagger-1">
                <div class="text-2xl font-bold text-blue-900">{{ $totalVotes }}</div>
                <div class="text-xs text-gray-600">{{ __t('total_votes') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-purple-500 stagger-2">
                <div class="text-2xl font-bold text-purple-600">{{ $turnoutPercentage }}%</div>
                <div class="text-xs text-gray-600">{{ __t('turnout') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-gray-500 stagger-3">
                <div class="text-2xl font-bold text-gray-600">{{ $totalEligible }}</div>
                <div class="text-xs text-gray-600">{{ __t('eligible_voters') }}</div>
            </div>
        </div>
        @if($votesPerElection->isNotEmpty())
        <div class="card-hover mt-4 bg-white rounded-lg shadow p-4 stagger-1">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ __t('votes_per_election') }}</h4>
            <div class="space-y-2">
                @foreach($votesPerElection as $i => $ve)
                <div class="flex items-center" style="animation: fadeInUp 0.4s ease-out {{ $i * 0.1 }}s both;">
                    <span class="text-sm text-gray-600 w-1/3 truncate">{{ $ve['election'] }}</span>
                    <div class="flex-1 bg-gray-200 rounded-full h-4 mx-2">
                        @php $pct = $totalVotes > 0 ? round(($ve['total'] / $totalVotes) * 100) : 0; @endphp
                        <div class="bg-blue-900 h-4 rounded-full transition-all duration-1000 ease-out progress-bar" style="width: 0%;" data-width="{{ $pct }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 w-16 text-right">{{ $ve['total'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Security Alerts --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            {{ __t('security_alerts') }}
            @if($systemStatus === 'alert')
                <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full font-medium">{{ __t('attention_required') }}</span>
            @endif
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-red-500 stagger-1">
                <div class="text-2xl font-bold text-red-600">{{ $securityAlerts['login_attempts'] }}</div>
                <div class="text-xs text-gray-600">{{ __t('failed_logins_today') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-orange-500 stagger-2">
                <div class="text-2xl font-bold text-orange-600">{{ $securityAlerts['suspicious_activities'] }}</div>
                <div class="text-xs text-gray-600">{{ __t('suspicious_activities_today') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500 stagger-3">
                <div class="text-2xl font-bold text-yellow-600">{{ $securityAlerts['duplicate_detections'] }}</div>
                <div class="text-xs text-gray-600">{{ __t('duplicate_detections') }}</div>
            </div>
            <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-red-700 stagger-4">
                <div class="text-2xl font-bold text-red-700">{{ $securityAlerts['failed_auth'] }}</div>
                <div class="text-xs text-gray-600">{{ __t('failed_auth_today') }}</div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="card-hover bg-white rounded-lg shadow stagger-1">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">{{ __t('recent_activity') }}</h3>
        </div>
        @if($recentLogs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('user') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('action') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('timestamp') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('ip_address') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLogs as $log)
                    <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $log->user_id ? optional($log->user)->full_name ?? 'User#' . $log->user_id : '-' }}</td>
                        <td class="py-2 px-4">
                            <span class="px-2 py-0.5 rounded text-xs font-medium
                                @if(str_contains($log->action, 'SUCCESS') || str_contains($log->action, 'APPROVED') || str_contains($log->action, 'OPENED')) bg-green-100 text-green-700
                                @elseif(str_contains($log->action, 'FAILED') || str_contains($log->action, 'LOCKED') || str_contains($log->action, 'BLOCKED')) bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="py-2 px-4 text-gray-600">{{ $log->timestamp->format('Y-m-d H:i') }}</td>
                        <td class="py-2 px-4 text-gray-500 text-xs">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 text-center py-6 text-sm">{{ __t('no_data') }}</p>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.querySelectorAll('.progress-bar').forEach(function(bar) {
                bar.style.width = bar.dataset.width;
            });
        }, 300);
    });
</script>
@endsection