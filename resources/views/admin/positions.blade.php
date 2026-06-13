@extends('layouts.admin')
@section('title', 'Manage Positions')
@section('subtitle', 'Configure election positions and requirements')
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">All Positions</h3>
        <a href="{{ route('admin.positions.create') }}" class="btn bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4 inline-block mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Position
        </a>
    </div>
    <div class="overflow-x-auto table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>Slug</th>
                    <th>Name (EN)</th>
                    <th>Name (SW)</th>
                    <th>Min Age</th>
                    <th>Constituency</th>
                    <th>Running Mate</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $pos)
                <tr>
                    <td><code class="text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-700">{{ $pos->slug }}</code></td>
                    <td class="font-medium text-gray-900">{{ $pos->name_en }}</td>
                    <td class="text-gray-700">{{ $pos->name_sw }}</td>
                    <td class="text-gray-600">{{ $pos->min_age }}</td>
                    <td>
                        <span class="badge {{ $pos->requires_constituency ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $pos->requires_constituency ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $pos->requires_running_mate ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $pos->requires_running_mate ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="text-gray-600">{{ $pos->sort_order }}</td>
                    <td>
                        <div class="flex items-center space-x-1.5">
                            <a href="{{ route('admin.positions.edit', $pos->id) }}" class="btn bg-blue-50 text-blue-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-100 border border-blue-200">
                                <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <a href="{{ route('admin.positions.delete', $pos->id) }}" class="btn bg-red-50 text-red-600 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-red-100 border border-red-200" onclick="return confirm('Delete this position? This cannot be undone if elections/candidates use it.')">
                                <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-10 text-gray-400">No positions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
