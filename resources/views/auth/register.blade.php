@extends('layouts.app')
@section('title', __t('register'))
@section('content')
<div class="max-w-lg mx-auto mt-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <div class="bg-white rounded-full p-3 inline-flex items-center justify-center mx-auto mb-4 shadow-lg"><img src="{{ asset('images/TUME.png') }}" alt="TUME Logo" class="h-16 w-16"></div>
            <h2 class="text-2xl font-bold text-blue-900">{{ __t('register') }}</h2>
            <p class="text-gray-600 text-sm">{{ __t('at_least_one_id') }}</p>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('full_name') }}</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('phone') }} <span class="text-xs text-gray-500">({{ __t('phone_tz_hint') }})</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="+2557XXXXXXXX">
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('password') }}</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required minlength="8">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('confirm_password') }}</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
            </div>
            <hr class="my-4">
            <p class="text-sm text-gray-600 mb-4"><strong>{{ __t('at_least_one_id') }}</strong></p>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('nida_number') }}</label>
                <input type="text" name="nida_number" value="{{ old('nida_number') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="YYYYMMDD-XXXXX-XXXXX-XX">
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('driving_licence_label') }}</label>
                    <input type="text" name="driving_licence" value="{{ old('driving_licence') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">{{ __t('nhif_number') }}</label>
                    <input type="text" name="nhif_number" value="{{ old('nhif_number') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-900 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-800">{{ __t('register_btn') }}</button>
        </form>
        <p class="text-center text-sm text-gray-600 mt-4">
            {{ __t('already_have_account') }} <a href="{{ route('login') }}" class="text-blue-900 underline">{{ __t('login') }}</a>
        </p>
    </div>
</div>
@endsection
