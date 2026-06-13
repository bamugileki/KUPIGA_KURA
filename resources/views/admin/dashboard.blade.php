@extends('layouts.admin')
@section('title', __t('dashboard'))
@section('subtitle', __t('admin_dashboard_overview'))
@section('content')
<div class="space-y-6">

    {{-- Users & Voters --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ __t('users') }} &amp; {{ __t('voter') }}s
            </h3>
            <a href="{{ route('admin.users') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">{{ __t('view_all') }} &rarr;</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('total_registered') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalUsers }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-green-600">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    <span>+{{ $newRegistrationsToday }} {{ __t('new_today') }}</span>
                </div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('verified') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $verifiedUsers }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">{{ $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100) : 0 }}% {{ __t('of_total') }}</div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('unverified') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $unverifiedUsers }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">{{ $totalUsers > 0 ? round(($unverifiedUsers / $totalUsers) * 100) : 0 }}% {{ __t('of_total') }}</div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('suspended_flagged') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $suspendedAccounts }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">{{ $suspendedAccounts > 0 ? '⚠ '.__t('attention_required') : '✓ '.__t('all_clear') }}</div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('active') }} {{ __t('voter') }}s</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalUsers - $unverifiedUsers - $suspendedAccounts }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">{{ $totalUsers > 0 ? round((($totalUsers - $unverifiedUsers - $suspendedAccounts) / $totalUsers) * 100) : 0 }}% {{ __t('of_total') }}</div>
            </div>
        </div>
    </div>

    {{-- Elections Overview --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                {{ __t('elections') }} {{ __t('overview') }}
            </h3>
            <a href="{{ route('admin.elections') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">{{ __t('manage_elections') }} &rarr;</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('live') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $electionsByStatus['live'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="mt-3">
                    @if($electionsByStatus['live'] > 0)
                    <span class="inline-flex items-center text-xs text-green-600">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                        {{ __t('voting_open') }}
                    </span>
                    @else
                    <span class="text-xs text-gray-400">{{ __t('no_active') }}</span>
                    @endif
                </div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('upcoming') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $electionsByStatus['upcoming'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">{{ __t('scheduled') }}</div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('completed') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $electionsByStatus['completed'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">{{ __t('finished') }}</div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('draft') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $electionsByStatus['draft'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">{{ __t('in_preparation') }}</div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('winner_declared_label') ?? 'Winners' }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ \App\Models\Election::where('winner_declared', true)->count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">{{ __t('declared') }}</div>
            </div>
        </div>
    </div>

    {{-- Candidates --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                {{ __t('candidates') }}
            </h3>
            <a href="{{ route('admin.candidates') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">{{ __t('manage_candidates') }} &rarr;</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('total_candidates') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalCandidates }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('pending_approval') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $pendingCandidates }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                @if($pendingCandidates > 0)
                <div class="mt-3"><span class="text-xs text-yellow-600 font-medium">{{ __t('requires_review') }}</span></div>
                @endif
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('approved') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $approvedCandidates }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">{{ $totalCandidates > 0 ? round(($approvedCandidates / $totalCandidates) * 100) : 0 }}% {{ __t('approval_rate') }}</div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('rejected') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $rejectedCandidates }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center stat-icon">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Voting Activity --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __t('voting_activity') }}
            </h3>
            <a href="{{ route('admin.votes') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">{{ __t('view_all') }} &rarr;</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('total_votes') }}</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalVotes }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg stat-icon">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center text-xs text-gray-400">
                    <span>{{ __t('votes_cast_across_all_elections') }}</span>
                </div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('turnout') }}</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $turnoutPercentage }}%</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center shadow-lg stat-icon">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <div class="mt-3 flex items-center space-x-2 text-xs">
                    <span class="text-gray-400">{{ $totalVotes }} / {{ $totalEligible }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="{{ $turnoutPercentage >= 50 ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $turnoutPercentage >= 50 ? '✓ '.__t('good_turnout') : '⚠ '.__t('low_turnout') }}
                    </span>
                </div>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('eligible_voters') }}</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalEligible }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center shadow-lg stat-icon">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-400">{{ __t('registered_eligible_voters') }}</div>
            </div>
        </div>
        @if($votesPerElection->isNotEmpty())
        <div class="card-hover mt-4 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">{{ __t('votes_per_election') }}</h4>
            <div class="space-y-3">
                @foreach($votesPerElection as $i => $ve)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-600 font-medium truncate mr-2">{{ $ve['election'] }}</span>
                        <span class="text-gray-900 font-semibold">{{ $ve['total'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        @php $pct = $totalVotes > 0 ? round(($ve['total'] / $totalVotes) * 100) : 0; @endphp
                        <div class="h-2 rounded-full transition-all duration-1000 ease-out" style="width: 0%; background: linear-gradient(90deg, #3b82f6, #60a5fa);" data-width="{{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Security Alerts --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                {{ __t('security_alerts') }}
                @if($systemStatus === 'alert')
                    <span class="ml-2 badge bg-red-100 text-red-700">{{ __t('attention_required') }}</span>
                @endif
            </h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('failed_logins_today') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $securityAlerts['login_attempts'] }}</p>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('suspicious_activities_today') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $securityAlerts['suspicious_activities'] }}</p>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('duplicate_detections') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $securityAlerts['duplicate_detections'] }}</p>
            </div>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('failed_auth_today') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $securityAlerts['failed_auth'] }}</p>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">{{ __t('recent_activity') }}</h3>
            @if($recentLogs->count() > 0)
            <span class="text-xs text-gray-400">{{ $recentLogs->count() }} {{ __t('entries') }}</span>
            @endif
        </div>
        @if($recentLogs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-3 px-5 text-xs text-gray-500 uppercase font-semibold tracking-wider">{{ __t('user') }}</th>
                        <th class="text-left py-3 px-5 text-xs text-gray-500 uppercase font-semibold tracking-wider">{{ __t('action') }}</th>
                        <th class="text-left py-3 px-5 text-xs text-gray-500 uppercase font-semibold tracking-wider">{{ __t('timestamp') }}</th>
                        <th class="text-left py-3 px-5 text-xs text-gray-500 uppercase font-semibold tracking-wider">{{ __t('ip_address') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLogs as $log)
                    <tr class="border-t border-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-5 text-gray-700">{{ $log->user_id ? optional($log->user)->full_name ?? 'User#'.$log->user_id : '-' }}</td>
                        <td class="py-3 px-5">
                            <span class="badge
                                @if(str_contains($log->action, 'SUCCESS') || str_contains($log->action, 'APPROVED') || str_contains($log->action, 'OPENED')) bg-green-100 text-green-700
                                @elseif(str_contains($log->action, 'FAILED') || str_contains($log->action, 'LOCKED') || str_contains($log->action, 'BLOCKED')) bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="py-3 px-5 text-gray-500">{{ $log->timestamp->format('Y-m-d H:i') }}</td>
                        <td class="py-3 px-5 text-gray-400 text-xs font-mono">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-400 text-center py-10 text-sm">{{ __t('no_data') }}</p>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.querySelectorAll('[data-width]').forEach(function(bar) {
                bar.style.width = bar.dataset.width;
            });
        }, 400);
    });
</script>
@endsection
