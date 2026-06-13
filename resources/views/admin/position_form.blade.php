@extends('layouts.admin')
@section('title', isset($position) ? 'Edit Position' : 'Create Position')
@section('subtitle', isset($position) ? 'Update position configuration' : 'Add a new election position')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">{{ isset($position) ? 'Edit Position' : 'Create Position' }}</h3>
        </div>
        <form method="POST" action="{{ isset($position) ? route('admin.positions.edit', $position->id) : route('admin.positions.create') }}" class="p-5">
            @csrf
            <div class="grid md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug *</label>
                    <input type="text" name="slug" value="{{ old('slug', $position->slug ?? '') }}" required placeholder="e.g. president">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order *</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $position->sort_order ?? 0) }}" min="0" required>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Name (English) *</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $position->name_en ?? '') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Name (Kiswahili) *</label>
                    <input type="text" name="name_sw" value="{{ old('name_sw', $position->name_sw ?? '') }}" required>
                </div>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                <textarea name="description" rows="3">{{ old('description', $position->description ?? '') }}</textarea>
            </div>
            <div class="grid md:grid-cols-3 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Minimum Age *</label>
                    <input type="number" name="min_age" value="{{ old('min_age', $position->min_age ?? 18) }}" min="1" max="150" required>
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="requires_constituency" value="1" {{ old('requires_constituency', $position->requires_constituency ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Requires Constituency</span>
                    </label>
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="requires_running_mate" value="1" {{ old('requires_running_mate', $position->requires_running_mate ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Requires Running Mate</span>
                    </label>
                </div>
            </div>
            <div class="flex space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 text-white font-medium py-2 px-6 rounded-lg text-sm hover:bg-blue-700">{{ isset($position) ? 'Update' : 'Create' }}</button>
                <a href="{{ route('admin.positions') }}" class="btn bg-gray-100 text-gray-700 font-medium py-2 px-6 rounded-lg text-sm hover:bg-gray-200 border border-gray-200">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
