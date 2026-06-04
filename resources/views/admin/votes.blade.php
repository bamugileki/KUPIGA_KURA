@extends('layouts.admin')
@section('title', __t('votes'))
@section('content')
<div class="space-y-6">
    <div class="flex justify-end mb-2">
        <a href="{{ route('admin.votes.delete_all') }}" class="btn-anim bg-red-700 text-white px-4 py-2 rounded text-sm hover:bg-red-600" onclick="return confirm('Are you sure you want to delete ALL votes? This cannot be undone.')">{{ __t('delete_all_votes') ?? 'Delete All Votes' }}</a>
    </div>
    <div class="grid md:grid-cols-3 gap-4">
        <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-blue-900 stagger-1">
            <div class="text-2xl font-bold text-blue-900">{{ $votes->count() }}</div>
            <div class="text-xs text-gray-600">{{ __t('total_votes_cast') }}</div>
        </div>
        <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-green-500 stagger-2">
            <div class="text-2xl font-bold text-green-600">{{ $voterCount }}</div>
            <div class="text-xs text-gray-600">{{ __t('eligible_voters') }}</div>
        </div>
        <div class="card-hover bg-white rounded-lg shadow p-4 border-l-4 border-purple-500 stagger-3">
            @php $pct = $voterCount > 0 ? round(($votes->count() / $voterCount) * 100, 1) : 0; @endphp
            <div class="text-2xl font-bold text-purple-600">{{ $pct }}%</div>
            <div class="text-xs text-gray-600">{{ __t('turnout') }}</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">{{ __t('live_monitoring') }}</h3>
            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">{{ __t('live') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('voter') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('candidate_role') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('election_title') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('timestamp') }}</th>
                        <th class="text-left py-2 px-4 text-xs text-gray-600 uppercase">{{ __t('ip_address') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($votes as $vote)
                    <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $vote->voter->full_name ?? 'Unknown' }}</td>
                        <td class="py-2 px-4">{{ $vote->candidate->user->full_name ?? 'Unknown' }}</td>
                        <td class="py-2 px-4">{{ $vote->election->title_en ?? 'Unknown' }}</td>
                        <td class="py-2 px-4 text-gray-600">{{ $vote->timestamp->format('Y-m-d H:i:s') }}</td>
                        <td class="py-2 px-4 text-xs text-gray-500">{{ '- -' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-6 text-center text-gray-500">{{ __t('no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">{{ __t('elections') }}</h3>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
            @forelse($elections as $election)
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</h4>
                <div class="flex items-center justify-between mt-2 text-sm">
                    <span class="text-gray-600">{{ __t($election->status) }}</span>
                    <span class="font-bold text-blue-900">{{ $election->votes->count() }} {{ __t('votes') }}</span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 col-span-full text-center py-4">{{ __t('no_data') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection