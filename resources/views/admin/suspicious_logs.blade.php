@extends('layouts.admin')
@section('title', __t('suspicious_activity'))
@section('subtitle', __t('suspicious_activity_subtitle'))
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">{{ __t('suspicious_activity_log') }}</h3>
        <span class="badge bg-red-100 text-red-700">{{ $logs->count() }} {{ __t('alerts') }}</span>
    </div>
    <div class="overflow-x-auto table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __t('user') }}</th>
                    <th>{{ __t('activity') }}</th>
                    <th>{{ __t('ip_address') }}</th>
                    <th>{{ __t('user_agent') }}</th>
                    <th>{{ __t('timestamp') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-gray-400 text-xs">{{ $log->id }}</td>
                    <td class="font-medium text-gray-900">{{ $log->user ? $log->user->full_name : 'Guest' }}</td>
                    <td class="text-gray-700">
                        <span class="badge bg-red-50 text-red-700">{{ $log->activity }}</span>
                    </td>
                    <td class="text-gray-400 text-xs font-mono">{{ $log->ip_address ?? '-' }}</td>
                    <td class="text-gray-400 text-xs truncate" style="max-width:200px" title="{{ $log->user_agent ?? '' }}">{{ $log->user_agent ? Str::limit($log->user_agent, 50) : '-' }}</td>
                    <td class="text-gray-500 text-xs">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-400">{{ __t('no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
