@extends('layouts.app')
@section('title', __t('home'))
@section('content')

{{-- Hero Section --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white mb-12 scale-in">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="relative z-10 px-8 py-16 md:py-24 text-center">
        <div class="flex justify-center mb-6">
            <div class="bg-white rounded-full p-4 flex items-center justify-center inline-flex shadow-xl"><img src="{{ asset('images/TUME.png') }}" alt="TUME Logo" class="h-20 w-20"></div>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __t('hero_title') }}</h1>
        <p class="text-lg md:text-xl text-blue-200 max-w-2xl mx-auto mb-8">{{ __t('hero_subtitle') }}</p>
        <div class="flex flex-wrap justify-center gap-4 mb-10">
            <a href="{{ route('login') }}" class="inline-flex items-center bg-white text-blue-900 font-bold px-8 py-3 rounded-xl hover:bg-blue-50 transition shadow-lg">{{ __t('view_elections') }}</a>
            <a href="{{ route('login') }}" class="inline-flex items-center border-2 border-white text-white font-semibold px-8 py-3 rounded-xl hover:bg-white/10 transition">{{ __t('login_to_vote') }}</a>
        </div>
        <div class="flex flex-wrap justify-center gap-6 text-sm text-blue-200">
            <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> {{ __t('one_person_one_vote') }}</span>
            <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> {{ __t('audit_protected') }}</span>
            <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> {{ __t('verified_system') }}</span>
        </div>
    </div>
</div>

{{-- Active Elections --}}
@if($activeElections->isNotEmpty())
<div class="mb-12 fade-in-up stagger-1">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">{{ __t('active_elections') }}</h2>
    <div class="space-y-4">
        @foreach($activeElections as $index => $election)
        <div class="bg-white rounded-xl shadow-md border-l-4 border-green-500 p-6 vote-card stagger-{{ min($index + 2, 8) }}">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-800">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</h3>
                    <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-600">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><span class="live-dot mr-1.5"></span>{{ __t('active') }}</span>
                        <span>{{ __t('ends') }}: {{ $election->end_time->format('d M Y – H:i') }}</span>
                        <span>{{ __t('eligible_all_voters') }}</span>
                    </div>
                </div>
                <a href="{{ route('login') }}" class="inline-flex items-center bg-green-600 text-white font-semibold px-6 py-2.5 rounded-lg hover:bg-green-700 transition whitespace-nowrap">{{ __t('vote_now') }}</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Upcoming Elections + Results Preview + Trust --}}
<div class="grid md:grid-cols-3 gap-6 mb-12">
    {{-- Upcoming Elections --}}
    <div class="bg-white rounded-xl shadow-md p-6 fade-in-up stagger-3">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">{{ __t('upcoming_elections') }}</h2>
        @if($upcomingElections->isNotEmpty())
            <ul class="space-y-3">
                @foreach($upcomingElections as $election)
                <li class="text-sm text-gray-700 border-b border-gray-100 pb-2">
                    <span class="font-medium">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</span>
                    <br><span class="text-gray-500">{{ __t('starts') }}: {{ $election->start_time->format('d M Y') }}</span>
                </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-gray-500">{{ __t('no_upcoming_elections') }}</p>
        @endif
    </div>

    {{-- Results Preview --}}
    <div class="bg-white rounded-xl shadow-md p-6 fade-in-up stagger-4">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">{{ __t('results') }}</h2>
        <div class="text-center py-8">
            <div class="text-4xl mb-3">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <p class="text-sm text-gray-600">{{ __t('results_published_after') }}</p>
            <span class="inline-block mt-2 text-xs px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">{{ __t('locked_fairness') }}</span>
        </div>
    </div>

    {{-- Security & Integrity --}}
    <div class="bg-white rounded-xl shadow-md p-6 fade-in-up stagger-5">
        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">{{ __t('security_integrity') }}</h2>
        <ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start"><svg class="w-4 h-4 mr-2 text-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> {{ __t('votes_encrypted') }}</li>
            <li class="flex items-start"><svg class="w-4 h-4 mr-2 text-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> {{ __t('one_voter_one_vote') }}</li>
            <li class="flex items-start"><svg class="w-4 h-4 mr-2 text-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> {{ __t('full_audit_trail') }}</li>
            <li class="flex items-start"><svg class="w-4 h-4 mr-2 text-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> {{ __t('commission_monitored') }}</li>
            <li class="flex items-start"><svg class="w-4 h-4 mr-2 text-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> {{ __t('no_vote_editing') }}</li>
        </ul>
    </div>
</div>

