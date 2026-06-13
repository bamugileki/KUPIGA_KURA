@extends('layouts.admin')
@section('title', '♿ '.__t('accessibility_logs'))
@section('subtitle', __t('accessibility_logs_subtitle'))
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">{{ __t('accessibility_logs') }}</h3>
        <span class="badge bg-blue-50 text-blue-700">{{ $logs->count() }} {{ __t('entries') }}</span>
    </div>
    <div class="overflow-x-auto table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __t('user') }}</th>
                    <th>{{ __t('previous_mode') }}</th>
                    <th>{{ __t('new_mode') }}</th>
                    <th>{{ __t('notes') }}</th>
                    <th>{{ __t('changed_at') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-gray-400 text-xs">{{ $log->id }}</td>
                    <td class="font-medium text-gray-900">{{ $log->user->full_name ?? 'Unknown' }}</td>
                    <td>
                        <span class="badge bg-gray-100 text-gray-700">{{ $log->old_mode ?? '-'; }}</span>
                    </td>
                    <td>
                        <span class="badge bg-indigo-100 text-indigo-700">{{ $log->new_mode }}</span>
                    </td>
                    <td class="text-gray-500 text-xs max-w-xs truncate">{{ $log->notes ?? '-' }}</td>
                    <td class="text-gray-500 text-xs">{{ $log->changed_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-400">{{ __t('no_logs_found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
