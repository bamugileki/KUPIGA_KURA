@extends('layouts.app')
@section('title', __t('results'))
@section('content')
<h2 class="text-2xl font-bold text-blue-900 mb-4">{{ __t('results') }}</h2>
@forelse($resultsData as $eid => $data)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-xl text-blue-900">{{ session('lang') == 'sw' ? $data['election']->title_sw : $data['election']->title_en }}</h3>
                <p class="text-sm text-gray-500">{{ __t($data['election']->election_type) }}</p>
            </div>
            <span class="text-xs px-3 py-1 rounded-full font-medium
                @if($data['election']->status === 'closed') bg-green-100 text-green-700
                @else bg-yellow-100 text-yellow-700 @endif">
                {{ __t($data['election']->status) }}
            </span>
        </div>
        @if($data['election']->status === 'closed')
            <p class="text-sm text-gray-600 mb-4">{{ __t('total_votes') }}: <strong>{{ $data['total_votes'] }}</strong></p>
            <div>
                @foreach($data['candidates'] as $item)
                <div class="flex items-center py-3 border-b border-gray-100 last:border-b-0">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm mr-4 flex-shrink-0
                        @if($loop->first) bg-yellow-500 text-gray-900
                        @elseif($loop->second) bg-gray-400 text-white
                        @elseif($loop->third) bg-orange-700 text-white
                        @else bg-blue-900 text-white @endif">
                        {{ $item['rank'] }}
                    </div>
                    <div class="flex items-center flex-1 space-x-3">
                        @if($item['candidate']->photo)
                        <img src="{{ asset($item['candidate']->photo) }}" alt="" class="h-10 w-10 rounded-full object-cover">
                        @else
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-sm font-bold">{{ substr($item['candidate']->full_name ?? $item['candidate']->user->full_name, 0, 1) }}</div>
                        @endif
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $item['candidate']->full_name ?? $item['candidate']->user->full_name }}</h4>
                            <p class="text-xs text-blue-900 font-medium">{{ $item['candidate']->party_abbreviation }}</p>
                        </div>
                    </div>
                    <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden mr-4 hidden md:block">
                        <div class="h-full bg-blue-900 rounded-full" style="width: {{ $item['percentage'] }}%"></div>
                    </div>
                    <div class="font-bold text-blue-900 text-right min-w-[80px]">{{ $item['vote_count'] }} ({{ $item['percentage'] }}%)</div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-gray-500">{{ __t('results_pending') }}</p>
            </div>
        @endif
    </div>
@empty
    <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">{{ __t('no_results') }}</div>
@endforelse
@endsection