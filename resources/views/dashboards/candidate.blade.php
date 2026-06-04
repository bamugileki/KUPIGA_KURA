@extends('layouts.app')
@section('title', __t('candidate_dashboard'))
@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    @if($candidacy)
    {{-- Overview Panel --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->full_name }}</h2>
                <div class="flex items-center space-x-3 mt-2 text-sm">
                    <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-medium">{{ __t($candidacy->position) }}</span>
                    @if($candidacy->constituency)
                        <span class="text-gray-600">{{ $candidacy->constituency }}</span>
                    @endif
                    <span class="px-2 py-0.5 rounded text-xs font-medium
                        @if($candidacy->status === 'approved') bg-green-100 text-green-700
                        @elseif($candidacy->status === 'pending') bg-yellow-100 text-yellow-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ __t($candidacy->status) }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                @if($myElections->count() > 0)
                    @php $nextElection = $myElections->first(); @endphp
                    @if($nextElection->start_time && $now->lt($nextElection->start_time))
                        <div class="text-sm">
                            <span class="text-gray-600">{{ __t('election_countdown') }}</span>
                            <div class="text-xl font-bold text-blue-900">
                                {{ $nextElection->start_time->diffInDays($now) }} {{ __t('days') }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Campaign Performance + Election Status --}}
    <div class="grid md:grid-cols-2 gap-6">
        {{-- Campaign Performance --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">{{ __t('campaign_performance') }}</h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-4xl font-bold text-blue-900">{{ $totalVotesReceived }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ __t('total_votes_received') }}</div>
                </div>

                @foreach($rankings as $electionId => $data)
                    @if(isset($data['results']) && count($data['results']) > 0)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ $data['election']->title_en ?? 'Election' }}</h4>
                            <div class="space-y-2">
                                @foreach($data['results'] as $result)
                                    <div class="flex items-center justify-between text-sm py-1 {{ isset($result['candidate_id']) && $result['candidate_id'] == $candidacy->id ? 'bg-blue-50 rounded px-2' : '' }}">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-gray-500">{{ $loop->iteration }}.</span>
                                            <span>{{ $result['candidate_name'] ?? $result['candidate'] ?? 'Unknown' }}</span>
                                            @if(isset($result['candidate_id']) && $result['candidate_id'] == $candidacy->id)
                                                <span class="text-xs text-blue-600 font-medium">{{ __t('you') }}</span>
                                            @endif
                                        </div>
                                        <span class="font-semibold">{{ $result['votes'] ?? 0 }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Election Status --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">{{ __t('election_status') }}</h3>
            </div>
            <div class="p-4 space-y-3">
                @forelse($myElections as $election)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-sm">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</h4>
                            <span class="text-xs px-2 py-0.5 rounded
                                @if($election->status === 'voting_open') bg-green-100 text-green-700
                                @elseif($election->status === 'draft') bg-gray-100 text-gray-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ __t($election->status) }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-600 mt-2">
                            {{ $election->start_time->format('d M Y H:i') }} - {{ $election->end_time->format('d M Y H:i') }}
                        </div>
                        @if($election->isVotingOpen())
                            <span class="inline-block mt-2 text-xs text-green-600 font-medium">{{ __t('voting_in_progress') }}</span>
                        @elseif($now->gt($election->end_time))
                            <span class="inline-block mt-2 text-xs text-gray-600 font-medium">{{ __t('completed') }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">{{ __t('no_elections_assigned') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Security & Compliance --}}
    <div class="grid md:grid-cols-2 gap-6">
        {{-- Approval History --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">{{ __t('approval_history') }}</h3>
            </div>
            <div class="p-4 space-y-2 text-sm">
                @forelse($approvalLogs as $log)
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <div>
                            <span class="text-xs px-2 py-0.5 rounded
                                @if(str_contains($log->action, 'APPROVED')) bg-green-100 text-green-700
                                @elseif(str_contains($log->action, 'REJECTED')) bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $log->action }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $log->timestamp->format('d M Y H:i') }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">{{ __t('no_data') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Admin Messages --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">{{ __t('admin_messages') }}</h3>
            </div>
            <div class="p-4 space-y-2 text-sm">
                @forelse($adminMessages as $msg)
                    <div class="border-l-4 border-blue-500 pl-3">
                        <p class="text-gray-700">{{ $msg->details }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $msg->timestamp->format('d M Y H:i') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">{{ __t('no_messages') }}</p>
                @endforelse
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <div class="bg-white rounded-full p-4 inline-flex items-center justify-center mx-auto mb-4 shadow-lg">
            <img src="{{ asset('images/TUME.png') }}" alt="TUME Logo" class="h-20 w-20">
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ __t('welcome') }}, {{ $user->full_name }}</h2>
        <p class="text-gray-600">{{ __t('no_candidacy_found') }}</p>
        <a href="{{ route('candidates.apply') }}" class="inline-block mt-4 bg-blue-900 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-800">{{ __t('candidate_apply_link') }}</a>
    </div>
    @endif
</div>
@endsection
