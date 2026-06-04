@extends('layouts.admin')
@section('title', __t('code_conduct_violations'))
@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-900">{{ __t('code_conduct_violations') }}</h2>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left py-3 px-4">#</th>
                    <th class="text-left py-3 px-4">{{ __t('reported_by') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('accused') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('description') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('status') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('created_at') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($violations as $violation)
                <tr class="border-t border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">#{{ $violation->id }}</td>
                    <td class="py-3 px-4">{{ $violation->reporter->full_name ?? 'Unknown' }}</td>
                    <td class="py-3 px-4">{{ $violation->accused->full_name ?? ($violation->candidate->full_name ?? 'N/A') }}</td>
                    <td class="py-3 px-4 max-w-xs truncate">{{ Str::limit($violation->description, 60) }}</td>
                    <td class="py-3 px-4">
                        <span class="text-xs px-2 py-0.5 rounded
                            @if($violation->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($violation->status === 'investigated') bg-blue-100 text-blue-800
                            @elseif($violation->status === 'substantiated') bg-red-100 text-red-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ __t($violation->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $violation->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.violations.view', $violation->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">{{ __t('view') }}</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-gray-500">{{ __t('no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
