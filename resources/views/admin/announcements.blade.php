@extends('layouts.admin')
@section('title', __t('announcements'))
@section('subtitle', __t('manage_announcements_subtitle'))
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">{{ __t('all_announcements') }}</h3>
        <a href="{{ route('admin.announcements.create') }}" class="btn bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4 inline-block mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __t('create_announcement') }}
        </a>
    </div>
    <div class="overflow-x-auto table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __t('title') }}</th>
                    <th>{{ __t('type') }}</th>
                    <th>{{ __t('status') }}</th>
                    <th>{{ __t('created_at') }}</th>
                    <th>{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($announcements as $ann)
                <tr>
                    <td class="text-gray-400 text-xs">{{ $ann->id }}</td>
                    <td class="font-medium text-gray-900">{{ session('lang') == 'sw' ? $ann->title_sw : $ann->title_en }}</td>
                    <td>
                        <span class="badge bg-blue-50 text-blue-700">{{ $ann->type }}</span>
                    </td>
                    <td>
                        <span class="badge
                            @if($ann->status == 'published') bg-green-100 text-green-700
                            @else bg-gray-100 text-gray-700
                            @endif">{{ __t($ann->status) }}</span>
                    </td>
                    <td class="text-gray-500 text-xs">{{ $ann->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <div class="flex items-center space-x-1.5">
                            <a href="{{ route('admin.announcements.edit', $ann->id) }}" class="btn bg-blue-50 text-blue-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-100 border border-blue-200">{{ __t('edit') }}</a>
                            <a href="{{ route('admin.announcements.delete', $ann->id) }}" class="btn bg-red-50 text-red-600 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-red-100 border border-red-200" onclick="return confirm('{{ __t('are_you_sure_delete') }}')">{{ __t('delete') }}</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
