@extends('layouts.app')
@section('title', __t('dashboard'))
@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ __t('welcome') }}, {{ $user->full_name }}!</h2>
                <p class="text-sm text-gray-600 mt-1">
                    <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                        @if($user->isPollingAgent()) bg-blue-100 text-blue-800
                        @else bg-purple-100 text-purple-800 @endif">
                        {{ $user->isPollingAgent() ? __t('polling_agent_role') : __t('observer_role') }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-lg text-gray-800">{{ __t('elections') }}</h3>
        </div>
        <div class="p-6 space-y-4">
            @forelse($elections as $election)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-blue-900">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</h4>
                        <p class="text-xs text-gray-600 mt-1">
                            {{ __t($election->election_type) }}
                            | {{ $election->start_time->format('d M Y H:i') }} - {{ $election->end_time->format('d M Y H:i') }}
                        </p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded
                        @if($election->status === 'active') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-700 @endif">{{ __t($election->status) }}</span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-8">{{ __t('no_active_elections') }}</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">{{ __t('announcements') }}</h3>
        </div>
        <div class="p-4 space-y-3 text-sm">
            @forelse($announcements as $a)
            <div class="border-l-4 border-blue-500 pl-3">
                <p class="text-gray-700">{{ session('lang') == 'sw' ? $a->content_sw : $a->content_en }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $a->published_at->format('d M Y H:i') }}</p>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">{{ __t('no_announcements') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
