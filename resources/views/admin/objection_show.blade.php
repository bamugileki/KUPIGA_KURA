@extends('layouts.admin')
@section('title', __t('objection_details'))
@section('subtitle', __t('objection_details_subtitle'))
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">{{ __t('objection_details') }} #{{ $objection->id }}</h3>
            <span class="badge
                @if($objection->status === 'pending') bg-yellow-100 text-yellow-700
                @elseif($objection->status === 'upheld') bg-red-100 text-red-700
                @else bg-green-100 text-green-700
                @endif">{{ __t($objection->status) }}</span>
        </div>
        <div class="p-5 space-y-5">
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('objection_type') }}</p>
                    <p class="font-semibold text-gray-900 mt-1">
                        @if($objection->type === 'nomination') {{ __t('nomination_objection') }}
                        @elseif($objection->type === 'election') {{ __t('election_objection') }}
                        @else {{ __t('election_petition') }} @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('status') }}</p>
                    <p class="mt-1">
                        <span class="badge
                            @if($objection->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($objection->status === 'upheld') bg-red-100 text-red-700
                            @else bg-green-100 text-green-700
                            @endif">{{ __t($objection->status) }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('objector') }}</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ $objection->objector->full_name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-400">{{ $objection->objector->email ?? '' }}</p>
                </div>
                @if($objection->candidate)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('candidate') }}</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ $objection->candidate->full_name ?? $objection->candidate->user->full_name ?? 'N/A' }}</p>
                </div>
                @endif
                @if($objection->election)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('election') }}</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ session('lang') == 'sw' ? $objection->election->title_sw : $objection->election->title_en }}</p>
                </div>
                @endif
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-2">{{ __t('reason') }}</p>
                <p class="text-gray-800">{{ $objection->reason }}</p>
            </div>

            @if($objection->evidence)
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-2">{{ __t('evidence') }}</p>
                <p class="text-gray-800">{{ $objection->evidence }}</p>
            </div>
            @endif

            @if($objection->status === 'pending')
            <div class="border-t border-gray-100 pt-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-4">{{ __t('resolve_objection') }}</h4>
                <form method="POST" action="{{ route('admin.objections.resolve', $objection->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('resolution') }}</label>
                        <select name="status" required>
                            <option value="upheld">{{ __t('upheld') }}</option>
                            <option value="dismissed">{{ __t('dismissed') }}</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('admin_notes') }}</label>
                        <textarea name="admin_notes" rows="3" placeholder="{{ __t('admin_notes_placeholder') }}"></textarea>
                    </div>
                    <button type="submit" class="btn bg-blue-600 text-white font-medium py-2 px-6 rounded-lg text-sm hover:bg-blue-700">{{ __t('resolve') }}</button>
                </form>
            </div>
            @else
            <div class="border-t border-gray-100 pt-5">
                <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('resolved_by') }}</p>
                <p class="font-semibold text-gray-900 mt-1">{{ $objection->resolver->full_name ?? 'Unknown' }} <span class="text-gray-400 font-normal">({{ $objection->resolved_at ? $objection->resolved_at->format('Y-m-d H:i') : '' }})</span></p>
                @if($objection->admin_notes)
                <div class="mt-3 bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-2">{{ __t('admin_notes') }}</p>
                    <p class="text-gray-800">{{ $objection->admin_notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    <a href="{{ route('admin.objections') }}" class="inline-flex items-center mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        {{ __t('back') }} {{ __t('objections') }}
    </a>
</div>
@endsection
