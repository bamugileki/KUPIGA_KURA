@extends('layouts.admin')
@section('title', isset($announcement) ? __t('edit_announcement') : __t('create_announcement'))
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-blue-900 mb-6">{{ isset($announcement) ? __t('edit_announcement') : __t('create_announcement') }}</h2>
        <form method="POST" action="{{ isset($announcement) ? route('admin.announcements.edit', $announcement->id) : route('admin.announcements.create') }}">
            @csrf
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('title_en') }}</label>
                    <input type="text" name="title_en" required value="{{ old('title_en', $announcement->title_en ?? '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('title_sw') }}</label>
                    <input type="text" name="title_sw" required value="{{ old('title_sw', $announcement->title_sw ?? '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('content_en') }}</label>
                    <textarea name="content_en" required rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('content_en', $announcement->content_en ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('content_sw') }}</label>
                    <textarea name="content_sw" required rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('content_sw', $announcement->content_sw ?? '') }}</textarea>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1 text-sm">{{ __t('priority') }}</label>
                    <select name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="normal" {{ (old('priority', $announcement->priority ?? '') === 'normal') ? 'selected' : '' }}>{{ __t('normal') }}</option>
                        <option value="high" {{ (old('priority', $announcement->priority ?? '') === 'high') ? 'selected' : '' }}>{{ __t('high') }}</option>
                        <option value="urgent" {{ (old('priority', $announcement->priority ?? '') === 'urgent') ? 'selected' : '' }}>{{ __t('urgent') }}</option>
                    </select>
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center space-x-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', isset($announcement) && $announcement->is_published) ? 'checked' : '' }} class="rounded border-gray-300">
                        <span>{{ __t('publish_immediately') }}</span>
                    </label>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.announcements') }}" class="flex-1 text-center bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg hover:bg-gray-300">{{ __t('cancel') }}</a>
                <button type="submit" class="flex-1 bg-blue-900 text-white font-bold py-2 rounded-lg hover:bg-blue-800">{{ __t('save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
