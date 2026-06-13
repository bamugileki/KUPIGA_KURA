@extends('layouts.admin')
@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
@endpush
@section('title', __t('election_results'))
@section('subtitle', __t('election_results_subtitle'))
@section('content')
<div class="space-y-6">
    @forelse($resultsData as $i => $data)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden card-hover">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-2">
                    <h3 class="font-semibold text-gray-900">{{ session('lang') == 'sw' ? $data['election']->title_sw : $data['election']->title_en }}</h3>
                    @if($data['election']->winner_declared)
                    <span class="badge bg-yellow-100 text-yellow-800 font-semibold">
                        <svg class="w-3.5 h-3.5 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ __t('winner_declared_label') ?? 'Winner Declared' }}
                    </span>
                    @endif
                </div>
                @php $pos = \App\Models\Position::where('slug', $data['election']->election_type)->first(); @endphp
                <span class="text-xs text-gray-400">{{ session('lang') == 'sw' ? ($pos->name_sw ?? $data['election']->election_type) : ($pos->name_en ?? $data['election']->election_type) }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="badge
                    @if($data['election']->status === 'active') bg-green-100 text-green-700
                    @elseif($data['election']->status === 'closed') bg-gray-100 text-gray-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ __t($data['election']->status) }}
                    @if($data['election']->status === 'active')
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full inline-block ml-1 animate-pulse"></span>
                    @endif
                </span>
                <span class="text-xs text-gray-500 font-medium">{{ __t('total_votes') }}: <strong>{{ $data['total_votes'] ?? 0 }}</strong></span>
                @if(in_array($data['election']->status, ['active', 'closed']))
                <a href="{{ route('admin.results.export', $data['election']->id) }}" class="btn bg-green-50 text-green-700 px-3 py-1.5 rounded-lg text-xs font-medium border border-green-200 hover:bg-green-100">
                    <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    CSV
                </a>
                <a href="{{ route('admin.results.export_pdf', $data['election']->id) }}" class="btn bg-red-50 text-red-700 px-3 py-1.5 rounded-lg text-xs font-medium border border-red-200 hover:bg-red-100" target="_blank">
                    <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    PDF
                </a>
                @endif
            </div>
        </div>
        @if(in_array($data['election']->status, ['active', 'closed']))
        <div class="grid md:grid-cols-2 gap-5 p-5">
            <div class="bg-gray-50 rounded-xl p-4">
                <canvas id="chart_{{ $data['election']->id }}" height="200"></canvas>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 rounded-lg">
                            <th class="text-left py-2.5 px-4 text-xs text-gray-500 uppercase font-semibold tracking-wider">#</th>
                            <th class="text-left py-2.5 px-4 text-xs text-gray-500 uppercase font-semibold tracking-wider">{{ __t('result_candidate') }}</th>
                            @if($data['election']->election_type === 'presidential')
                            <th class="text-left py-2.5 px-4 text-xs text-gray-500 uppercase font-semibold tracking-wider">{{ __t('running_mate') }}</th>
                            @endif
                            <th class="text-left py-2.5 px-4 text-xs text-gray-500 uppercase font-semibold tracking-wider">{{ __t('party_abbreviation') }}</th>
                            <th class="text-left py-2.5 px-4 text-xs text-gray-500 uppercase font-semibold tracking-wider">{{ __t('result_votes') }}</th>
                            <th class="text-left py-2.5 px-4 text-xs text-gray-500 uppercase font-semibold tracking-wider">{{ __t('result_percentage') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['candidates'] as $result)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors {{ $data['election']->winner_declared && $loop->first ? 'bg-yellow-50/50' : '' }}">
                            <td class="py-2.5 px-4">
                                @if($data['election']->winner_declared && $loop->first)
                                    <span class="text-yellow-500 font-bold">&#9733;</span>
                                @endif
                                <span class="font-semibold text-gray-700">{{ $result['rank'] }}</span>
                            </td>
                            <td class="py-2.5 px-4">
                                <div class="flex items-center space-x-2.5">
                                    @if($result['candidate']->photo)
                                    <img src="{{ asset($result['candidate']->photo) }}" alt="" class="h-8 w-8 rounded-full object-cover ring-2 ring-gray-100">
                                    @else
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">{{ substr($result['candidate']->full_name ?? $result['candidate']->user->full_name, 0, 1) }}</div>
                                    @endif
                                    <span class="{{ $data['election']->winner_declared && $loop->first ? 'font-bold text-yellow-800' : 'text-gray-900' }}">{{ $result['candidate']->full_name ?? $result['candidate']->user->full_name }}</span>
                                </div>
                            </td>
                            @if($data['election']->election_type === 'presidential')
                            <td class="py-2.5 px-4 text-sm text-gray-500">{{ $result['candidate']->running_mate_name ?? '—' }}</td>
                            @endif
                            <td class="py-2.5 px-4">
                                <div class="flex items-center space-x-1.5">
                                    @if($result['candidate']->party_logo)
                                    <img src="{{ asset($result['candidate']->party_logo) }}" alt="" class="h-5 w-5 object-contain">
                                    @endif
                                    <span class="font-medium text-gray-700">{{ $result['candidate']->party_abbreviation }}</span>
                                </div>
                            </td>
                            <td class="py-2.5 px-4 font-bold text-gray-900">{{ number_format($result['vote_count']) }}</td>
                            <td class="py-2.5 px-4">
                                @php $pct = $result['percentage']; @endphp
                                <div class="flex items-center space-x-2">
                                    <div class="bg-gray-200 rounded-full h-2 w-20">
                                        <div class="h-2 rounded-full" style="width: {{ $pct }}%; background: {{ $loop->first && $data['election']->winner_declared ? '#ca8a04' : '#3b82f6' }}"></div>
                                    </div>
                                    <span class="font-medium text-sm {{ $loop->first && $data['election']->winner_declared ? 'text-yellow-600' : 'text-gray-700' }}">{{ $pct }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <p class="text-gray-400 text-center py-8 text-sm">{{ __t('no_results') }}</p>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <p class="text-gray-500 text-sm">{{ __t('no_results') }}</p>
    </div>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var colors = ['#2563eb', '#059669', '#7c3aed', '#d97706', '#1d4ed8', '#dc2626', '#0891b2', '#ca8a04', '#9333ea', '#15803d'];
    @foreach($resultsData as $data)
    @if(in_array($data['election']->status, ['active', 'closed']))
    (function() {
        var ctx = document.getElementById('chart_{{ $data['election']->id }}');
        if (!ctx) return;
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($data['candidates'] as $r)
                        '{{ ($r["candidate"]->full_name ?? $r["candidate"]->user->full_name) }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Votes',
                    data: [
                        @foreach($data['candidates'] as $r)
                            {{ $r['vote_count'] }},
                        @endforeach
                    ],
                    backgroundColor: colors.slice(0, {{ count($data['candidates']) }}),
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' votes (' +
                                    @json($data['total_votes']) > 0
                                    ? Math.round(context.parsed.y / {{ $data['total_votes'] }} * 100) + '%)'
                                    : '0%)';
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
    })();
    @endif
    @endforeach
});
</script>
<style>
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
.animate-pulse { animation: pulse 1.5s ease-in-out infinite; }
</style>
@endsection
