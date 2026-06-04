@extends('layouts.admin')
@section('title', __t('manage_candidates'))
@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-900">{{ __t('manage_candidates') }}</h2>
    <div class="flex space-x-2">
        <a href="{{ route('admin.candidates.register', 'presidential') }}" class="btn-anim bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">{{ __t('register_presidential_candidate') }}</a>
        <a href="{{ route('admin.candidates.register', 'parliamentary') }}" class="btn-anim bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600">{{ __t('register_parliamentary_candidate') }}</a>
        <a href="{{ route('admin.candidates.register', 'councillor') }}" class="btn-anim bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-600">{{ __t('register_councillor_candidate') }}</a>
        <a href="{{ route('admin.candidates.delete_all') }}" class="btn-anim bg-red-700 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Are you sure you want to delete ALL candidates and their user accounts? This cannot be undone.')">{{ __t('delete_all_candidates') ?? 'Delete All Candidates' }}</a>
    </div>
</div>
<div class="flex space-x-2 mb-4">
    <a href="{{ route('admin.candidates', ['status' => 'all']) }}" class="px-3 py-1 rounded text-sm {{ $statusFilter == 'all' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __t('all') }}</a>
    <a href="{{ route('admin.candidates', ['status' => 'pending']) }}" class="px-3 py-1 rounded text-sm {{ $statusFilter == 'pending' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __t('pending') }}</a>
    <a href="{{ route('admin.candidates', ['status' => 'approved']) }}" class="px-3 py-1 rounded text-sm {{ $statusFilter == 'approved' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __t('approved') }}</a>
    <a href="{{ route('admin.candidates', ['status' => 'rejected']) }}" class="px-3 py-1 rounded text-sm {{ $statusFilter == 'rejected' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __t('rejected') }}</a>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left py-3 px-4">{{ __t('photo') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('full_name') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('party_abbreviation') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('position_label') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('constituency') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('running_mate') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('status') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('date_created') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $cand)
                <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">
                        @if($cand->photo)
                        <img src="{{ asset($cand->photo) }}" alt="Photo" class="h-10 w-10 rounded-full object-cover">
                        @else
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs">{{ substr($cand->full_name ?? $cand->user->full_name, 0, 2) }}</div>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <div class="font-medium">{{ $cand->full_name ?? $cand->user->full_name }}</div>
                        <div class="text-xs text-gray-500">{{ $cand->user->email ?? '' }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="font-medium text-blue-900">{{ $cand->party_abbreviation }}</div>
                        <div class="text-xs text-gray-500">{{ $cand->party_name }}</div>
                        @if($cand->party_logo)
                        <img src="{{ asset($cand->party_logo) }}" alt="Logo" class="h-5 mt-1">
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ __t($cand->position) }}</td>
                    <td class="py-3 px-4">{{ $cand->constituency ?? '-' }}</td>
                    <td class="py-3 px-4">
                        @if($cand->running_mate_name)
                        <span>{{ $cand->running_mate_name }}</span>
                        @if($cand->running_mate_photo)
                        <img src="{{ asset($cand->running_mate_photo) }}" alt="RM" class="h-6 w-6 rounded-full object-cover inline-block ml-1">
                        @endif
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <span class="badge-anim text-xs px-2 py-1 rounded
                            @if($cand->status == 'approved') bg-green-100 text-green-800
                            @elseif($cand->status == 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">{{ __t($cand->status) }}</span>
                    </td>
                    <td class="py-3 px-4">{{ $cand->created_at->format('Y-m-d') }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.candidates.nomination_support', $cand->id) }}" class="btn-anim bg-indigo-600 text-white px-2 py-1 rounded text-xs hover:bg-indigo-700">{{ __t('nomination_support') }}</a>
                        @if($cand->status == 'pending')
                        <form method="POST" action="{{ route('admin.candidates.approve', $cand->id) }}" class="inline">
                            @csrf
                            <select name="election_id" class="text-xs border rounded px-1 py-1" required>
                                <option value="">{{ __t('select_election') }}</option>
                                @foreach($elections as $election)
                                    @if(in_array($election->status, ['draft', 'nomination_open']))
                                    <option value="{{ $election->id }}" {{ $cand->election_id == $election->id ? 'selected' : '' }}>{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="submit" class="btn-anim bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700">{{ __t('approve') }}</button>
                        </form>
                        <a href="{{ route('admin.candidates.reject', $cand->id) }}" class="btn-anim bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('reject') }}</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
