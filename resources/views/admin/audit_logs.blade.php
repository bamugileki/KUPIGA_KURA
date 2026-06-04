@extends('layouts.admin')
@section('title', __t('audit_logs'))
@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-900">{{ __t('audit_logs') }}</h2>
    <a href="{{ route('admin.audit_logs.delete_all') }}" class="btn-anim bg-red-700 text-white px-4 py-2 rounded text-sm hover:bg-red-600" onclick="return confirm('Are you sure you want to delete ALL audit logs? This cannot be undone.')">{{ __t('delete_all_logs') ?? 'Delete All Logs' }}</a>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left py-3 px-4">ID</th>
                    <th class="text-left py-3 px-4">{{ __t('user') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('action') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('details') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('ip_address') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('device_info') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('timestamp') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $log->id }}</td>
                    <td class="py-3 px-4">{{ $log->user_id ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $log->action }}</td>
                    <td class="py-3 px-4 max-w-xs break-all">{{ $log->details ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $log->ip_address ?? '-' }}</td>
                    <td class="py-3 px-4 max-w-xs break-all text-xs">{{ $log->device_info ?? '-' }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">{{ $log->timestamp->format('Y-m-d H:i:s') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-gray-500">{{ __t('no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($logs, 'links'))
    <div class="p-4">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
