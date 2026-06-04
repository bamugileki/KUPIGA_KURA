@extends('layouts.admin')
@section('title', __t('objections'))
@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-900">{{ __t('objections') }}</h2>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left py-3 px-4">#</th>
                    <th class="text-left py-3 px-4">{{ __t('objection_type') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('objector') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('reason') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('status') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('created_at') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($objections as $objection)
                <tr class="border-t border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">#{{ $objection->id }}</td>
                    <td class="py-3 px-4">
                        <span class="text-xs px-2 py-0.5 rounded
                            @if($objection->type === 'nomination') bg-blue-100 text-blue-800
                            @elseif($objection->type === 'election') bg-orange-100 text-orange-800
                            @else bg-purple-100 text-purple-800 @endif">
                            @if($objection->type === 'nomination') {{ __t('nomination_objection') }}
                            @elseif($objection->type === 'election') {{ __t('election_objection') }}
                            @else {{ __t('election_petition') }} @endif
                        </span>
                    </td>
                    <td class="py-3 px-4">{{ $objection->objector->full_name ?? 'Unknown' }}</td>
                    <td class="py-3 px-4 max-w-xs truncate">{{ Str::limit($objection->reason, 60) }}</td>
                    <td class="py-3 px-4">
                        <span class="text-xs px-2 py-0.5 rounded
                            @if($objection->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($objection->status === 'upheld') bg-red-100 text-red-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ __t($objection->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $objection->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.objections.view', $objection->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">{{ __t('view') }}</a>
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
