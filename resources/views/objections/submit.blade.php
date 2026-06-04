@extends('layouts.app')
@section('title', __t('submit_objection'))
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">{{ __t('submit_objection') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ __t('objection_form_info') }}</p>
        </div>
        <div class="p-6">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">{{ __t('objection_notice') }}</p>
            </div>
            <form method="POST" action="{{ route('objections.submit.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('objection_type') }}</label>
                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="nomination">{{ __t('nomination_objection') }}</option>
                        <option value="petition">{{ __t('election_petition') }}</option>
                        <option value="election">{{ __t('election_objection') }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('reason') }}</label>
                    <textarea name="reason" required rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="{{ __t('objection_reason_placeholder') }}"></textarea>
                    <p class="text-xs text-gray-500 mt-1">{{ __t('objection_reason_min') }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('evidence') }}</label>
                    <textarea name="evidence" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="{{ __t('evidence_placeholder') }}"></textarea>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('dashboard') }}" class="flex-1 text-center bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg hover:bg-gray-300">{{ __t('cancel') }}</a>
                    <button type="submit" class="flex-1 bg-blue-900 text-white font-bold py-3 rounded-lg hover:bg-blue-800">{{ __t('submit_objection_btn') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
