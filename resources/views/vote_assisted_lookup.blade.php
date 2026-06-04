@extends('layouts.app')
@section('title', __t('assisted_voting'))
@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">{{ __t('assisted_voting') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</p>
        </div>
        <div class="p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">{{ __t('assisted_voting_info') }}</p>
            </div>
            <form method="POST" action="{{ route('vote.assisted.lookup', $election->id) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">{{ __t('voter_identifier') }}</label>
                    <p class="text-xs text-gray-500 mb-2">{{ __t('voter_identifier_help') }}</p>
                    <input type="text" name="identifier" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="{{ __t('login_identifier') }}">
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('vote.form', $election->id) }}" class="flex-1 text-center bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg hover:bg-gray-300">{{ __t('back') }}</a>
                    <button type="submit" class="flex-1 bg-blue-900 text-white font-bold py-3 rounded-lg hover:bg-blue-800">{{ __t('find_voter') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
