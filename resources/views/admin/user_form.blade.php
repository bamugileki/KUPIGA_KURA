@extends('layouts.admin')
@section('title', isset($user) ? __t('edit_user') : __t('create_user'))
@section('subtitle', isset($user) ? __t('edit_user_subtitle') : __t('create_user_subtitle'))
@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">{{ isset($user) ? __t('edit_user') : __t('create_user') }}</h3>
        </div>
        <form method="POST" action="{{ isset($user) ? route('admin.users.edit', $user->id) : route('admin.users.create') }}" class="p-5">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('full_name') }}</label>
                <input type="text" name="full_name" value="{{ old('full_name', $user->full_name ?? '') }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('email') }}</label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="07XXXXXXXX or +2557XXXXXXXX">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('nida_number') }}</label>
                <input type="text" name="nida_number" value="{{ old('nida_number', $user->nida_number ?? '') }}" placeholder="YYYYMMDD-XXXXX-XXXXX-XX">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('driving_licence') }}</label>
                <input type="text" name="driving_licence" value="{{ old('driving_licence', $user->driving_licence ?? '') }}">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('nhif_number') }}</label>
                <input type="text" name="nhif_number" value="{{ old('nhif_number', $user->nhif_number ?? '') }}">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('password') }}@if(isset($user)) <span class="text-gray-400 font-normal">({{ __t('leave_blank_to_keep') }})</span>@endif</label>
                <input type="password" name="password" @if(!isset($user)) required @endif minlength="8">
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('role_label') }}</label>
                    <select name="role" required>
                        <option value="voter" {{ old('role', $user->role ?? '') == 'voter' ? 'selected' : '' }}>{{ __t('voter') }}</option>
                        <option value="candidate" {{ old('role', $user->role ?? '') == 'candidate' ? 'selected' : '' }}>{{ __t('candidate') }}</option>
                        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>{{ __t('admin') }}</option>
                        <option value="officer" {{ old('role', $user->role ?? '') == 'officer' ? 'selected' : '' }}>{{ __t('electoral_officer') }}</option>
                        <option value="observer" {{ old('role', $user->role ?? '') == 'observer' ? 'selected' : '' }}>{{ __t('observer') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('status') }}</label>
                    <select name="status" required>
                        <option value="pending" {{ old('status', $user->status ?? '') == 'pending' ? 'selected' : '' }}>{{ __t('pending') }}</option>
                        <option value="active" {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>{{ __t('active') }}</option>
                        <option value="rejected" {{ old('status', $user->status ?? '') == 'rejected' ? 'selected' : '' }}>{{ __t('rejected') }}</option>
                    </select>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('age') }}</label>
                <input type="number" name="age" value="{{ old('age', $user->age ?? '') }}" min="18">
            </div>

            <div class="border-t border-gray-100 pt-5 mb-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-1">♿ {{ __t('accessibility_settings') }}</h4>
                <p class="text-xs text-gray-400 mb-4">{{ __t('accessibility_officer_hint') }}</p>

                <div class="mb-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="accessibility_enabled" value="1" {{ old('accessibility_enabled', $user->accessibility_enabled ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">{{ __t('enable_accessibility') }}</span>
                    </label>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('accessibility_mode_label') }}</label>
                        <select name="accessibility_mode">
                            <option value="normal" {{ old('accessibility_mode', $user->accessibility_mode ?? '') === 'normal' ? 'selected' : '' }}>{{ __t('mode_normal') }}</option>
                            <option value="blind" {{ old('accessibility_mode', $user->accessibility_mode ?? '') === 'blind' ? 'selected' : '' }}>{{ __t('mode_blind') }}</option>
                            <option value="low_vision" {{ old('accessibility_mode', $user->accessibility_mode ?? '') === 'low_vision' ? 'selected' : '' }}>{{ __t('mode_low_vision') }}</option>
                            <option value="hearing" {{ old('accessibility_mode', $user->accessibility_mode ?? '') === 'hearing' ? 'selected' : '' }}>{{ __t('mode_hearing') }}</option>
                            <option value="motor" {{ old('accessibility_mode', $user->accessibility_mode ?? '') === 'motor' ? 'selected' : '' }}>{{ __t('mode_motor') }}</option>
                            <option value="cognitive" {{ old('accessibility_mode', $user->accessibility_mode ?? '') === 'cognitive' ? 'selected' : '' }}>{{ __t('mode_cognitive') }}</option>
                            <option value="elderly" {{ old('accessibility_mode', $user->accessibility_mode ?? '') === 'elderly' ? 'selected' : '' }}>{{ __t('mode_elderly') }}</option>
                            <option value="assisted" {{ old('accessibility_mode', $user->accessibility_mode ?? '') === 'assisted' ? 'selected' : '' }}>{{ __t('mode_assisted') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __t('text_size') }}</label>
                        <select name="text_size">
                            <option value="small" {{ old('text_size', $user->text_size ?? '') === 'small' ? 'selected' : '' }}>{{ __t('text_small') }}</option>
                            <option value="medium" {{ old('text_size', $user->text_size ?? '') === 'medium' ? 'selected' : '' }}>{{ __t('text_medium') }}</option>
                            <option value="large" {{ old('text_size', $user->text_size ?? '') === 'large' ? 'selected' : '' }}>{{ __t('text_large') }}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="high_contrast" value="1" {{ old('high_contrast', $user->high_contrast ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">{{ __t('high_contrast') }}</span>
                    </label>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-xs text-blue-700">
                    <strong>{{ __t('accessibility_privacy_note_title') }}:</strong> {{ __t('accessibility_privacy_note') }}
                </div>
            </div>

            <div class="flex space-x-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 text-white font-medium py-2 px-6 rounded-lg text-sm hover:bg-blue-700">{{ __t('save') }}</button>
                <a href="{{ route('admin.users') }}" class="btn bg-gray-100 text-gray-700 font-medium py-2 px-6 rounded-lg text-sm hover:bg-gray-200 border border-gray-200">{{ __t('cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
