@extends('layouts.app')
@section('title', __t('cast_vote'))
@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Already voted receipt --}}
    @if(session('vote_receipt'))
    <div class="bg-white rounded-lg shadow-lg p-8 text-center mb-6 scale-in">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 success-check">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h2 class="text-2xl font-bold text-green-700 mb-2">{{ __t('vote_success_title') }}</h2>
        <p class="text-gray-600 mb-4">{{ __t('vote_success_message') }}</p>
        <div class="bg-gray-50 rounded-lg p-4 inline-block text-left text-sm space-y-1">
            <p><strong>{{ __t('election_title') }}:</strong> {{ session('lang') == 'sw' ? session('vote_receipt')->election_title_sw : session('vote_receipt')->election_title_en }}</p>
            <p><strong>{{ __t('result_candidate') }}:</strong> {{ session('vote_receipt')->candidate_name }}</p>
            <p><strong>{{ __t('voted_at') }}:</strong> {{ session('vote_receipt')->voted_at }}</p>
            <p><strong>{{ __t('vote_id') }}:</strong> #{{ session('vote_receipt')->vote_id }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="inline-block mt-6 bg-blue-900 text-white font-semibold py-2 px-8 rounded-lg hover:bg-blue-800">{{ __t('back') }} {{ __t('dashboard') }}</a>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                @php $pos = \App\Models\Position::where('slug', $election->election_type)->first(); @endphp
                {{ session('lang') == 'sw' ? ($pos->name_sw ?? $election->election_type) : ($pos->name_en ?? $election->election_type) }}
                @if($pos && $pos->requires_constituency && Auth::user()->constituency)
                    | {{ __t('constituency') }}: {{ Auth::user()->constituency->name }}
                @endif
            </p>
        </div>
        <p class="text-red-600 text-sm px-6 pt-4">{{ __t('vote_immutable') }}</p>
        <form id="voteForm" method="POST" action="{{ route('vote.cast', $election->id) }}">
            @csrf
            <div class="p-6">
                <h3 class="font-semibold text-gray-700 mb-4">{{ __t('select_candidate') }}</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    @forelse($candidates as $candidate)
                    <label class="candidate-card border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-blue-500 hover:shadow-md transition-all has-[:checked]:border-green-500 has-[:checked]:bg-green-50 has-[:checked]:shadow-md">
                        <input type="radio" name="candidate_id" value="{{ $candidate->id }}" class="hidden" required>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($candidate->photo)
                                <img src="{{ asset($candidate->photo) }}" alt="Photo" class="h-16 w-16 rounded-full object-cover border-2 border-gray-200">
                                @else
                                <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xl font-bold border-2 border-gray-200">{{ substr($candidate->full_name, 0, 1) }}</div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-800 truncate">{{ $candidate->full_name }}</h4>
                                <div class="flex items-center space-x-2 mt-1">
                                    @if($candidate->party_logo)
                                    <img src="{{ asset($candidate->party_logo) }}" alt="Logo" class="h-5 w-5 object-contain">
                                    @endif
                                    <span class="text-sm font-medium text-blue-900">{{ $candidate->party_abbreviation }}</span>
                                </div>
                                @if($candidate->running_mate_name)
                                <p class="text-xs text-gray-500 mt-1">{{ __t('running_mate') }}: {{ $candidate->running_mate_name }}</p>
                                @endif
                                @if($candidate->constituency)
                                <p class="text-xs text-gray-500 mt-1">{{ $candidate->constituency }}</p>
                                @endif
                                @if($candidate->manifesto)
                                <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ Str::limit($candidate->manifesto, 80) }}</p>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center candidate-radio">
                                    <div class="w-3 h-3 rounded-full hidden candidate-dot"></div>
                                </div>
                            </div>
                        </div>
                    </label>
                    @empty
                    <div class="md:col-span-2 text-center py-8 text-gray-500">{{ __t('no_candidates_available') }}</div>
                    @endforelse
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800 font-medium">{{ __t('cancel') }}</a>
                    <a href="{{ route('vote.assisted', $election->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">{{ __t('assisted_voting') }}</a>
                </div>
                <button type="button" id="confirmBtn" class="vote-btn bg-green-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>{{ __t('submit_vote') }}</button>
            </div>
        </form>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ __t('confirm_vote_title') }}</h3>
            <p class="text-gray-600 mb-4">{{ __t('confirm_vote_message') }}</p>
            <div id="confirmCandidate" class="bg-gray-50 rounded-xl p-4 mb-6 flex items-center space-x-4 text-left">
                <div id="confirmPhoto" class="h-14 w-14 rounded-full bg-gray-200 flex-shrink-0"></div>
                <div>
                    <p id="confirmName" class="font-bold text-gray-800"></p>
                    <p id="confirmParty" class="text-sm text-blue-900"></p>
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="button" id="cancelModalBtn" class="flex-1 bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl hover:bg-gray-300">{{ __t('cancel_button') }}</button>
                <button type="button" id="confirmModalBtn" class="flex-1 bg-green-600 text-white font-bold py-3 rounded-xl hover:bg-green-700">{{ __t('confirm_button') }}</button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
(function() {
    var cards = document.querySelectorAll('.candidate-card');
    var confirmBtn = document.getElementById('confirmBtn');
    var confirmModal = document.getElementById('confirmModal');
    var confirmName = document.getElementById('confirmName');
    var confirmParty = document.getElementById('confirmParty');
    var confirmPhoto = document.getElementById('confirmPhoto');
    var modalConfirmBtn = document.getElementById('confirmModalBtn');
    var modalCancelBtn = document.getElementById('cancelModalBtn');
    var voteForm = document.getElementById('voteForm');
    var selectedCandidate = null;

    cards.forEach(function(card) {
        card.addEventListener('click', function() {
            cards.forEach(function(c) {
                c.querySelector('.candidate-radio').classList.remove('border-green-500');
                c.querySelector('.candidate-dot').classList.add('hidden');
            });
            var radio = card.querySelector('input[type="radio"]');
            radio.checked = true;
            card.querySelector('.candidate-radio').classList.add('border-green-500');
            card.querySelector('.candidate-dot').classList.remove('hidden');
            confirmBtn.disabled = false;

            selectedCandidate = {
                name: card.querySelector('h4').textContent.trim(),
                party: card.querySelector('.text-blue-900') ? card.querySelector('.text-blue-900').textContent.trim() : '',
                photo: card.querySelector('img') ? card.querySelector('img').src : null,
            };
        });
    });

    confirmBtn.addEventListener('click', function() {
        if (!selectedCandidate) return;
        confirmName.textContent = selectedCandidate.name;
        confirmParty.textContent = selectedCandidate.party;
        if (selectedCandidate.photo) {
            confirmPhoto.innerHTML = '<img src="' + selectedCandidate.photo + '" class="h-14 w-14 rounded-full object-cover">';
        } else {
            confirmPhoto.innerHTML = '<div class="h-14 w-14 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xl">' + selectedCandidate.name.charAt(0) + '</div>';
        }
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
    });

    modalConfirmBtn.addEventListener('click', function() {
        voteForm.submit();
    });

    modalCancelBtn.addEventListener('click', function() {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
    });

    confirmModal.addEventListener('click', function(e) {
        if (e.target === confirmModal) {
            confirmModal.classList.add('hidden');
            confirmModal.classList.remove('flex');
        }
    });
})();
</script>
<style>
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endsection