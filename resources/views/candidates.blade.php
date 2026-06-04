@extends('layouts.app')
@section('title', __t('candidates'))
@section('content')
<h2 class="text-2xl font-bold text-blue-900 mb-4">{{ __t('candidates') }}</h2>
@if($election)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-bold text-lg text-blue-900 mb-4">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</h3>
        @if(count($candidates) > 0)
            <div class="grid md:grid-cols-3 gap-4">
                @foreach($candidates as $cand)
                <div class="border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition">
                    <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto mb-3 flex items-center justify-center text-gray-500 text-2xl">V</div>
                    <h4 class="font-bold text-gray-800">{{ $cand->user->full_name }}</h4>
                    <p class="text-sm text-gray-500">{{ __t($cand->position) }}@if($cand->constituency) - {{ $cand->constituency }}@endif</p>
                    @if($cand->manifesto)
                    <p class="text-xs text-gray-400 mt-2">{{ Str::limit($cand->manifesto, 150) }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 py-8">{{ __t('no_candidates') }}</p>
        @endif
    </div>
@else
    <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">{{ __t('no_candidates') }}</div>
@endif
@endsection
