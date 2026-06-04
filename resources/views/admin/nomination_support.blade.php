@extends('layouts.admin')
@section('title', __t('nomination_support'))
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">{{ __t('nomination_support_title') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $candidate->full_name }} - {{ $candidate->party_abbreviation }} ({{ __t($candidate->position) }})</p>
        </div>

        <div class="p-6">
            <h3 class="font-semibold text-gray-700 mb-3">{{ __t('add_supporter') }}</h3>
            <form method="POST" action="{{ route('admin.candidates.nomination_support.add', $candidate->id) }}" class="grid md:grid-cols-2 gap-4 mb-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('supporter_name') }} *</label>
                    <input type="text" name="supporter_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('supporter_nida') }}</label>
                    <input type="text" name="supporter_nida" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('region_label') }} *</label>
                    <select name="region" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">{{ __t('select') }}</option>
                        @foreach($regions as $r)
                        <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __t('notes') }}</label>
                    <input type="text" name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="bg-blue-900 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-800 text-sm">{{ __t('add_supporter') }}</button>
                </div>
            </form>

            <h3 class="font-semibold text-gray-700 mb-3">{{ __t('nomination_support') }}</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left py-2 px-3">{{ __t('region_label') }}</th>
                            <th class="text-left py-2 px-3">{{ __t('supporter_name') }}</th>
                            <th class="text-left py-2 px-3">{{ __t('supporter_nida') }}</th>
                            <th class="text-left py-2 px-3">{{ __t('notes') }}</th>
                            <th class="text-left py-2 px-3">{{ __t('created_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($candidate->nominationSupport as $ns)
                        <tr class="border-t border-gray-100">
                            <td class="py-2 px-3 font-medium">{{ $ns->region }}</td>
                            <td class="py-2 px-3">{{ $ns->supporter_name }}</td>
                            <td class="py-2 px-3">{{ $ns->supporter_nida ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $ns->notes ?? '-' }}</td>
                            <td class="py-2 px-3 text-gray-500">{{ $ns->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-gray-500">{{ __t('no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @php
                $regionCount = $candidate->nominationSupport->groupBy('region')->count();
            @endphp
            <div class="mt-4 text-sm text-gray-600">
                {{ __t('total_regions') }}: <strong>{{ $regionCount }}</strong> |
                {{ __t('total_supporters') }}: <strong>{{ $candidate->nominationSupport->count() }}</strong>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.candidates') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">&larr; {{ __t('back') }} {{ __t('candidates') }}</a>
</div>
@endsection
