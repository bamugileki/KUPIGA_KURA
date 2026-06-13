@extends('layouts.admin')
@section('title', __t('manage_candidates'))
@section('subtitle', __t('manage_candidates_subtitle'))
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">{{ __t('all_candidates') }}</h3>
        <div class="flex flex-wrap gap-2">
            @foreach(\App\Models\Position::orderBy('sort_order')->get() as $i => $pos)
            <a href="{{ route('admin.candidates.register', $pos->slug) }}" class="btn text-white px-3 py-1.5 rounded-lg text-xs font-medium" style="background: {{ ['#2563eb', '#059669', '#7c3aed', '#d97706', '#1d4ed8', '#059669', '#dc2626', '#0891b2'][$i % 8] }}">
                + {{ session('lang') == 'sw' ? $pos->name_sw : $pos->name_en }}
            </a>
            @endforeach
            <a href="{{ route('admin.candidates.delete_all') }}" class="btn bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs font-medium border border-red-200 hover:bg-red-100" onclick="return confirm('{{ __t('are_you_sure_delete_all_candidates') }}')">{{ __t('delete_all_candidates') ?? 'Delete All Candidates' }}</a>
        </div>
    </div>
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.candidates', ['status' => 'all']) }}" class="filter-btn {{ $statusFilter == 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ __t('all') }}</a>
            <a href="{{ route('admin.candidates', ['status' => 'pending']) }}" class="filter-btn {{ $statusFilter == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ __t('pending') }}</a>
            <a href="{{ route('admin.candidates', ['status' => 'approved']) }}" class="filter-btn {{ $statusFilter == 'approved' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ __t('approved') }}</a>
            <a href="{{ route('admin.candidates', ['status' => 'rejected']) }}" class="filter-btn {{ $statusFilter == 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ __t('rejected') }}</a>
        </div>
    </div>
    <div class="overflow-x-auto table-wrap">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>{{ __t('photo') }}</th>
                    <th>{{ __t('full_name') }}</th>
                    <th>{{ __t('party_abbreviation') }}</th>
                    <th>{{ __t('position_label') }}</th>
                    <th>{{ __t('constituency') }}</th>
                    <th>{{ __t('running_mate') }}</th>
                    <th>{{ __t('status') }}</th>
                    <th>{{ __t('rejection_reason') }}</th>
                    <th>{{ __t('date_created') }}</th>
                    <th>{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $cand)
                <tr>
                    <td>
                        @if($cand->photo)
                        <img src="{{ asset($cand->photo) }}" alt="Photo" class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-100">
                        @else
                        <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">{{ substr($cand->full_name ?? $cand->user->full_name, 0, 2) }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="font-medium text-gray-900">{{ $cand->full_name ?? $cand->user->full_name }}</div>
                        <div class="text-xs text-gray-400">{{ $cand->user->email ?? '' }}</div>
                    </td>
                    <td>
                        <div class="font-semibold text-gray-900">{{ $cand->party_abbreviation }}</div>
                        <div class="text-xs text-gray-400">{{ $cand->party_name }}</div>
                        @if($cand->party_logo)
                        <img src="{{ asset($cand->party_logo) }}" alt="Logo" class="h-5 mt-1 opacity-80">
                        @endif
                    </td>
                    <td>
                        @php $pos = \App\Models\Position::where('slug', $cand->position)->first(); @endphp
                        <span class="badge bg-blue-50 text-blue-700">{{ session('lang') == 'sw' ? ($pos->name_sw ?? $cand->position) : ($pos->name_en ?? $cand->position) }}</span>
                    </td>
                    <td class="text-gray-600 text-xs">{{ $cand->constituency ?? '-' }}</td>
                    <td>
                        @if($cand->running_mate_name)
                        <div class="flex items-center space-x-1.5">
                            @if($cand->running_mate_photo)
                            <img src="{{ asset($cand->running_mate_photo) }}" alt="RM" class="h-6 w-6 rounded-full object-cover">
                            @endif
                            <span class="text-xs text-gray-700">{{ $cand->running_mate_name }}</span>
                        </div>
                        @else
                        <span class="text-gray-300">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge
                            @if($cand->status == 'approved') bg-green-100 text-green-700
                            @elseif($cand->status == 'pending') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700
                            @endif">{{ __t($cand->status) }}</span>
                    </td>
                    <td>
                        @if($cand->status == 'rejected' && $cand->rejection_reason)
                        <span class="text-xs text-red-600 block truncate" style="max-width:200px" title="{{ $cand->rejection_reason }}">{{ $cand->rejection_reason }}</span>
                        @else
                        <span class="text-gray-300">-</span>
                        @endif
                    </td>
                    <td class="text-gray-500 text-xs">{{ $cand->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="flex flex-wrap gap-1.5">
                            <a href="{{ route('admin.candidates.nomination_support', $cand->id) }}" class="btn bg-indigo-50 text-indigo-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-indigo-100 border border-indigo-200">{{ __t('nomination_support') }}</a>
                            @if($cand->status == 'pending')
                            <form method="POST" action="{{ route('admin.candidates.approve', $cand->id) }}" class="inline-flex items-center space-x-1">
                                @csrf
                                <select name="election_id" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 bg-white" style="min-width:100px" required>
                                    <option value="">{{ __t('select_election') }}</option>
                                    @foreach($elections as $election)
                                        @if(in_array($election->status, ['draft', 'nomination_open']))
                                        <option value="{{ $election->id }}" {{ $cand->election_id == $election->id ? 'selected' : '' }}>{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="submit" class="btn bg-green-50 text-green-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-green-100 border border-green-200">
                                    <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ __t('approve') }}
                                </button>
                            </form>
                            <a href="#" onclick="event.preventDefault(); var r = prompt('{{ __t('rejection_reason_prompt') }}'); if (r) { window.location='{{ route('admin.candidates.reject', $cand->id) }}?rejection_reason='+encodeURIComponent(r); }" class="btn bg-red-50 text-red-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-red-100 border border-red-200">
                                <svg class="w-3.5 h-3.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                {{ __t('reject') }}
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
