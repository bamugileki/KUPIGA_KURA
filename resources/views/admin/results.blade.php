@extends('layouts.admin')
@section('title', __t('election_results'))
@section('content')
<div class="space-y-6">
    <div class="flex justify-end">
        <a href="{{ route('admin.results.clear') }}" class="btn-anim bg-red-700 text-white px-4 py-2 rounded text-sm hover:bg-red-600" onclick="return confirm('Are you sure you want to clear ALL results? This will delete all votes and cannot be undone.')">{{ __t('clear_all_results') ?? 'Clear All Results' }}</a>
    </div>
    @forelse($resultsData as $i => $data)
    <div class="card-hover bg-white rounded-lg shadow stagger-{{ min($i + 1, 10) }}">
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800">{{ session('lang') == 'sw' ? $data['election']->title_sw : $data['election']->title_en }}</h3>
                <span class="text-xs text-gray-500">{{ __t($data['election']->election_type) }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-xs px-2 py-1 rounded-full font-medium
                    @if($data['election']->status === 'active') bg-green-100 text-green-700
                    @elseif($data['election']->status === 'closed') bg-gray-100 text-gray-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ __t($data['election']->status) }}
                    @if($data['election']->status === 'active')
                        <span class="ml-1 w-2 h-2 bg-green-500 rounded-full inline-block animate-pulse"></span>
                    @endif
                </span>
                <div class="flex items-center space-x-3">
                    <span class="text-xs text-gray-500">{{ __t('total_votes') }}: {{ $data['total_votes'] ?? 0 }}</span>
                    @if(in_array($data['election']->status, ['active', 'closed']))
                    <a href="{{ route('admin.results.export', $data['election']->id) }}" class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">{{ __t('export_results') }}</a>
                    @endif
                </div>
            </div>
        </div>
        @if(in_array($data['election']->status, ['active', 'closed']))
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('result_rank') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('result_candidate') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('party_abbreviation') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('result_votes') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('result_percentage') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['candidates'] as $result)
                    <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                        <td class="py-2 px-4 font-bold text-gray-700">{{ $result['rank'] }}</td>
                        <td class="py-2 px-4">
                            <div class="flex items-center space-x-2">
                                @if($result['candidate']->photo)
                                <img src="{{ asset($result['candidate']->photo) }}" alt="" class="h-8 w-8 rounded-full object-cover">
                                @endif
                                <span>{{ $result['candidate']->full_name ?? $result['candidate']->user->full_name }}</span>
                            </div>
                        </td>
                        <td class="py-2 px-4">
                            <div class="flex items-center space-x-1">
                                @if($result['candidate']->party_logo)
                                <img src="{{ asset($result['candidate']->party_logo) }}" alt="" class="h-5 w-5 object-contain">
                                @endif
                                <span>{{ $result['candidate']->party_abbreviation }}</span>
                            </div>
                        </td>
                        <td class="py-2 px-4 font-semibold">{{ $result['vote_count'] }}</td>
                        <td class="py-2 px-4">
                            @php $pct = $result['percentage']; @endphp
                            <div class="flex items-center">
                                <div class="bg-gray-200 rounded-full h-2 w-24 mr-2">
                                    <div class="bg-blue-900 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="font-medium">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 text-center py-6 text-sm">{{ __t('no_results') }}</p>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-500">{{ __t('no_results') }}</p>
    </div>
    @endforelse
</div>
<style>
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
.animate-pulse { animation: pulse 1.5s ease-in-out infinite; }
</style>
@endsection