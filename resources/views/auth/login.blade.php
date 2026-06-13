@extends('layouts.app')
@section('title', __t('login'))
@section('content')
<div class="max-w-md mx-auto mt-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <div class="bg-white rounded-full p-3 inline-flex items-center justify-center mx-auto mb-4 shadow-lg"><img src="{{ asset('images/TUME.png') }}" alt="TUME Logo" class="h-16 w-16"></div>
            <h2 class="text-2xl font-bold text-blue-900">{{ __t('login') }}</h2>
            <p class="text-gray-600 text-sm">{{ __t('app_subtitle') }}</p>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('login_identifier') }}</label>
                <input type="text" name="identifier" value="{{ old('identifier') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required placeholder="{{ __t('nida') }} / {{ __t('driving_licence_label') }} / {{ __t('nhif') }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('password') }}</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Security Check: {{ session('captcha_question') }} = ?</label>
                <input type="number" name="captcha" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required placeholder="Enter the answer">
                @error('captcha') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-800">{{ __t('login_btn') }}</button>
        </form>
        <p class="text-center text-sm text-gray-600 mt-4">
            {{ __t('no_account') }} <a href="{{ route('register') }}" class="text-blue-900 underline">{{ __t('register') }}</a>
        </p>
    </div>
</div>
@endsection
