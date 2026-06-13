@extends('layouts.admin')
@section('title', __t('nomination_support'))
@section('subtitle', __t('nomination_support_subtitle'))
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">{{ __t('nomination_support_title') }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ $candidate->full_name }} — {{ $candidate->party_abbreviation }} ({{ __t($candidate->position) }})</p>
        </div>

        <div class="p-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">{{ __t('add_supporter') }}</h4>
            <form method="POST" action="{{ route('admin.candidates.nomination_support.add', $candidate->id) }}" class="grid md:grid-cols-2 gap-4 mb-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('supporter_name') }} *</label>
                    <input type="text" name="supporter_name" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('supporter_nida') }}</label>
                    <input type="text" name="supporter_nida">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('region_label') }} *</label>
                    <select name="region" required>
                        <option value="">{{ __t('select') }}</option>
                        @foreach($regions as $r)
                        <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('notes') }}</label>
                    <input type="text" name="notes">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="btn bg-blue-600 text-white font-medium py-2 px-6 rounded-lg text-sm hover:bg-blue-700">{{ __t('add_supporter') }}</button>
                </div>
            </form>

            <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __t('nomination_support') }}</h4>
            <div class="overflow-x-auto table-wrap border border-gray-100 rounded-lg">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th>{{ __t('region_label') }}</th>
                            <th>{{ __t('supporter_name') }}</th>
                            <th>{{ __t('supporter_nida') }}</th>
                            <th>{{ __t('notes') }}</th>
                            <th>{{ __t('created_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($candidate->nominationSupport as $ns)
                        <tr>
                            <td class="font-medium text-gray-900">{{ $ns->region }}</td>
                            <td class="text-gray-700">{{ $ns->supporter_name }}</td>
                            <td class="text-gray-500 text-xs">{{ $ns->supporter_nida ?? '-' }}</td>
                            <td class="text-gray-500 text-xs">{{ $ns->notes ?? '-' }}</td>
                            <td class="text-gray-500 text-xs">{{ $ns->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-8 text-gray-400">{{ __t('no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @php $regionCount = $candidate->nominationSupport->groupBy('region')->count(); @endphp
            <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500">
                <span>{{ __t('total_regions') }}: <strong class="text-gray-900">{{ $regionCount }}</strong></span>
                <span class="text-gray-300">|</span>
                <span>{{ __t('total_supporters') }}: <strong class="text-gray-900">{{ $candidate->nominationSupport->count() }}</strong></span>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.candidates') }}" class="inline-flex items-center mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        {{ __t('back') }} {{ __t('candidates') }}
    </a>
</div>
@endsection
