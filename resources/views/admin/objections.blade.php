@extends('layouts.admin')
@section('title', __t('objections'))
@section('subtitle', __t('manage_objections_subtitle'))
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">{{ __t('all_objections') }}</h3>
    </div>
    <div class="overflow-x-auto table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __t('objection_type') }}</th>
                    <th>{{ __t('objector') }}</th>
                    <th>{{ __t('reason') }}</th>
                    <th>{{ __t('status') }}</th>
                    <th>{{ __t('created_at') }}</th>
                    <th>{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($objections as $obj)
                <tr>
                    <td class="text-gray-400 text-xs">{{ $obj->id }}</td>
                    <td>
                        <span class="badge bg-indigo-50 text-indigo-700">{{ $obj->type }}</span>
                    </td>
                    <td class="font-medium text-gray-900">{{ $obj->user->full_name ?? 'Unknown' }}</td>
                    <td class="text-gray-600 max-w-xs truncate">{{ $obj->reason }}</td>
                    <td>
                        <span class="badge
                            @if($obj->status == 'resolved') bg-green-100 text-green-700
                            @elseif($obj->status == 'dismissed') bg-gray-100 text-gray-700
                            @else bg-yellow-100 text-yellow-700
                            @endif">{{ __t($obj->status) }}</span>
                    </td>
                    <td class="text-gray-500 text-xs">{{ $obj->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.objections.show', $obj->id) }}" class="btn bg-blue-50 text-blue-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-100 border border-blue-200">{{ __t('view') }}</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
