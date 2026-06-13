@extends('layouts.app')
@section('title', __t('results'))
@section('content')
@php
    $grouped = [];
    foreach ($resultsData as $eid => $data) {
        $type = $data['election']->election_type;
        $grouped[$type][] = $data;
    }
@endphp

{{-- Header --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white mb-8 scale-in">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="relative z-10 px-8 py-10 text-center">
        <div class="flex justify-center mb-4">
            <div class="bg-white/20 backdrop-blur-sm rounded-full p-3 inline-flex shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ __t('results') }}</h1>
        <p class="text-blue-200 text-sm max-w-lg mx-auto">{{ __t('results_subtitle') }}</p>
    </div>
</div>

{{-- Search Bar --}}
<div class="mb-8 fade-in-up">
    <form method="GET" action="{{ route('results') }}" id="searchForm" class="max-w-2xl mx-auto">
        <div class="relative">
            <div class="flex items-center bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden focus-within:ring-2 focus-within:ring-blue-400 focus-within:border-blue-400 transition-all">
                <div class="pl-5 pr-3 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" id="searchInput" value="{{ e($search) }}"
                    list="regionSuggestions"
                    class="flex-1 py-4 px-2 text-gray-700 placeholder-gray-400 border-0 focus:ring-0 text-base outline-none"
                    placeholder="{{ __t('search_region_placeholder') }}"
                    autocomplete="off">
                <datalist id="regionSuggestions">
                    @foreach($suggestions as $s)
                    <option value="{{ e($s) }}">
                    @endforeach
                </datalist>
                @if($search)
                <a href="{{ route('results') }}" class="px-4 text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
                @endif
                <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white font-semibold px-6 py-4 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span class="hidden sm:inline ml-2">{{ __t('search') }}</span>
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Search Results Summary --}}
@if($search)
<div class="mb-8 fade-in-up">
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 text-center">
        <div class="flex items-center justify-center gap-2 mb-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <h2 class="text-lg font-bold text-blue-900">{{ __t('showing_results_for') }} <span class="text-blue-700">"{{ e($search) }}"</span></h2>
        </div>
        @php
            $totalMatchedVotes = 0;
            $totalMatchedConstituencies = [];
            foreach ($resultsData as $data) {
                $totalMatchedVotes += $data['total_votes'];
                $totalMatchedConstituencies = array_merge($totalMatchedConstituencies, $data['matched_constituencies'] ?? []);
            }
            $totalMatchedConstituencies = array_unique($totalMatchedConstituencies);
        @endphp
        <div class="flex flex-wrap justify-center gap-6 mt-3">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-900">{{ number_format($totalMatchedVotes) }}</div>
                <div class="text-xs text-gray-500">{{ __t('total_votes') }}</div>
            </div>
            @if(count($totalMatchedConstituencies) > 0)
            <div class="text-center">
                <div class="text-2xl font-bold text-green-700">{{ count($totalMatchedConstituencies) }}</div>
                <div class="text-xs text-gray-500">{{ __t('constituencies_found') }}</div>
            </div>
            @endif
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-700">{{ count($resultsData) }}</div>
                <div class="text-xs text-gray-500">{{ __t('elections_found') }}</div>
            </div>
        </div>
        @if(count($totalMatchedConstituencies) > 0)
        <div class="mt-3 flex flex-wrap justify-center gap-2">
            @foreach($totalMatchedConstituencies as $mc)
            <span class="text-xs px-3 py-1 rounded-full bg-white border border-blue-200 text-blue-700 font-medium">{{ $mc }}</span>
            @endforeach
        </div>
        @endif
    </div>
</div>
@else
<div class="mb-8 fade-in-up">
    <div class="bg-white rounded-2xl border border-gray-200 border-dashed p-8 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ __t('search_region_title') }}</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto">{{ __t('search_region_guide') }}</p>
    </div>
</div>
@endif

@if(empty($resultsData) || (collect($resultsData)->sum('total_votes') === 0 && $search))
    <div class="bg-white rounded-xl shadow-md p-12 text-center fade-in-up">
        <div class="text-6xl mb-4 opacity-30">
            <svg class="w-20 h-20 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <p class="text-gray-500 text-lg">{{ __t('no_results_found') }}</p>
        <p class="text-gray-400 text-sm mt-2">{{ __t('try_different_search') }}</p>
    </div>
