@extends('layouts.admin')
@section('title', __t('system_settings'))
@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.settings') }}" class="bg-white rounded-lg shadow">
        @csrf
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">{{ __t('password_policy') }}</h3>
        </div>
        <div class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('max_failed_attempts') }}</label>
                <input type="number" name="max_failed_attempts" value="{{ $settings['max_failed_attempts'] ?? 3 }}" min="1" max="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('lock_minutes_base') }}</label>
                <input type="number" name="lock_minutes_base" value="{{ $settings['lock_minutes_base'] ?? 30 }}" min="1" max="1440" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('lock_multiplier') }}</label>
                <input type="number" name="lock_multiplier" value="{{ $settings['lock_multiplier'] ?? 2 }}" min="1" max="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
        </div>
        <div class="px-4 py-3 border-b border-t border-gray-200">
            <h3 class="font-semibold text-gray-800">{{ __t('session_config') }}</h3>
        </div>
        <div class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('session_timeout') }}</label>
                <input type="number" name="session_timeout" value="{{ $settings['session_timeout'] ?? 60 }}" min="5" max="1440" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
        </div>
        <div class="px-4 py-3 border-b border-t border-gray-200">
            <h3 class="font-semibold text-gray-800">{{ __t('voting_rules') }}</h3>
        </div>
        <div class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('default_language') }}</label>
                <select name="default_language" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="en" {{ ($settings['default_language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                    <option value="sw" {{ ($settings['default_language'] ?? 'en') == 'sw' ? 'selected' : '' }}>Kiswahili</option>
                </select>
            </div>
        </div>
        <div class="px-4 py-3 text-right border-t border-gray-200">
            <button type="submit" class="bg-blue-900 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-800">{{ __t('save_settings') }}</button>
        </div>
    </form>
</div>
@endsection