@extends('layouts.admin')
@section('title', __t('code_conduct_violations'))
@section('subtitle', __t('manage_violations_subtitle'))
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">{{ __t('all_violations') }}</h3>
    </div>
    <div class="overflow-x-auto table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __t('reported_by') }}</th>
                    <th>{{ __t('violation_type') }}</th>
                    <th>{{ __t('description') }}</th>
                    <th>{{ __t('status') }}</th>
                    <th>{{ __t('created_at') }}</th>
                    <th>{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($violations as $violation)
                <tr>
                    <td class="text-gray-400 text-xs">{{ $violation->id }}</td>
                    <td class="font-medium text-gray-900">{{ $violation->reporter->full_name ?? 'Unknown' }}</td>
                    <td>
                        <span class="badge bg-red-50 text-red-700">{{ $violation->type }}</span>
                    </td>
                    <td class="text-gray-600 max-w-xs truncate">{{ $violation->description }}</td>
                    <td>
                        <span class="badge
                            @if($violation->status == 'resolved') bg-green-100 text-green-700
                            @elseif($violation->status == 'investigating') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-700
                            @endif">{{ __t($violation->status) }}</span>
                    </td>
                    <td class="text-gray-500 text-xs">{{ $violation->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.violations.show', $violation->id) }}" class="btn bg-blue-50 text-blue-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-100 border border-blue-200">{{ __t('view') }}</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
