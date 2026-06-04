@extends('layouts.admin')
@section('title', __t('user_management'))
@section('content')
<h2 class="text-2xl font-bold text-blue-900 mb-4">{{ __t('user_management') }}</h2>
<div class="flex justify-between items-center mb-4">
    <div class="flex space-x-2">
        <a href="{{ route('admin.users', ['status' => 'all']) }}" class="px-3 py-1 rounded text-sm {{ $statusFilter == 'all' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __t('all') }}</a>
        <a href="{{ route('admin.users', ['status' => 'pending']) }}" class="px-3 py-1 rounded text-sm {{ $statusFilter == 'pending' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __t('pending') }}</a>
        <a href="{{ route('admin.users', ['status' => 'active']) }}" class="px-3 py-1 rounded text-sm {{ $statusFilter == 'active' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __t('active') }}</a>
        <a href="{{ route('admin.users', ['status' => 'rejected']) }}" class="px-3 py-1 rounded text-sm {{ $statusFilter == 'rejected' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __t('rejected') }}</a>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-anim bg-blue-900 text-white px-4 py-2 rounded text-sm hover:bg-blue-800">{{ __t('create_user') }}</a>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left py-3 px-4">{{ __t('full_name') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('email') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('identifier') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('role_label') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('status') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('date_created') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $user->full_name }}</td>
                    <td class="py-3 px-4">{{ $user->email }}</td>
                    <td class="py-3 px-4">{{ $user->nida_number ?? $user->driving_licence ?? $user->nhif_number ?? '-' }}</td>
                    <td class="py-3 px-4">{{ __t($user->role) }}</td>
                    <td class="py-3 px-4">
                        <span class="badge-anim text-xs px-2 py-1 rounded
                            @if($user->status == 'active') bg-green-100 text-green-800
                            @elseif($user->status == 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">{{ __t($user->status) }}</span>
                    </td>
                    <td class="py-3 px-4">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-anim bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700 mr-1">{{ __t('edit') }}</a>
                        @if($user->status == 'pending')
                            <a href="{{ route('admin.users.approve', $user->id) }}" class="btn-anim bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700 mr-1">{{ __t('approve') }}</a>
                            <a href="{{ route('admin.users.reject', $user->id) }}" class="btn-anim bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700 mr-1" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('reject') }}</a>
                        @endif
                        <a href="{{ route('admin.users.delete', $user->id) }}" class="btn-anim bg-gray-600 text-white px-2 py-1 rounded text-xs hover:bg-gray-700" onclick="return confirm('{{ __t('are_you_sure_delete') }}')">{{ __t('delete') }}</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
