@extends('layouts.app')
@section('title', __t('candidate_apply_link'))
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">{{ __t('candidate_apply_link') }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ __t('become_candidate') }}</p>
        </div>

        @if(session('error'))
            <div class="mx-6 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('candidates.apply.store') }}" class="p-6 space-y-6">
            @csrf

            <fieldset>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">1</span>
                    {{ __t('election_info') }}
                </legend>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('election_title') }} *</label>
                        <select name="election_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                            <option value="">{{ __t('select_election') }}</option>
                            @foreach($elections as $election)
                                <option value="{{ $election->id }}" data-type="{{ $election->election_type }}" {{ old('election_id') == $election->id ? 'selected' : '' }}>
                                    {{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}
                                    ({{ __t($election->election_type) }})
                                </option>
                            @endforeach
                        </select>
                        @error('election_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('position_label') }} *</label>
                        <select name="position" id="position_select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                            <option value="">{{ __t('select') }}</option>
                            <option value="presidential" {{ old('position') == 'presidential' ? 'selected' : '' }}>{{ __t('presidential') }}</option>
                            <option value="parliamentary" {{ old('position') == 'parliamentary' ? 'selected' : '' }}>{{ __t('parliamentary') }}</option>
                            <option value="councillor" {{ old('position') == 'councillor' ? 'selected' : '' }}>{{ __t('councillor') }}</option>
                        </select>
                        @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            <fieldset id="constituency_fieldset" @if(old('position') !== 'parliamentary' && old('position') !== 'councillor') style="display:none" @endif>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">2</span>
                    {{ __t('constituency') }}
                </legend>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('constituency') }} *</label>
                    <select name="constituency_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">{{ __t('select_constituency') }}</option>
                        @foreach($constituencies as $c)
                            <option value="{{ $c->id }}" {{ old('constituency_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->region }})</option>
                        @endforeach
                    </select>
                    @error('constituency_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </fieldset>

            <fieldset>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">{{ old('position') == 'parliamentary' || old('position') == 'councillor' ? '3' : '2' }}</span>
                    {{ __t('party') }}
                </legend>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('party') }} *</label>
                    <select name="party_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <option value="">{{ __t('select') }}</option>
                        @foreach($parties as $p)
                            <option value="{{ $p->id }}" {{ old('party_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->abbreviation }})</option>
                        @endforeach
                    </select>
                    @error('party_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </fieldset>

            <fieldset>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">{{ old('position') == 'parliamentary' || old('position') == 'councillor' ? '4' : '3' }}</span>
                    {{ __t('additional_information') }}
                </legend>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('manifesto_label') }}</label>
                        <textarea name="manifesto" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="{{ __t('manifesto_placeholder') }}">{{ old('manifesto') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('biography') }}</label>
                        <textarea name="biography" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('biography') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('education') }}</label>
                        <textarea name="education" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="{{ __t('education_placeholder') }}">{{ old('education') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('political_experience') }}</label>
                        <textarea name="political_experience" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="{{ __t('experience_placeholder') }}">{{ old('political_experience') }}</textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">{{ old('position') == 'parliamentary' || old('position') == 'councillor' ? '5' : '4' }}</span>
                    {{ __t('registration_terms') }}
                </legend>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-700 space-y-2">
                    <label class="flex items-start">
                        <input type="checkbox" name="terms_accepted" value="1" class="mt-1 mr-3" {{ old('terms_accepted') ? 'checked' : '' }}>
                        <span>{{ __t('candidate_terms_required') }}</span>
                    </label>
                    @error('terms_accepted') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </fieldset>

            <div class="flex space-x-4 pt-4 border-t border-gray-200">
                <button type="submit" class="bg-blue-900 text-white font-semibold py-2 px-8 rounded-lg hover:bg-blue-800">{{ __t('register_btn') }}</button>
                <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">{{ __t('cancel') }}</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('position_select')?.addEventListener('change', function() {
    var fieldset = document.getElementById('constituency_fieldset');
    var constituencySelect = fieldset.querySelector('select');
    if (this.value === 'parliamentary' || this.value === 'councillor') {
        fieldset.style.display = '';
        constituencySelect.required = true;
    } else {
        fieldset.style.display = 'none';
        constituencySelect.required = false;
    }
});

document.querySelector('select[name="election_id"]')?.addEventListener('change', function() {
    var selected = this.options[this.selectedIndex];
    var type = selected.getAttribute('data-type');
    var posSelect = document.getElementById('position_select');
    if (type) {
        for (var i = 0; i < posSelect.options.length; i++) {
            if (posSelect.options[i].value === type) {
                posSelect.value = type;
                posSelect.options[i].selected = true;
                posSelect.dispatchEvent(new Event('change'));
                break;
            }
        }
    }
});
</script>
@endsection