@extends('layouts.app')
@section('title', __t('profile'))
@section('content')
<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-bold text-lg text-blue-900 mb-4">{{ __t('profile') }}</h3>
        <div class="space-y-2 text-sm">
            <p><strong>{{ __t('full_name') }}:</strong> {{ $user->full_name }}</p>
            <p><strong>{{ __t('email') }}:</strong> {{ $user->email }}</p>
            <p><strong>{{ __t('phone') }}:</strong> {{ $user->phone ?? '-' }}</p>
            <p><strong>{{ __t('nida') }}:</strong> {{ $user->nida_number ?? '-' }}</p>
            <p><strong>{{ __t('driving_licence_label') }}:</strong> {{ $user->driving_licence ?? '-' }}</p>
            <p><strong>{{ __t('nhif') }}:</strong> {{ $user->nhif_number ?? '-' }}</p>
            <p><strong>{{ __t('role_label') }}:</strong> {{ __t($user->role) }}</p>
            <p><strong>{{ __t('account_status') }}:</strong>
                <span class="text-xs px-2 py-1 rounded
                    @if($user->status === 'active') bg-green-100 text-green-800
                    @elseif($user->status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">{{ __t($user->status) }}</span>
            </p>
        </div>
    </div>
    <div>
        @if($userCandidacy)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-bold text-lg text-blue-900 mb-4">{{ __t('my_candidacy') }}</h3>
                <p><strong>{{ __t('position_label') }}:</strong> {{ __t($userCandidacy->position) }}</p>
                @if($userCandidacy->constituency)<p><strong>{{ __t('constituency') }}:</strong> {{ $userCandidacy->constituency }}</p>@endif
                <p><strong>{{ __t('status') }}:</strong> <span class="text-xs px-2 py-1 rounded
                    @if($userCandidacy->status === 'approved') bg-green-100 text-green-800
                    @elseif($userCandidacy->status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">{{ __t($userCandidacy->status) }}</span></p>
            </div>
        @elseif($user->role !== 'voter')
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-bold text-lg text-blue-900 mb-3">{{ __t('register_candidate') }}</h3>
                <p class="text-gray-600 mb-3">{{ __t('become_candidate') }}</p>
                <a href="{{ route('candidates.apply') }}" class="inline-block bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">{{ __t('register_candidate') }}</a>
            </div>
        @endif
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="font-bold text-lg text-blue-900 mb-4">{{ __t('change_password') }}</h3>
            <form method="POST" action="{{ route('profile.change_password') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('password') }} {{ __t('current') }}</label>
                    <input type="password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('password') }} {{ __t('new') }}</label>
                    <input type="password" name="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('confirm_password') }}</label>
                    <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm" required>
                </div>
                <button type="submit" class="bg-blue-900 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-800 text-sm">{{ __t('save') }}</button>
            </form>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-lg text-blue-900 mb-4">{{ __t('language_settings') }}</h3>
            <p class="mb-3">{{ __t('select_language') }}:</p>
            <div class="flex space-x-2">
                <a href="{{ route('language.set', 'en') }}" class="px-4 py-2 rounded text-sm {{ session('lang') == 'en' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __t('english') }}</a>
                <a href="{{ route('language.set', 'sw') }}" class="px-4 py-2 rounded text-sm {{ session('lang') == 'sw' ? 'bg-blue-900 text-white' : 'bg-gray-200 text-gray-700' }}">{{ __t('kiswahili') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
