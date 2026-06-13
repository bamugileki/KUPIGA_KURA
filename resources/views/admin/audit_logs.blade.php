@extends('layouts.admin')
@section('title', __t('audit_logs'))
@section('subtitle', __t('audit_trail_subtitle'))
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">{{ __t('audit_trail') }}</h3>
        <a href="{{ route('admin.audit_logs.delete_all') }}" class="btn bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs font-medium border border-red-200 hover:bg-red-100" onclick="return confirm('Are you sure you want to delete ALL audit logs? This cannot be undone.')">
            <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            {{ __t('delete_all_logs') ?? 'Delete All Logs' }}
        </a>
    </div>
    <div class="overflow-x-auto table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __t('user') }}</th>
                    <th>{{ __t('action') }}</th>
                    <th>{{ __t('details') }}</th>
                    <th>{{ __t('ip_address') }}</th>
                    <th>{{ __t('timestamp') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-gray-400 text-xs">{{ $log->id }}</td>
                    <td class="font-medium text-gray-900">{{ $log->user ? $log->user->full_name : 'System' }}</td>
                    <td>
                        <span class="badge
                            @if(str_contains($log->action, 'SUCCESS') || str_contains($log->action, 'APPROVED') || str_contains($log->action, 'CREATED') || str_contains($log->action, 'OPENED')) bg-green-100 text-green-700
                            @elseif(str_contains($log->action, 'FAILED') || str_contains($log->action, 'LOCKED') || str_contains($log->action, 'BLOCKED') || str_contains($log->action, 'DELETED') || str_contains($log->action, 'REJECTED')) bg-red-100 text-red-700
                            @elseif(str_contains($log->action, 'UPDATE') || str_contains($log->action, 'EDIT')) bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700
                            @endif">{{ $log->action }}</span>
                    </td>
                    <td class="text-gray-600 text-xs max-w-xs truncate">{{ $log->details ?? '-' }}</td>
                    <td class="text-gray-400 text-xs font-mono">{{ $log->ip_address ?? '-' }}</td>
                    <td class="text-gray-500 text-xs">{{ $log->timestamp->format('Y-m-d H:i:s') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-gray-400">{{ __t('no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
