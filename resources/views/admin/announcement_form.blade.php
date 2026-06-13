@extends('layouts.admin')
@section('title', isset($announcement) ? __t('edit_announcement') : __t('create_announcement'))
@section('subtitle', isset($announcement) ? __t('edit_announcement_subtitle') : __t('create_announcement_subtitle'))
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">{{ isset($announcement) ? __t('edit_announcement') : __t('create_announcement') }}</h3>
        </div>
        <form method="POST" action="{{ isset($announcement) ? route('admin.announcements.edit', $announcement->id) : route('admin.announcements.create') }}" class="p-5">
            @csrf
            <div class="grid md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('title_en') }}</label>
                    <input type="text" name="title_en" required value="{{ old('title_en', $announcement->title_en ?? '') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('title_sw') }}</label>
                    <input type="text" name="title_sw" required value="{{ old('title_sw', $announcement->title_sw ?? '') }}">
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('content_en') }}</label>
                    <textarea name="content_en" required rows="4">{{ old('content_en', $announcement->content_en ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('content_sw') }}</label>
                    <textarea name="content_sw" required rows="4">{{ old('content_sw', $announcement->content_sw ?? '') }}</textarea>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('priority') }}</label>
                    <select name="priority" required>
                        <option value="normal" {{ (old('priority', $announcement->priority ?? '') === 'normal') ? 'selected' : '' }}>{{ __t('normal') }}</option>
                        <option value="high" {{ (old('priority', $announcement->priority ?? '') === 'high') ? 'selected' : '' }}>{{ __t('high') }}</option>
                        <option value="urgent" {{ (old('priority', $announcement->priority ?? '') === 'urgent') ? 'selected' : '' }}>{{ __t('urgent') }}</option>
                    </select>
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center space-x-2 cursor-pointer text-sm text-gray-700">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', isset($announcement) && $announcement->is_published) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>{{ __t('publish_immediately') }}</span>
                    </label>
                </div>
            </div>
            <div class="flex space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.announcements') }}" class="btn flex-1 text-center bg-gray-100 text-gray-700 font-medium py-2 rounded-lg text-sm hover:bg-gray-200 border border-gray-200">{{ __t('cancel') }}</a>
                <button type="submit" class="btn flex-1 text-center bg-blue-600 text-white font-medium py-2 rounded-lg text-sm hover:bg-blue-700">{{ __t('save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
