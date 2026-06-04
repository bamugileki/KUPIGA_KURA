@extends('layouts.admin')
@section('title', isset($election) ? __t('edit_election') : __t('create_election'))
@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-blue-900 mb-6">{{ isset($election) ? __t('edit_election') : __t('create_election') }}</h2>
        <form method="POST" action="{{ isset($election) ? route('admin.elections.edit', $election->id) : route('admin.elections.create') }}">
            @csrf
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">{{ __t('election_title') }} (English)</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $election->title_en ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">{{ __t('election_title') }} (Kiswahili)</label>
                    <input type="text" name="title_sw" value="{{ old('title_sw', $election->title_sw ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
            </div>
            @if(!isset($election))
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">{{ __t('election_type') }}</label>
                <select name="election_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                    <option value="presidential">{{ __t('presidential') }}</option>
                    <option value="parliamentary">{{ __t('parliamentary') }}</option>
                    <option value="councillor">{{ __t('councillor') }}</option>
                </select>
            </div>
            @endif
            <h3 class="font-bold text-gray-700 mb-2 mt-4 text-sm uppercase">{{ __t('nomination_period') }}</h3>
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('nomination_start') }}</label>
                    <input type="datetime-local" name="nomination_start" value="{{ old('nomination_start', isset($election) && $election->nomination_start ? $election->nomination_start->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('nomination_end') }}</label>
                    <input type="datetime-local" name="nomination_end" value="{{ old('nomination_end', isset($election) && $election->nomination_end ? $election->nomination_end->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <h3 class="font-bold text-gray-700 mb-2 mt-4 text-sm uppercase">{{ __t('campaign_period') }}</h3>
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('campaign_start') }}</label>
                    <input type="datetime-local" name="campaign_start" value="{{ old('campaign_start', isset($election) && $election->campaign_start ? $election->campaign_start->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('campaign_end') }}</label>
                    <input type="datetime-local" name="campaign_end" value="{{ old('campaign_end', isset($election) && $election->campaign_end ? $election->campaign_end->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <h3 class="font-bold text-gray-700 mb-2 mt-4 text-sm uppercase">{{ __t('voting_period') }}</h3>
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('start_time') }}</label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time', isset($election) ? $election->start_time->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('end_time') }}</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time', isset($election) ? $election->end_time->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-900 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-800">{{ __t('save') }}</button>
                <a href="{{ route('admin.elections') }}" class="bg-gray-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-gray-600">{{ __t('cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
