@extends('layouts.admin')
@section('title', isset($user) ? __t('edit_user') : __t('create_user'))
@section('content')
<div class="max-w-lg mx-auto mt-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-blue-900 mb-6">{{ isset($user) ? __t('edit_user') : __t('create_user') }}</h2>
        <form method="POST" action="{{ isset($user) ? route('admin.users.edit', $user->id) : route('admin.users.create') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">{{ __t('full_name') }}</label>
                <input type="text" name="full_name" value="{{ old('full_name', $user->full_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">{{ __t('email') }}</label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">{{ __t('phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="07XXXXXXXX or +2557XXXXXXXX">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">{{ __t('nida_number') }}</label>
                <input type="text" name="nida_number" value="{{ old('nida_number', $user->nida_number ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="YYYYMMDD-XXXXX-XXXXX-XX">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">{{ __t('driving_licence') }}</label>
                <input type="text" name="driving_licence" value="{{ old('driving_licence', $user->driving_licence ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">{{ __t('nhif_number') }}</label>
                <input type="text" name="nhif_number" value="{{ old('nhif_number', $user->nhif_number ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">{{ __t('password') }} @if(isset($user)) ({{ __t('leave_blank_to_keep') }})@endif</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" @if(!isset($user)) required @endif minlength="8">
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">{{ __t('role_label') }}</label>
                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="voter" {{ old('role', $user->role ?? '') == 'voter' ? 'selected' : '' }}>{{ __t('voter') }}</option>
                        <option value="candidate" {{ old('role', $user->role ?? '') == 'candidate' ? 'selected' : '' }}>{{ __t('candidate') }}</option>
                        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>{{ __t('admin') }}</option>
                        <option value="officer" {{ old('role', $user->role ?? '') == 'officer' ? 'selected' : '' }}>{{ __t('electoral_officer') }}</option>
                        <option value="observer" {{ old('role', $user->role ?? '') == 'observer' ? 'selected' : '' }}>{{ __t('observer') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">{{ __t('status') }}</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="pending" {{ old('status', $user->status ?? '') == 'pending' ? 'selected' : '' }}>{{ __t('pending') }}</option>
                        <option value="active" {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>{{ __t('active') }}</option>
                        <option value="rejected" {{ old('status', $user->status ?? '') == 'rejected' ? 'selected' : '' }}>{{ __t('rejected') }}</option>
                    </select>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">{{ __t('age') }}</label>
                <input type="number" name="age" value="{{ old('age', $user->age ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" min="18">
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-900 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-800">{{ __t('save') }}</button>
                <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-gray-600">{{ __t('cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
