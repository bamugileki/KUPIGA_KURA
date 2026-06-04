@extends('layouts.admin')
@section('title', __t('assisted_votes'))
@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-900">{{ __t('assisted_votes') }}</h2>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left py-3 px-4">#</th>
                    <th class="text-left py-3 px-4">{{ __t('voter') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('assistant') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('election') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('candidate') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('assistant_relationship') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('timestamp') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assistedVotes as $av)
                <tr class="border-t border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">#{{ $av->id }}</td>
                    <td class="py-3 px-4">{{ $av->voter->full_name ?? 'Unknown' }}</td>
                    <td class="py-3 px-4">{{ $av->assistant->full_name ?? 'Unknown' }}</td>
                    <td class="py-3 px-4">{{ session('lang') == 'sw' ? $av->election->title_sw : $av->election->title_en }}</td>
                    <td class="py-3 px-4">{{ $av->candidate->full_name ?? $av->candidate->user->full_name ?? 'Unknown' }}</td>
                    <td class="py-3 px-4">{{ $av->assistant_relationship ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $av->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-gray-500">{{ __t('no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
