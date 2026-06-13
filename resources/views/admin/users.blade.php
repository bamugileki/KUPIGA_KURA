@extends('layouts.admin')
@section('title', __t('user_management'))
@section('subtitle', __t('manage_user_accounts'))
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.users', ['status' => 'all']) }}" class="filter-btn {{ $statusFilter == 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ __t('all') }}</a>
            <a href="{{ route('admin.users', ['status' => 'pending']) }}" class="filter-btn {{ $statusFilter == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ __t('pending') }}</a>
            <a href="{{ route('admin.users', ['status' => 'active']) }}" class="filter-btn {{ $statusFilter == 'active' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ __t('active') }}</a>
            <a href="{{ route('admin.users', ['status' => 'rejected']) }}" class="filter-btn {{ $statusFilter == 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ __t('rejected') }}</a>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4 inline-block mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            {{ __t('create_user') }}
        </a>
    </div>
    <div class="overflow-x-auto table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>{{ __t('full_name') }}</th>
                    <th>{{ __t('email') }}</th>
                    <th>{{ __t('identifier') }}</th>
                    <th>{{ __t('role_label') }}</th>
                    <th>{{ __t('status') }}</th>
                    <th>{{ __t('date_created') }}</th>
                    <th>{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                {{ substr($user->full_name, 0, 1) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $user->full_name }}</span>
                        </div>
                    </td>
                    <td class="text-gray-600">{{ $user->email }}</td>
                    <td class="text-gray-500 text-xs font-mono">{{ $user->nida_number ?? $user->driving_licence ?? $user->nhif_number ?? '-' }}</td>
                    <td>
                        <span class="badge bg-indigo-50 text-indigo-700">{{ __t($user->role) }}</span>
                    </td>
                    <td>
                        <span class="badge
                            @if($user->status == 'active') bg-green-100 text-green-700
                            @elseif($user->status == 'pending') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700
                            @endif">{{ __t($user->status) }}</span>
                    </td>
                    <td class="text-gray-500 text-xs">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="flex items-center space-x-1.5">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn bg-blue-50 text-blue-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-100 border border-blue-200">
                                <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                {{ __t('edit') }}
                            </a>
                            @if($user->status == 'pending')
                                <a href="{{ route('admin.users.approve', $user->id) }}" class="btn bg-green-50 text-green-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-green-100 border border-green-200">
                                    <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ __t('approve') }}
                                </a>
                                <a href="{{ route('admin.users.reject', $user->id) }}" class="btn bg-red-50 text-red-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-red-100 border border-red-200" onclick="return confirm('{{ __t('are_you_sure') }}')">
                                    <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    {{ __t('reject') }}
                                </a>
                            @endif
                            <a href="{{ route('admin.users.delete', $user->id) }}" class="btn bg-gray-50 text-gray-600 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-100 border border-gray-200" onclick="return confirm('{{ __t('are_you_sure_delete') }}')">
                                <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                {{ __t('delete') }}
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
