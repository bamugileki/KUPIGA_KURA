@extends('layouts.admin')
@section('title', isset($election) ? __t('edit_election') : __t('create_election'))
@section('subtitle', isset($election) ? __t('edit_election_subtitle') : __t('create_election_subtitle'))
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">{{ isset($election) ? __t('edit_election') : __t('create_election') }}</h3>
        </div>
        <form method="POST" action="{{ isset($election) ? route('admin.elections.edit', $election->id) : route('admin.elections.create') }}" class="p-5">
            @csrf
            <div class="grid md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('election_title') }} (English)</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $election->title_en ?? '') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('election_title') }} (Kiswahili)</label>
                    <input type="text" name="title_sw" value="{{ old('title_sw', $election->title_sw ?? '') }}" required>
                </div>
            </div>
            @if(!isset($election))
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('election_type') }}</label>
                <select name="election_type" required>
                    @foreach(\App\Models\Position::orderBy('sort_order')->get() as $pos)
                    <option value="{{ $pos->slug }}" {{ old('election_type') == $pos->slug ? 'selected' : '' }}>
                        {{ session('lang') == 'sw' ? $pos->name_sw : $pos->name_en }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="px-4 py-3 bg-gray-50 rounded-lg mb-5">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __t('nomination_period') }}</h4>
            </div>
            <div class="grid md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('nomination_start') }}</label>
                    <input type="datetime-local" name="nomination_start" value="{{ old('nomination_start', isset($election) && $election->nomination_start ? $election->nomination_start->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('nomination_end') }}</label>
                    <input type="datetime-local" name="nomination_end" value="{{ old('nomination_end', isset($election) && $election->nomination_end ? $election->nomination_end->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 rounded-lg mb-5">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __t('campaign_period') }}</h4>
            </div>
            <div class="grid md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('campaign_start') }}</label>
                    <input type="datetime-local" name="campaign_start" value="{{ old('campaign_start', isset($election) && $election->campaign_start ? $election->campaign_start->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('campaign_end') }}</label>
                    <input type="datetime-local" name="campaign_end" value="{{ old('campaign_end', isset($election) && $election->campaign_end ? $election->campaign_end->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 rounded-lg mb-5">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __t('voting_period') }}</h4>
            </div>
            <div class="grid md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('start_time') }}</label>
                    <input type="datetime-local" name="start_time" value="{{ old('start_time', isset($election) ? $election->start_time->format('Y-m-d\TH:i') : '') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('end_time') }}</label>
                    <input type="datetime-local" name="end_time" value="{{ old('end_time', isset($election) ? $election->end_time->format('Y-m-d\TH:i') : '') }}" required>
                </div>
            </div>

            <div class="flex space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 text-white font-medium py-2 px-6 rounded-lg text-sm hover:bg-blue-700">{{ __t('save') }}</button>
                <a href="{{ route('admin.elections') }}" class="btn bg-gray-100 text-gray-700 font-medium py-2 px-6 rounded-lg text-sm hover:bg-gray-200 border border-gray-200">{{ __t('cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
