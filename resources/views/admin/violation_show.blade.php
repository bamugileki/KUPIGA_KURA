@extends('layouts.admin')
@section('title', __t('violation_details'))
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">{{ __t('violation_details') }} #{{ $violation->id }}</h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase">{{ __t('reported_by') }}</span>
                    <p class="font-semibold">{{ $violation->reporter->full_name ?? 'Unknown' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase">{{ __t('status') }}</span>
                    <p><span class="text-xs px-2 py-0.5 rounded
                        @if($violation->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($violation->status === 'investigated') bg-blue-100 text-blue-800
                        @elseif($violation->status === 'substantiated') bg-red-100 text-red-800
                        @else bg-green-100 text-green-800 @endif">{{ __t($violation->status) }}</span></p>
                </div>
                @if($violation->accused)
                <div>
                    <span class="text-xs text-gray-500 uppercase">{{ __t('accused') }}</span>
                    <p class="font-semibold">{{ $violation->accused->full_name }}</p>
                </div>
                @endif
                @if($violation->candidate)
                <div>
                    <span class="text-xs text-gray-500 uppercase">{{ __t('candidate') }}</span>
                    <p class="font-semibold">{{ $violation->candidate->full_name ?? $violation->candidate->user->full_name ?? 'N/A' }}</p>
                </div>
                @endif
            </div>
            <div>
                <span class="text-xs text-gray-500 uppercase">{{ __t('description') }}</span>
                <p class="mt-1 text-gray-800">{{ $violation->description }}</p>
            </div>
            @if($violation->evidence)
            <div>
                <span class="text-xs text-gray-500 uppercase">{{ __t('evidence') }}</span>
                <p class="mt-1 text-gray-800">{{ $violation->evidence }}</p>
            </div>
            @endif
            @if(in_array($violation->status, ['pending', 'investigated']))
            <div class="border-t border-gray-200 pt-4">
                <h3 class="font-semibold text-gray-800 mb-3">{{ __t('resolve_violation') }}</h3>
                <form method="POST" action="{{ route('admin.violations.resolve', $violation->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('resolution') }}</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="investigated">{{ __t('investigated') }}</option>
                            <option value="substantiated">{{ __t('substantiated') }}</option>
                            <option value="dismissed">{{ __t('dismissed') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('resolution_notes') }}</label>
                        <textarea name="resolution_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="{{ __t('resolution_notes_placeholder') }}"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-900 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-800">{{ __t('resolve') }}</button>
                </form>
            </div>
            @else
            <div class="border-t border-gray-200 pt-4">
                <span class="text-xs text-gray-500 uppercase">{{ __t('resolved_by') }}</span>
                <p class="font-semibold">{{ $violation->resolver->full_name ?? 'Unknown' }} ({{ $violation->resolved_at ? $violation->resolved_at->format('Y-m-d H:i') : '' }})</p>
                @if($violation->resolution_notes)
                <div class="mt-2">
                    <span class="text-xs text-gray-500 uppercase">{{ __t('resolution_notes') }}</span>
                    <p class="mt-1">{{ $violation->resolution_notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    <a href="{{ route('admin.violations') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">&larr; {{ __t('back') }} {{ __t('code_conduct_violations') }}</a>
</div>
@endsection