@elseif(!empty($resultsData) && collect($resultsData)->sum('total_votes') > 0)
    {{-- Position Filter Tabs --}}
    <div class="mb-8 fade-in-up">
        <div class="flex flex-wrap gap-2" id="positionTabs">
            <button class="position-tab active px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 bg-blue-900 text-white shadow-lg" data-position="all">
                <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                {{ __t('all_results') }}
            </button>
            @foreach($positions as $position)
                @if(isset($grouped[$position->slug]) && collect($grouped[$position->slug])->sum('total_votes') > 0)
                <button class="position-tab px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 bg-white text-gray-700 border border-gray-200 hover:border-blue-300 hover:text-blue-900 hover:shadow-md" data-position="{{ $position->slug }}">
                    {{ session('lang') == 'sw' ? $position->name_sw : $position->name_en }}
                    <span class="ml-1.5 text-xs px-1.5 py-0.5 rounded-full bg-gray-100 text-gray-500">{{ collect($grouped[$position->slug])->sum('total_votes') }}</span>
                </button>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Results Cards by Position --}}
    <div class="space-y-8" id="resultsContainer">
        @foreach($grouped as $positionSlug => $positionResults)
        <div class="position-group" data-position="{{ $positionSlug }}">
            @foreach($positionResults as $data)
            @if($data['total_votes'] === 0) @continue @endif
            @php
                $pos = $positions->firstWhere('slug', $positionSlug);
            @endphp
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6 card-hover fade-in-up">
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-white">{{ session('lang') == 'sw' ? $data['election']->title_sw : $data['election']->title_en }}</h3>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs text-blue-200">{{ $pos ? (session('lang') == 'sw' ? $pos->name_sw : $pos->name_en) : $positionSlug }}</span>
                            <span class="text-blue-200">|</span>
                            <span class="text-xs text-blue-200">{{ __t('total_votes') }}: <strong class="text-white">{{ number_format($data['total_votes']) }}</strong></span>
                            @if($search && count($data['matched_constituencies'] ?? []))
                            <span class="text-blue-200">|</span>
                            <span class="text-xs text-blue-200">{{ __t('constituency') }}: <strong class="text-white">{{ implode(', ', $data['matched_constituencies']) }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($data['election']->winner_declared)
                        <span class="text-xs px-3 py-1 rounded-full bg-yellow-400 text-yellow-900 font-bold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ __t('winner_declared_label') }}
                        </span>
                        @endif
                        <div class="flex items-center gap-1">
                            <a href="{{ route('results.export', $data['election']->id) }}" class="text-xs bg-white/20 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg hover:bg-white/30 transition font-medium" title="{{ __t('download_csv') }}">
                                <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                CSV
                            </a>
                            <a href="{{ route('results.export_pdf', $data['election']->id) }}" class="text-xs bg-white/20 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg hover:bg-white/30 transition font-medium" target="_blank" title="{{ __t('download_pdf') }}">
                                <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                PDF
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Candidates List --}}
                <div class="p-6">
                    @if(count($data['constituency_groups'] ?? []) > 0)
                        {{-- Constituency Grouped View (parliamentary + search) --}}
                        <div class="space-y-6">
                            @foreach($data['constituency_groups'] as $cgIdx => $cg)
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-100 to-blue-50 px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <h4 class="font-bold text-indigo-900 text-lg">{{ $cg['constituency'] }}</h4>
                                    </div>
                                    <span class="text-xs bg-white px-3 py-1 rounded-full text-gray-600 font-medium">{{ __t('total_votes') }}: <strong class="text-indigo-700">{{ number_format($cg['total_votes']) }}</strong></span>
                                </div>
                                <div class="p-4 space-y-1">
                                    @foreach($cg['candidates'] as $item)
                                    <div class="flex items-center py-2.5 px-3 rounded-xl transition-all duration-200 hover:bg-gray-50 {{ $data['election']->winner_declared && $loop->first ? 'bg-yellow-50 border border-yellow-200' : 'border border-transparent' }}">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs mr-3 flex-shrink-0 shadow-sm
                                            @if($loop->first) bg-gradient-to-br from-yellow-400 to-yellow-500 text-white
                                            @elseif($loop->iteration === 2) bg-gradient-to-br from-gray-300 to-gray-400 text-white
                                            @elseif($loop->iteration === 3) bg-gradient-to-br from-orange-500 to-orange-600 text-white
                                            @else bg-gray-100 text-gray-600 border border-gray-200 @endif">
                                            @if($data['election']->winner_declared && $loop->first)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @else
                                            {{ $item['rank'] }}
                                            @endif
                                        </div>
                                        <div class="flex items-center flex-1 space-x-3">
                                            @if($item['candidate']->photo)
                                            <img src="{{ asset($item['candidate']->photo) }}" alt="" class="h-10 w-10 rounded-full object-cover ring-2 {{ $data['election']->winner_declared && $loop->first ? 'ring-yellow-400' : 'ring-gray-200' }}">
                                            @else
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-800 text-sm font-bold ring-2 {{ $data['election']->winner_declared && $loop->first ? 'ring-yellow-400' : 'ring-gray-200' }}">
                                                {{ substr($item['candidate']->full_name ?? $item['candidate']->user->full_name, 0, 1) }}
                                            </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <h4 class="font-semibold text-gray-800 text-sm {{ $data['election']->winner_declared && $loop->first ? 'text-yellow-900' : '' }}">
                                                        {{ $item['candidate']->full_name ?? $item['candidate']->user->full_name }}
                                                    </h4>
                                                    @if($data['election']->winner_declared && $loop->first)
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800 font-bold">{{ __t('winner') }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                                    <span class="text-xs font-medium text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded-full">{{ $item['candidate']->party_abbreviation }}</span>
                                                    @if($item['candidate']->running_mate_name)
                                                    <span class="text-xs text-gray-500">{{ __t('running_mate') }}: {{ $item['candidate']->running_mate_name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 ml-3">
                                            <div class="hidden sm:block w-20">
                                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                                    <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-400 transition-all duration-500" style="width: {{ $item['percentage'] }}%"></div>
                                                </div>
                                            </div>
                                            <div class="text-right min-w-[80px]">
                                                <div class="text-base font-bold {{ $data['election']->winner_declared && $loop->first ? 'text-yellow-800' : 'text-blue-900' }}">{{ number_format($item['vote_count']) }}</div>
                                                <div class="text-xs text-gray-500">{{ $item['percentage'] }}%</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Flat List View (presidential or no search) --}}
                        <div class="space-y-1">
                            @foreach($data['candidates'] as $item)
                            <div class="flex items-center py-3 px-4 rounded-xl transition-all duration-200 hover:bg-gray-50 {{ $data['election']->winner_declared && $loop->first ? 'bg-yellow-50 border border-yellow-200' : 'border border-transparent' }}">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm mr-4 flex-shrink-0 shadow-sm
                                    @if($loop->first) bg-gradient-to-br from-yellow-400 to-yellow-500 text-white
                                    @elseif($loop->iteration === 2) bg-gradient-to-br from-gray-300 to-gray-400 text-white
                                    @elseif($loop->iteration === 3) bg-gradient-to-br from-orange-500 to-orange-600 text-white
                                    @else bg-gray-100 text-gray-600 border border-gray-200 @endif">
                                    @if($data['election']->winner_declared && $loop->first)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        {{ $item['rank'] }}
                                    @endif
                                </div>
                                <div class="flex items-center flex-1 space-x-4">
                                    @if($item['candidate']->photo)
                                    <img src="{{ asset($item['candidate']->photo) }}" alt="" class="h-11 w-11 rounded-full object-cover ring-2 {{ $data['election']->winner_declared && $loop->first ? 'ring-yellow-400' : 'ring-gray-200' }}">
                                    @else
                                    <div class="h-11 w-11 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-800 text-sm font-bold ring-2 {{ $data['election']->winner_declared && $loop->first ? 'ring-yellow-400' : 'ring-gray-200' }}">
                                        {{ substr($item['candidate']->full_name ?? $item['candidate']->user->full_name, 0, 1) }}
                                    </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="font-semibold text-gray-800 {{ $data['election']->winner_declared && $loop->first ? 'text-yellow-900' : '' }}">
                                                {{ $item['candidate']->full_name ?? $item['candidate']->user->full_name }}
                                            </h4>
                                            @if($data['election']->winner_declared && $loop->first)
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800 font-bold">{{ __t('winner') }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3 mt-0.5 flex-wrap">
                                            <span class="text-xs font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">{{ $item['candidate']->party_abbreviation }}</span>
                                            @if($data['election']->election_type === 'presidential' && $item['candidate']->running_mate_name)
                                            <span class="text-xs text-gray-500">{{ __t('running_mate') }}: {{ $item['candidate']->running_mate_name }}</span>
                                            @endif
                                            @if($item['candidate']->constituency)
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $item['candidate']->constituency }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 ml-4">
                                    <div class="hidden sm:block w-28">
                                        <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-400 transition-all duration-500" style="width: {{ $item['percentage'] }}%"></div>
                                        </div>
                                    </div>
                                    <div class="text-right min-w-[100px]">
                                        <div class="text-lg font-bold {{ $data['election']->winner_declared && $loop->first ? 'text-yellow-800' : 'text-blue-900' }}">{{ number_format($item['vote_count']) }}</div>
                                        <div class="text-xs text-gray-500">{{ $item['percentage'] }}%</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.position-tab');
    const groups = document.querySelectorAll('.position-group');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => {
                t.classList.remove('active', 'bg-blue-900', 'text-white', 'shadow-lg');
                t.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-200');
            });
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-200');
            this.classList.add('active', 'bg-blue-900', 'text-white', 'shadow-lg');

            const position = this.dataset.position;
            groups.forEach(group => {
                if (position === 'all' || group.dataset.position === position) {
                    group.style.display = 'block';
                    group.style.animation = 'fadeInUp 0.3s ease-out';
                } else {
                    group.style.display = 'none';
                }
            });
        });
    });

    const cards = document.querySelectorAll('.card-hover');
    cards.forEach((card, i) => {
        card.style.animationDelay = (i * 0.05) + 's';
    });
});
</script>
@endpush

<style>
.card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,0,0,0.1); }
.position-tab { transition: all 0.2s ease; }
.position-tab.active { transform: scale(1.02); }
.scale-in { animation: scaleIn 0.4s ease-out; }
@keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
.fade-in-up { animation: fadeInUp 0.4s ease-out; }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
