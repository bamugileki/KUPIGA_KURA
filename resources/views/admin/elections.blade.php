@extends('layouts.admin')
@section('title', __t('manage_elections'))
@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-900">{{ __t('manage_elections') }}</h2>
    <a href="{{ route('admin.elections.create') }}" class="btn-anim bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">{{ __t('create_election') }}</a>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left py-3 px-4">{{ __t('election_title') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('election_type') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('status') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('start_time') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('end_time') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('objections') }}</th>
                    <th class="text-left py-3 px-4">{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($elections as $election)
                <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</td>
                    <td class="py-3 px-4">{{ __t($election->election_type) }}</td>
                    <td class="py-3 px-4">
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'nomination_open' => 'bg-yellow-100 text-yellow-800',
                                'published' => 'bg-blue-100 text-blue-800',
                                'campaign_period' => 'bg-purple-100 text-purple-800',
                                'active' => 'bg-green-100 text-green-800',
                                'closed' => 'bg-red-100 text-red-800',
                                'objection_period' => 'bg-orange-100 text-orange-800',
                                'returned' => 'bg-pink-100 text-pink-800',
                            ];
                            $color = $statusColors[$election->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="badge-anim text-xs px-2 py-1 rounded {{ $color }}">{{ __t($election->status) }}</span>
                    </td>
                    <td class="py-3 px-4">{{ $election->start_time->format('Y-m-d H:i') }}</td>
                    <td class="py-3 px-4">{{ $election->end_time->format('Y-m-d H:i') }}</td>
                    <td class="py-3 px-4 text-xs">
                        @if($election->objection_deadline)
                            <span class="block">{{ $election->totalObjections() }} / {{ $election->totalVoters() }}</span>
                            @if($election->status === 'objection_period' || $election->status === 'closed')
                                <span class="block text-gray-500">{{ __t('deadline') }}: {{ $election->objection_deadline->format('Y-m-d') }}</span>
                            @endif
                            @if($election->objection_triggered)
                                <span class="block text-pink-600 font-bold">{{ __t('threshold_reached') }}</span>
                            @endif
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 whitespace-nowrap">
                        @if($election->status === 'draft')
                            <a href="{{ route('admin.elections.edit', $election->id) }}" class="btn-anim bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700">{{ __t('edit_election') }}</a>
                            <a href="{{ route('admin.elections.transition', [$election->id, 'nomination_open']) }}" class="btn-anim bg-yellow-600 text-white px-2 py-1 rounded text-xs hover:bg-yellow-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('open_nominations') }}</a>
                        @endif
                        @if($election->status === 'nomination_open')
                            <a href="{{ route('admin.elections.transition', [$election->id, 'published']) }}" class="btn-anim bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('publish_candidates') }}</a>
                        @endif
                        @if($election->status === 'published')
                            <a href="{{ route('admin.elections.transition', [$election->id, 'campaign_period']) }}" class="btn-anim bg-purple-600 text-white px-2 py-1 rounded text-xs hover:bg-purple-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('start_campaign') }}</a>
                        @endif
                        @if($election->status === 'campaign_period')
                            <a href="{{ route('admin.elections.transition', [$election->id, 'active']) }}" class="btn-anim bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('open_voting') }}</a>
                        @endif
                        @if($election->status === 'active')
                            <a href="{{ route('admin.elections.transition', [$election->id, 'closed']) }}" class="btn-anim bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700" onclick="return confirm('{{ __t('close_voting_confirm') }}')">{{ __t('close_voting') }}</a>
                        @endif
                        @if(in_array($election->status, ['active', 'closed', 'objection_period']))
                            <a href="{{ route('admin.elections.generate_results', $election->id) }}" class="btn-anim bg-indigo-600 text-white px-2 py-1 rounded text-xs hover:bg-indigo-700" onclick="return confirm('{{ __t('generate_results_confirm') }}')">{{ __t('generate_results') }}</a>
                        @endif
                        @if($election->status === 'closed' || $election->status === 'objection_period')
                            <a href="{{ route('admin.elections.transition', [$election->id, 'objection_period']) }}" class="btn-anim bg-orange-600 text-white px-2 py-1 rounded text-xs hover:bg-orange-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('open_objections') }}</a>
                        @endif
                        @if($election->status === 'returned')
                            <a href="{{ route('admin.elections.transition', [$election->id, 'draft']) }}" class="btn-anim bg-purple-600 text-white px-2 py-1 rounded text-xs hover:bg-purple-700" onclick="return confirm('Are you sure? This will reset the election for a new round of voting.')">{{ __t('reopen_election') }}</a>
                        @endif
                        @if(!in_array($election->status, ['active', 'objection_period']))
                            <a href="{{ route('admin.elections.delete', $election->id) }}" class="btn-anim bg-red-700 text-white px-2 py-1 rounded text-xs hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this election and all its data? This cannot be undone.')">{{ __t('delete') }}</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
