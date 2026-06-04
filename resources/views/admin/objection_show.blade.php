@extends('layouts.admin')
@section('title', __t('objection_details'))
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">{{ __t('objection_details') }} #{{ $objection->id }}</h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase">{{ __t('objection_type') }}</span>
                    <p class="font-semibold">
                        @if($objection->type === 'nomination') {{ __t('nomination_objection') }}
                        @elseif($objection->type === 'election') {{ __t('election_objection') }}
                        @else {{ __t('election_petition') }} @endif
                    </p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">{{ __t('status') }}</span>
                    <p><span class="text-xs px-2 py-0.5 rounded
                        @if($objection->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($objection->status === 'upheld') bg-red-100 text-red-800
                        @else bg-green-100 text-green-800 @endif">{{ __t($objection->status) }}</span></p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">{{ __t('objector') }}</span>
                    <p class="font-semibold">{{ $objection->objector->full_name ?? 'Unknown' }} ({{ $objection->objector->email ?? '' }})</p>
                </div>
                @if($objection->candidate)
                <div>
                    <span class="text-xs text-gray-500 uppercase">{{ __t('candidate') }}</span>
                    <p class="font-semibold">{{ $objection->candidate->full_name ?? $objection->candidate->user->full_name ?? 'N/A' }}</p>
                </div>
                @endif
                @if($objection->election)
                <div>
                    <span class="text-xs text-gray-500 uppercase">{{ __t('election') }}</span>
                    <p class="font-semibold">{{ session('lang') == 'sw' ? $objection->election->title_sw : $objection->election->title_en }}</p>
                </div>
                @endif
            </div>
            <div>
                <span class="text-xs text-gray-500 uppercase">{{ __t('reason') }}</span>
                <p class="mt-1 text-gray-800">{{ $objection->reason }}</p>
            </div>
            @if($objection->evidence)
            <div>
                <span class="text-xs text-gray-500 uppercase">{{ __t('evidence') }}</span>
                <p class="mt-1 text-gray-800">{{ $objection->evidence }}</p>
            </div>
            @endif
            @if($objection->status === 'pending')
            <div class="border-t border-gray-200 pt-4">
                <h3 class="font-semibold text-gray-800 mb-3">{{ __t('resolve_objection') }}</h3>
                <form method="POST" action="{{ route('admin.objections.resolve', $objection->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('resolution') }}</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="upheld">{{ __t('upheld') }}</option>
                            <option value="dismissed">{{ __t('dismissed') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('admin_notes') }}</label>
                        <textarea name="admin_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="{{ __t('admin_notes_placeholder') }}"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-900 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-800">{{ __t('resolve') }}</button>
                </form>
            </div>
            @else
            <div class="border-t border-gray-200 pt-4">
                <span class="text-xs text-gray-500 uppercase">{{ __t('resolved_by') }}</span>
                <p class="font-semibold">{{ $objection->resolver->full_name ?? 'Unknown' }} ({{ $objection->resolved_at ? $objection->resolved_at->format('Y-m-d H:i') : '' }})</p>
                @if($objection->admin_notes)
                <div class="mt-2">
                    <span class="text-xs text-gray-500 uppercase">{{ __t('admin_notes') }}</span>
                    <p class="mt-1">{{ $objection->admin_notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    <a href="{{ route('admin.objections') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">&larr; {{ __t('back') }} {{ __t('objections') }}</a>
</div>
@endsection
