@extends('layouts.admin')
@section('title', $presetPosition === 'presidential' ? __t('register_presidential_candidate') : __t('register_parliamentary_candidate'))
@section('subtitle', __t('register_candidate_subtitle'))
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">
                @if($presetPosition === 'presidential')
                    {{ __t('register_presidential_candidate') }}
                @elseif(in_array($presetPosition, ['parliamentary', 'councillor']))
                    {{ $presetPosition === 'parliamentary' ? __t('register_parliamentary_candidate') : __t('register_councillor_candidate') }}
                @else
                    {{ __t('register_candidate') }}
                @endif
            </h2>
            <span class="text-xs px-3 py-1 rounded-full font-medium
                @if($presetPosition === 'presidential') bg-blue-100 text-blue-700
                @else bg-green-100 text-green-700 @endif">
                @if($presetPosition === 'presidential') {{ __t('presidential') }}
                @else {{ $presetPosition === 'parliamentary' ? __t('parliamentary') : __t('councillor') }} @endif
            </span>
        </div>
        <form method="POST" action="{{ $presetPosition ? route('admin.candidates.register', $presetPosition) : route('admin.candidates.register', 'presidential') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf

            {{-- 1. Personal Details --}}
            <fieldset>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">1</span>
                    {{ __t('personal_details') }}
                </legend>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('full_name') }} *</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('gender') }} *</label>
                        <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                            <option value="">{{ __t('select') }}</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __t('male') }}</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __t('female') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('date_of_birth') }} *</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($presetPosition === 'presidential') {{ __t('min_age_40') }}
                            @else {{ __t('min_age_21') }} @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('nationality') }} *</label>
                        <input type="text" name="nationality" value="{{ old('nationality', 'Tanzanian') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('nida_number') }} *</label>
                        <input type="text" name="nida_number" value="{{ old('nida_number') }}" placeholder="YYYYMMDD-XXXXX-XXXXX-XXXX" maxlength="25" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <p class="text-xs text-gray-500 mt-1">{{ __t('nida_format_hint') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('phone') }} *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="07XXXXXXXX or +2557XXXXXXXX" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('email') }} *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    @if(in_array($presetPosition, ['parliamentary', 'councillor']))
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('residential_address') }} *</label>
                        <input type="text" name="residential_address" value="{{ old('residential_address') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    @endif
                </div>
            </fieldset>

            {{-- 2. Election & Constituency --}}
            <fieldset>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">2</span>
                    @if(in_array($presetPosition, ['parliamentary', 'councillor'])) {{ __t('parliamentary_election_info') }}
                    @else {{ __t('election_info') }} @endif
                </legend>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('user') }} *</label>
                        <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                            <option value="">{{ __t('select_candidate') }}</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>{{ $u->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('election_id') }} *</label>
                        <input type="text" name="election_id" value="{{ old('election_id') }}" placeholder="e.g. 1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                        <p class="text-xs text-gray-500 mt-1">Enter the election ID number from the elections list</p>
                    </div>
                    @if(!$presetPosition)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('position_label') }} *</label>
                        <select name="position" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                            @foreach($positions as $pos)
                            <option value="{{ $pos->slug }}" data-constituency="{{ $pos->requires_constituency ? '1' : '0' }}" data-running-mate="{{ $pos->requires_running_mate ? '1' : '0' }}" {{ old('position') == $pos->slug ? 'selected' : '' }}>
                                {{ session('lang') == 'sw' ? $pos->name_sw : $pos->name_en }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="position" value="{{ $presetPosition }}">
                    @endif

                    @php
                        $posModel = $positions->firstWhere('slug', $presetPosition);
                    @endphp

                    @if($posModel && $posModel->requires_constituency)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('constituency') }} *</label>
                        <select name="constituency_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                            <option value="">{{ __t('select_constituency') }}</option>
                            @foreach($constituencies as $c)
                                <option value="{{ $c->id }}" data-region="{{ $c->region }}" {{ old('constituency_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->region }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('region') }}</label>
                        <input type="text" id="region_display" readonly class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-600" placeholder="{{ __t('auto_filled') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('ward') }}</label>
                        <input type="text" name="ward_name" value="{{ old('ward_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('party_membership_number') }}</label>
                        <input type="text" name="party_membership_number" value="{{ old('party_membership_number') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    @endif
                </div>
            </fieldset>

            {{-- 3. Political Party --}}
            <fieldset>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">3</span>
                    {{ __t('political_party_info') }}
                </legend>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('party') }} *</label>
                        <select name="party_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                            <option value="">{{ __t('select') }}</option>
                            @foreach($parties as $p)
                                <option value="{{ $p->id }}" data-abbr="{{ $p->abbreviation }}" {{ old('party_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->abbreviation }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('party_logo_label') }}</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition">
                            <img id="logo_preview" class="hidden mx-auto h-20 w-20 object-contain mb-2">
                            <input type="file" name="party_logo" accept="image/png,image/jpg,image/jpeg" class="w-full text-sm" onchange="document.getElementById('logo_preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('logo_preview').classList.remove('hidden')">
                            <p class="text-xs text-gray-500 mt-1">{{ __t('logo_requirements') }}</p>
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- 4. Running Mate (Presidential only) --}}
            @if($presetPosition === 'presidential')
            <fieldset>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">4</span>
                    {{ __t('running_mate') }}
                </legend>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('running_mate_name') }} *</label>
                        <input type="text" name="running_mate_name" value="{{ old('running_mate_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('running_mate_photo') }}</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition">
                            <img id="rm_photo_preview" class="hidden mx-auto h-20 w-20 object-cover rounded-lg mb-2">
                            <input type="file" name="running_mate_photo" accept="image/jpg,image/jpeg,image/png" class="w-full text-sm" onchange="document.getElementById('rm_photo_preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('rm_photo_preview').classList.remove('hidden')">
                            <p class="text-xs text-gray-500 mt-1">{{ __t('photo_requirements') }}</p>
                        </div>
                    </div>
                </div>
            </fieldset>
            @endif

            {{-- 4/5. Candidate Photo & Documents --}}
            <fieldset>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">{{ $presetPosition === 'presidential' ? '5' : '4' }}</span>
                    {{ __t('candidate_media') }}
                </legend>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('candidate_photo') }} *</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition">
                            <img id="photo_preview" class="hidden mx-auto h-32 w-32 object-cover rounded-lg mb-2">
                            <input type="file" name="photo" accept="image/jpg,image/jpeg,image/png" class="w-full text-sm" onchange="document.getElementById('photo_preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('photo_preview').classList.remove('hidden')">
                            <p class="text-xs text-gray-500 mt-1">{{ __t('photo_requirements') }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('required_documents') }}</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition">
                            <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm">
                            <p class="text-xs text-gray-500 mt-1">{{ __t('documents_requirements') }}</p>
                        </div>
                    </div>
                </div>
            </fieldset>

            {{-- 5/6. Additional Info --}}
            <fieldset>
                <legend class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <span class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-sm mr-2">{{ $presetPosition === 'presidential' ? '6' : '5' }}</span>
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

            <div class="flex space-x-4 pt-4 border-t border-gray-200">
                <button type="submit" class="bg-blue-900 text-white font-semibold py-2 px-8 rounded-lg hover:bg-blue-800">{{ __t('register_btn') }}</button>
                <a href="{{ route('admin.candidates') }}" class="bg-gray-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">{{ __t('cancel') }}</a>
            </div>
        </form>
    </div>
</div>
<script>
document.querySelector('select[name="constituency_id"]')?.addEventListener('change', function() {
    var selected = this.options[this.selectedIndex];
    var region = selected.getAttribute('data-region') || '';
    document.getElementById('region_display').value = region;
});

document.querySelector('input[name="nida_number"]')?.addEventListener('input', function() {
    var nida = this.value.replace(/[^0-9]/g, '');
    var dobField = document.getElementById('date_of_birth');
    if (nida.length >= 8) {
        var year = nida.substring(0, 4);
        var month = nida.substring(4, 6);
        var day = nida.substring(6, 8);
        if (month >= 1 && month <= 12 && day >= 1 && day <= 31) {
            dobField.value = year + '-' + month + '-' + day;
        }
    } else {
        dobField.value = '';
    }
});
</script>
@endsection
