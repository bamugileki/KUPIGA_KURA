@extends('layouts.admin')
@section('title', __t('violation_details'))
@section('subtitle', __t('violation_details_subtitle'))
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">{{ __t('violation_details') }} #{{ $violation->id }}</h3>
            <span class="badge
                @if($violation->status === 'pending') bg-yellow-100 text-yellow-700
                @elseif($violation->status === 'investigated') bg-blue-100 text-blue-700
                @elseif($violation->status === 'substantiated') bg-red-100 text-red-700
                @else bg-green-100 text-green-700
                @endif">{{ __t($violation->status) }}</span>
        </div>
        <div class="p-5 space-y-5">
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('reported_by') }}</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ $violation->reporter->full_name ?? 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('status') }}</p>
                    <p class="mt-1">
                        <span class="badge
                            @if($violation->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($violation->status === 'investigated') bg-blue-100 text-blue-700
                            @elseif($violation->status === 'substantiated') bg-red-100 text-red-700
                            @else bg-green-100 text-green-700
                            @endif">{{ __t($violation->status) }}</span>
                    </p>
                </div>
                @if($violation->accused)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('accused') }}</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ $violation->accused->full_name }}</p>
                </div>
                @endif
                @if($violation->candidate)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('candidate') }}</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ $violation->candidate->full_name ?? $violation->candidate->user->full_name ?? 'N/A' }}</p>
                </div>
                @endif
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-2">{{ __t('description') }}</p>
                <p class="text-gray-800">{{ $violation->description }}</p>
            </div>

            @if($violation->evidence)
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-2">{{ __t('evidence') }}</p>
                <p class="text-gray-800">{{ $violation->evidence }}</p>
            </div>
            @endif

            @if(in_array($violation->status, ['pending', 'investigated']))
            <div class="border-t border-gray-100 pt-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-4">{{ __t('resolve_violation') }}</h4>
                <form method="POST" action="{{ route('admin.violations.resolve', $violation->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('resolution') }}</label>
                        <select name="status" required>
                            <option value="investigated">{{ __t('investigated') }}</option>
                            <option value="substantiated">{{ __t('substantiated') }}</option>
                            <option value="dismissed">{{ __t('dismissed') }}</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('resolution_notes') }}</label>
                        <textarea name="resolution_notes" rows="3" placeholder="{{ __t('resolution_notes_placeholder') }}"></textarea>
                    </div>
                    <button type="submit" class="btn bg-blue-600 text-white font-medium py-2 px-6 rounded-lg text-sm hover:bg-blue-700">{{ __t('resolve') }}</button>
                </form>
            </div>
            @else
            <div class="border-t border-gray-100 pt-5">
                <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">{{ __t('resolved_by') }}</p>
                <p class="font-semibold text-gray-900 mt-1">{{ $violation->resolver->full_name ?? 'Unknown' }} <span class="text-gray-400 font-normal">({{ $violation->resolved_at ? $violation->resolved_at->format('Y-m-d H:i') : '' }})</span></p>
                @if($violation->resolution_notes)
                <div class="mt-3 bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-2">{{ __t('resolution_notes') }}</p>
                    <p class="text-gray-800">{{ $violation->resolution_notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    <a href="{{ route('admin.violations') }}" class="inline-flex items-center mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        {{ __t('back') }} {{ __t('code_conduct_violations') }}
    </a>
</div>
@endsection
