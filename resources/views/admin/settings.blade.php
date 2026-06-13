@extends('layouts.admin')
@section('title', __t('system_settings'))
@section('subtitle', __t('system_settings_subtitle'))
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.settings') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                {{ __t('password_policy') }}
            </h3>
        </div>
        <div class="p-5 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('max_failed_attempts') }}</label>
                <input type="number" name="max_failed_attempts" value="{{ $settings['max_failed_attempts'] ?? 3 }}" min="1" max="10">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('lock_minutes_base') }}</label>
                <input type="number" name="lock_minutes_base" value="{{ $settings['lock_minutes_base'] ?? 30 }}" min="1" max="1440">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('lock_multiplier') }}</label>
                <input type="number" name="lock_multiplier" value="{{ $settings['lock_multiplier'] ?? 2 }}" min="1" max="10">
            </div>
        </div>

        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
            <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __t('session_config') }}
            </h3>
        </div>
        <div class="p-5 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('session_timeout') }} ({{ __t('minutes') }})</label>
                <input type="number" name="session_timeout" value="{{ $settings['session_timeout'] ?? 60 }}" min="5" max="1440">
            </div>
        </div>

        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
            <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M3 14h18M3 17h18"/></svg>
                {{ __t('voting_rules') }}
            </h3>
        </div>
        <div class="p-5 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('default_language') }}</label>
                <select name="default_language">
                    <option value="en" {{ ($settings['default_language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                    <option value="sw" {{ ($settings['default_language'] ?? 'en') == 'sw' ? 'selected' : '' }}>Kiswahili</option>
                </select>
            </div>
        </div>

        <div class="px-5 py-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="btn bg-blue-600 text-white font-medium py-2 px-6 rounded-lg text-sm hover:bg-blue-700">
                <svg class="w-4 h-4 inline-block mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                {{ __t('save_settings') }}
            </button>
        </div>
    </form>
</div>
@endsection
