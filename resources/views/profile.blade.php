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

        {{-- Accessibility Settings --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6" id="accessibilitySettings">
            <h3 class="font-bold text-lg text-blue-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                ♿ {{ __t('accessibility_settings') }}
            </h3>
            <form method="POST" action="{{ route('profile.update_accessibility') }}">
                @csrf
                <div class="mb-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="accessibility_enabled" value="1" {{ $user->accessibility_enabled ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-blue-900 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">{{ __t('enable_accessibility') }}</span>
                    </label>
                </div>

                <div id="accessibilityFields" class="space-y-4 {{ $user->accessibility_enabled ? '' : 'hidden' }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('disability_type') }}</label>
                        <div class="grid grid-cols-2 gap-2">
                            @php $disabilities = json_decode($user->disability_type ?? '[]', true); @endphp
                            @foreach([
                                ['value' => 'visual', 'label_en' => 'Vision Impairment', 'label_sw' => 'Ulemavu wa Macho'],
                                ['value' => 'hearing', 'label_en' => 'Hearing Impairment', 'label_sw' => 'Ulemavu wa Kusikia'],
                                ['value' => 'cognitive', 'label_en' => 'Cognitive Disability', 'label_sw' => 'Ulemavu wa Akili'],
                                ['value' => 'motor', 'label_en' => 'Physical/Motor Disability', 'label_sw' => 'Ulemavu wa Mwili'],
                                ['value' => 'elderly', 'label_en' => 'Elderly Support', 'label_sw' => 'Msaada kwa Wazee'],
                                ['value' => 'speech', 'label_en' => 'Speech Impairment', 'label_sw' => 'Ulemavu wa Kuongea'],
                                ['value' => 'other', 'label_en' => 'Other / Prefer not to say', 'label_sw' => 'Nyingine / Sitaki kusema'],
                            ] as $d)
                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                <input type="checkbox" name="disability_type[]" value="{{ $d['value'] }}"
                                    {{ in_array($d['value'], $disabilities) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-900 focus:ring-blue-500">
                                {{ session('lang') == 'sw' ? $d['label_sw'] : $d['label_en'] }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('accessibility_mode_label') }}</label>
                        <select name="accessibility_mode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
                            <option value="normal" {{ $user->accessibility_mode === 'normal' ? 'selected' : '' }}>{{ __t('mode_normal') }}</option>
                            <option value="blind" {{ $user->accessibility_mode === 'blind' ? 'selected' : '' }}>{{ __t('mode_blind') }}</option>
                            <option value="low_vision" {{ $user->accessibility_mode === 'low_vision' ? 'selected' : '' }}>{{ __t('mode_low_vision') }}</option>
                            <option value="hearing" {{ $user->accessibility_mode === 'hearing' ? 'selected' : '' }}>{{ __t('mode_hearing') }}</option>
                            <option value="motor" {{ $user->accessibility_mode === 'motor' ? 'selected' : '' }}>{{ __t('mode_motor') }}</option>
                            <option value="cognitive" {{ $user->accessibility_mode === 'cognitive' ? 'selected' : '' }}>{{ __t('mode_cognitive') }}</option>
                            <option value="elderly" {{ $user->accessibility_mode === 'elderly' ? 'selected' : '' }}>{{ __t('mode_elderly') }}</option>
                            <option value="assisted" {{ $user->accessibility_mode === 'assisted' ? 'selected' : '' }}>{{ __t('mode_assisted') }}</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('text_size') }}</label>
                            <select name="text_size" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
                                <option value="small" {{ $user->text_size === 'small' ? 'selected' : '' }}>{{ __t('text_small') }}</option>
                                <option value="medium" {{ $user->text_size === 'medium' ? 'selected' : '' }}>{{ __t('text_medium') }}</option>
                                <option value="large" {{ $user->text_size === 'large' ? 'selected' : '' }}>{{ __t('text_large') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 mt-6 cursor-pointer">
                                <input type="checkbox" name="high_contrast" value="1" {{ $user->high_contrast ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-blue-900 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">{{ __t('high_contrast') }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-800">
                        <strong>{{ __t('accessibility_privacy_note_title') }}:</strong> {{ __t('accessibility_privacy_note') }}
                    </div>
                </div>

                <button type="submit" class="mt-4 bg-blue-900 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-800 text-sm">{{ __t('save') }}</button>
            </form>
        </div>

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

<script>
(function() {
    var enabledCheckbox = document.querySelector('[name="accessibility_enabled"]');
    var fields = document.getElementById('accessibilityFields');
    if (enabledCheckbox && fields) {
        enabledCheckbox.addEventListener('change', function() {
            fields.classList.toggle('hidden', !this.checked);
        });
    }
})();
</script>
@endsection
