@extends('layouts.app')
@section('title', __t('dashboard'))
@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Welcome & Status --}}
    <div class="bg-white rounded-lg shadow p-6 flex items-center justify-between fade-in-up stagger-1">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __t('welcome') }}, {{ $user->full_name }}!</h2>
            <p class="text-sm text-gray-600 mt-1">
                <span class="inline-block w-2 h-2 rounded-full mr-1
                    @if($user->is_verified) bg-green-500
                    @elseif($user->status === 'pending') bg-yellow-500
                    @else bg-red-500 @endif"></span>
                {{ __t('account_status') }}:
                @if($user->is_verified) {{ __t('verified') }}
                @elseif($user->status === 'pending') {{ __t('pending') }}
                @else {{ __t($user->status) }} @endif
            </p>
        </div>
        <div class="text-right text-sm">
            @php
                $liveCount = $activeElections->where('status', 'active')
                    ->filter(fn($e) => $now->between($e->start_time, $e->end_time))->count();
            @endphp
            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                @if($liveCount > 0) bg-green-100 text-green-700
                @elseif($activeElections->count() > 0) bg-yellow-100 text-yellow-700
                @else bg-gray-100 text-gray-700 @endif">
                @if($liveCount > 0) {{ __t('active') }}
                @elseif($activeElections->count() > 0) {{ __t('upcoming') }}
                @else {{ __t('no_active_elections') }} @endif
            </span>
        </div>
    </div>

    {{-- Voting Section --}}
    <div class="bg-white rounded-lg shadow fade-in-up stagger-2">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-bold text-lg text-gray-800">{{ __t('cast_vote') }}</h3>
            @if($user->is_verified)
                <span class="text-green-600 text-xs font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ __t('verified') }}
                </span>
            @else
                <span class="text-yellow-600 text-xs font-medium">{{ __t('not_verified') }}</span>
            @endif
        </div>
        <div class="p-6 space-y-4">
            @forelse($activeElections as $election)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-blue-900">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</h4>
                    <p class="text-xs text-gray-600 mt-1">
                        {{ __t($election->election_type) }}
                        @if(in_array($election->election_type, ['parliamentary', 'councillor'])) - {{ __t('constituency') }} @endif
                        | {{ $election->start_time->format('d M Y H:i') }} - {{ $election->end_time->format('d M Y H:i') }}
                    </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-xs px-2 py-1 rounded
                                @if($election->status === 'active') bg-green-100 text-green-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ __t($election->status) }}
                            </span>
                            @if(in_array($election->id, $votedElectionIds))
                                <span class="bg-gray-100 text-gray-500 px-4 py-2 rounded text-sm font-medium">
                                    {{ __t('already_voted') }}
                                </span>
                            @elseif($election->status === 'active' && $now->between($election->start_time, $election->end_time))
                                <a href="{{ route('vote.form', $election->id) }}"
                                   class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                                    {{ __t('cast_vote') }}
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">{{ __t('not_open_yet') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">{{ __t('no_active_elections') }}</p>
            @endforelse
        </div>
    </div>

    {{-- My Voting Activity + Profile + Announcements grid --}}
    <div class="grid md:grid-cols-3 gap-6">
        {{-- Voting Activity --}}
        <div class="bg-white rounded-lg shadow fade-in-up stagger-3">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">{{ __t('my_votes') }}</h3>
            </div>
            <div class="p-4 space-y-3 text-sm">
                @forelse($userVotes as $uv)
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <span class="text-gray-700">{{ $uv->election->title_en ?? 'Unknown' }}</span>
                        <span class="text-gray-500 text-xs">{{ $uv->timestamp->format('d M Y H:i') }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">{{ __t('no_votes') }}</p>
                @endforelse
                <div>
                    <span class="font-medium">{{ __t('total_votes_cast') }}: </span>
                    <span class="font-bold text-blue-900">{{ $userVotes->count() }}</span>
                </div>
            </div>
        </div>

        {{-- Quick Profile --}}
        <div class="bg-white rounded-lg shadow fade-in-up stagger-4">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">{{ __t('profile') }}</h3>
            </div>
            <div class="p-4 space-y-2 text-sm">
                <p><span class="text-gray-600">{{ __t('full_name') }}:</span> <span class="font-medium">{{ $user->full_name }}</span></p>
                <p><span class="text-gray-600">{{ __t('nida') }}:</span> <span class="font-medium">{{ $user->nida_number ?? '-' }}</span></p>
                <p><span class="text-gray-600">{{ __t('role_label') }}:</span> <span class="font-medium">{{ __t($user->role) }}</span></p>
                @if($user->constituency)
                <p><span class="text-gray-600">{{ __t('constituency') }}:</span> <span class="font-medium">{{ $user->constituency->name }}</span></p>
                @endif
                <p><span class="text-gray-600">{{ __t('account_status') }}:</span>
                    <span class="text-xs px-2 py-0.5 rounded
                        @if($user->status === 'active') bg-green-100 text-green-700
                        @elseif($user->status === 'pending') bg-yellow-100 text-yellow-700
                        @else bg-red-100 text-red-700 @endif">{{ __t($user->status) }}</span>
                </p>
                @if($candidacy)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500">{{ __t('my_candidacy_application') }}</p>
                        <p class="text-xs mt-1">
                            <span class="font-medium">{{ __t('application_status') }}:</span>
                            <span class="text-xs px-2 py-0.5 rounded
                                @if($candidacy->status === 'approved') bg-green-100 text-green-700
                                @elseif($candidacy->status === 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">{{ __t($candidacy->status) }}</span>
                        </p>
                    </div>
                @elseif($user->role !== 'voter')
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <a href="{{ route('candidates.apply') }}" class="block w-full text-center bg-blue-900 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-800 text-xs">{{ __t('candidate_apply_link') }}</a>
                    </div>
                @endif
                <a href="{{ route('profile') }}" class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-xs font-medium">{{ __t('view_profile') }} &rarr;</a>
            </div>
        </div>

        {{-- Announcements --}}
        <div class="bg-white rounded-lg shadow fade-in-up stagger-5">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">{{ __t('announcements') }}</h3>
            </div>
            <div class="p-4 space-y-3 text-sm">
                @forelse($recentAnnouncements as $ann)
                    <div class="border-l-4 border-blue-500 pl-3">
                        <p class="text-gray-700">{{ $ann->details }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $ann->timestamp->format('d M Y H:i') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">{{ __t('no_announcements') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Security Info --}}
    <div class="bg-white rounded-lg shadow p-4 fade-in-up stagger-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <span class="font-medium">{{ __t('last_login') }}:</span>
                @if($lastLogin)
                    {{ $lastLogin->timestamp->format('d M Y H:i:s') }} ({{ $lastLogin->ip_address ?? '-' }})
                @else
                    {{ __t('no_data') }}
                @endif
            </div>
            <a href="{{ route('profile') }}" class="text-sm text-blue-600 hover:text-blue-800">{{ __t('change_password') }}</a>
        </div>
    </div>
</div>
@endsection