@extends('layouts.app')
@section('title', __t('dashboard'))
@section('content')
<h2 class="text-2xl font-bold text-blue-900 mb-4">{{ __t('welcome') }}, {{ Auth::user()->full_name }}!</h2>

@if($activeElections->count() > 0)
    <h3 class="text-xl font-semibold text-gray-700 mb-3">{{ __t('active_elections') }}</h3>
    <div class="grid md:grid-cols-2 gap-4">
        @foreach($activeElections as $election)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-bold text-lg text-blue-900">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</h4>
                    <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-800">{{ __t($election->status) }}</span>
                </div>
                <p class="text-sm text-gray-600 mb-2">
                    <strong>{{ __t('election_type') }}:</strong> {{ __t($election->election_type) }}<br>
                    <strong>{{ __t('voting_period') }}:</strong> {{ $election->start_time->format('Y-m-d H:i') }} - {{ $election->end_time->format('Y-m-d H:i') }}
                </p>
                @if(in_array($election->id, $votedElectionIds))
                    <p class="text-green-600 font-semibold">{{ __t('already_voted') }}</p>
                @elseif($election->status === 'voting_open' && $now >= $election->start_time && $now <= $election->end_time)
                    <a href="{{ route('vote.form', $election->id) }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">{{ __t('cast_vote') }}</a>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">{{ __t('no_data') }}</div>
@endif

@if($userCandidacy)
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="font-bold text-lg text-blue-900 mb-3">{{ __t('my_candidacy') }}</h3>
        <p><strong>{{ __t('position_label') }}:</strong> {{ __t($userCandidacy->position) }}</p>
        @if($userCandidacy->constituency)<p><strong>{{ __t('constituency') }}:</strong> {{ $userCandidacy->constituency }}</p>@endif
        <p><strong>{{ __t('status') }}:</strong> <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-800">{{ __t($userCandidacy->status) }}</span></p>
    </div>
@endif
@endsection
