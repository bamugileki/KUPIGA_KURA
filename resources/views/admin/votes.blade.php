@extends('layouts.admin')
@section('title', __t('votes'))
@section('subtitle', __t('vote_monitoring_subtitle'))
@section('content')
<div class="space-y-6">
    <div class="grid md:grid-cols-3 gap-4">
        <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('total_votes_cast') }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $votes->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center stat-icon">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('eligible_voters') }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $voterCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center stat-icon">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            @php $pct = $voterCount > 0 ? round(($votes->count() / $voterCount) * 100, 1) : 0; @endphp
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ __t('turnout') }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $pct }}%</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center stat-icon">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">{{ __t('live_monitoring') }}</h3>
            <span class="badge bg-green-100 text-green-700">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full inline-block mr-1 animate-pulse"></span>
                {{ __t('live') }}
            </span>
        </div>
        <div class="overflow-x-auto table-wrap">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>{{ __t('voter') }}</th>
                        <th>{{ __t('candidate_role') }}</th>
                        <th>{{ __t('election_title') }}</th>
                        <th>{{ __t('timestamp') }}</th>
                        <th>{{ __t('ip_address') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($votes as $vote)
                    <tr>
                        <td class="font-medium text-gray-900">{{ $vote->voter->full_name ?? 'Unknown' }}</td>
                        <td class="text-gray-700">{{ $vote->candidate->user->full_name ?? 'Unknown' }}</td>
                        <td class="text-gray-600">{{ $vote->election->title_en ?? 'Unknown' }}</td>
                        <td class="text-gray-500 text-xs">{{ $vote->timestamp->format('Y-m-d H:i:s') }}</td>
                        <td class="text-gray-400 text-xs font-mono">{{ '- -' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-10 text-gray-400">{{ __t('no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">{{ __t('elections') }}</h3>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 p-5">
            @forelse($elections as $election)
            <div class="card-hover bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900 text-sm">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</h4>
                    <span class="badge
                        @if($election->status === 'active') bg-green-100 text-green-700
                        @elseif($election->status === 'closed') bg-gray-100 text-gray-700
                        @else bg-blue-100 text-blue-700
                        @endif">{{ __t($election->status) }}</span>
                </div>
                <p class="text-xs text-gray-400 mb-3">
                    {{ $election->candidates->count() }} {{ __t('candidates') }} &middot;
                    {{ $election->votes->count() }} {{ __t('votes') }}
                </p>
                @if($election->status === 'active')
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full bg-green-500 transition-all" style="width: {{ $voterCount > 0 ? min(100, round(($election->votes->count() / $voterCount) * 100)) : 0 }}%"></div>
                </div>
                @endif
            </div>
            @empty
            <p class="text-gray-400 text-center py-6 col-span-3 text-sm">{{ __t('no_elections_found') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
