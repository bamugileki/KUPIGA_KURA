@extends('layouts.admin')
@section('title', __t('assisted_votes'))
@section('subtitle', __t('assisted_votes_subtitle'))
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">{{ __t('assisted_votes') }}</h3>
        <span class="badge bg-blue-50 text-blue-700">{{ $assistedVotes->count() }} {{ __t('total') }}</span>
    </div>
    <div class="overflow-x-auto table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __t('voter') }}</th>
                    <th>{{ __t('officer') }}</th>
                    <th>{{ __t('election_title') }}</th>
                    <th>{{ __t('assistance_reason') }}</th>
                    <th>{{ __t('timestamp') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assistedVotes as $av)
                <tr>
                    <td class="text-gray-400 text-xs">{{ $av->id }}</td>
                    <td class="font-medium text-gray-900">{{ $av->voter->full_name ?? 'Unknown' }}</td>
                    <td class="text-gray-700">{{ $av->officer->full_name ?? 'Unknown' }}</td>
                    <td class="text-gray-600">{{ $av->election->title_en ?? 'Unknown' }}</td>
                    <td class="text-gray-500 text-xs max-w-xs">{{ $av->reason ?? '-' }}</td>
                    <td class="text-gray-500 text-xs">{{ $av->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-400">{{ __t('no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