{{-- How It Works --}}
<div class="bg-white rounded-xl shadow-md p-6 mb-12 fade-in-up stagger-6">
    <h2 class="text-lg font-bold text-gray-800 mb-6 text-center">{{ __t('how_it_works') }}</h2>
    <div class="grid md:grid-cols-6 gap-4 text-center">
        <div class="flex flex-col items-center fade-in-up stagger-1">
            <div class="w-10 h-10 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold mb-2">1</div>
            <span class="text-sm text-gray-700">{{ __t('step_register') }}</span>
        </div>
        <div class="hidden md:flex items-center justify-center text-gray-300 text-2xl">→</div>
        <div class="flex flex-col items-center fade-in-up stagger-2">
            <div class="w-10 h-10 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold mb-2">2</div>
            <span class="text-sm text-gray-700">{{ __t('step_verify') }}</span>
        </div>
        <div class="hidden md:flex items-center justify-center text-gray-300 text-2xl">→</div>
        <div class="flex flex-col items-center fade-in-up stagger-3">
            <div class="w-10 h-10 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold mb-2">3</div>
            <span class="text-sm text-gray-700">{{ __t('step_login') }}</span>
        </div>
        <div class="hidden md:flex items-center justify-center text-gray-300 text-2xl">→</div>
        <div class="flex flex-col items-center fade-in-up stagger-4">
            <div class="w-10 h-10 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold mb-2">4</div>
            <span class="text-sm text-gray-700">{{ __t('step_vote') }}</span>
        </div>
        <div class="hidden md:flex items-center justify-center text-gray-300 text-2xl">→</div>
        <div class="flex flex-col items-center fade-in-up stagger-5">
            <div class="w-10 h-10 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold mb-2">5</div>
            <span class="text-sm text-gray-700">{{ __t('step_receipt') }}</span>
        </div>
    </div>
</div>

{{-- Announcements --}}
@if($announcements->isNotEmpty())
<div class="mb-12 fade-in-up stagger-7">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">{{ __t('announcements') }}</h2>
    <div class="space-y-4">
        @foreach($announcements as $a)
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 vote-card
            @if($a->priority === 'urgent') border-red-500
            @elseif($a->priority === 'high') border-orange-500
            @else border-blue-500 @endif">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-bold text-gray-800">{{ session('lang') == 'sw' ? $a->title_sw : $a->title_en }}</h3>
                <span class="text-xs px-2 py-0.5 rounded
                    @if($a->priority === 'urgent') bg-red-100 text-red-700
                    @elseif($a->priority === 'high') bg-orange-100 text-orange-700
                    @else bg-blue-100 text-blue-700 @endif">{{ __t($a->priority) }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ session('lang') == 'sw' ? $a->content_sw : $a->content_en }}</p>
            @if($a->published_at)
            <p class="text-xs text-gray-400 mt-2">{{ $a->published_at->format('d M Y H:i') }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- System Access Roles --}}
<div class="bg-white rounded-xl shadow-md p-6 mb-12 fade-in-up stagger-8">
    <h2 class="text-lg font-bold text-gray-800 mb-6 text-center">{{ __t('system_roles') }}</h2>
    <div class="grid sm:grid-cols-2 md:grid-cols-5 gap-4">
        <div class="text-center p-3 rounded-lg bg-blue-50">
            <svg class="w-8 h-8 mx-auto mb-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="font-semibold text-sm">{{ __t('voter_role') }}</p>
            <p class="text-xs text-gray-500">{{ __t('voter_role_desc') }}</p>
        </div>
        <div class="text-center p-3 rounded-lg bg-purple-50">
            <svg class="w-8 h-8 mx-auto mb-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            <p class="font-semibold text-sm">{{ __t('candidate_role') }}</p>
            <p class="text-xs text-gray-500">{{ __t('candidate_role_desc') }}</p>
        </div>
        <div class="text-center p-3 rounded-lg bg-red-50">
            <svg class="w-8 h-8 mx-auto mb-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="font-semibold text-sm">{{ __t('admin_role') }}</p>
            <p class="text-xs text-gray-500">{{ __t('admin_role_desc') }}</p>
        </div>
        <div class="text-center p-3 rounded-lg bg-yellow-50">
            <svg class="w-8 h-8 mx-auto mb-1 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <p class="font-semibold text-sm">{{ __t('officer_role') }}</p>
            <p class="text-xs text-gray-500">{{ __t('officer_role_desc') }}</p>
        </div>
        <div class="text-center p-3 rounded-lg bg-green-50">
            <svg class="w-8 h-8 mx-auto mb-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <p class="font-semibold text-sm">{{ __t('observer_role') }}</p>
            <p class="text-xs text-gray-500">{{ __t('observer_role_desc') }}</p>
        </div>
    </div>
</div>
@endsection
