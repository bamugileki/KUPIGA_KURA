@extends('layouts.admin')
@section('title', __t('announcements'))
@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-900">{{ __t('announcements') }}</h2>
    <a href="{{ route('admin.announcements.create') }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">{{ __t('create_announcement') }}</a>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left py-3 px-4">{{ __t('title') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('priority') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('status') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('created_by') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('published_at') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $a)
                <tr class="border-t border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ session('lang') == 'sw' ? $a->title_sw : $a->title_en }}</td>
                    <td class="py-3 px-4">
                        <span class="text-xs px-2 py-0.5 rounded
                            @if($a->priority === 'urgent') bg-red-100 text-red-800
                            @elseif($a->priority === 'high') bg-orange-100 text-orange-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ __t($a->priority) }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        @if($a->is_published)
                        <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-800">{{ __t('published') }}</span>
                        @else
                        <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600">{{ __t('draft') }}</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ $a->creator->full_name ?? 'Unknown' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $a->published_at ? $a->published_at->format('Y-m-d H:i') : '-' }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">
                        <a href="{{ route('admin.announcements.edit', $a->id) }}" class="bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700">{{ __t('edit') }}</a>
                        @if($a->is_published)
                        <a href="{{ route('admin.announcements.unpublish', $a->id) }}" class="bg-yellow-600 text-white px-2 py-1 rounded text-xs hover:bg-yellow-700">{{ __t('unpublish') }}</a>
                        @else
                        <a href="{{ route('admin.announcements.publish', $a->id) }}" class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700">{{ __t('publish') }}</a>
                        @endif
                        <a href="{{ route('admin.announcements.delete', $a->id) }}" class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('delete') }}</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-8 text-gray-500">{{ __t('no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
