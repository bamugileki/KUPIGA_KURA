@extends('layouts.admin')
@section('title', __t('manage_elections'))
@section('subtitle', __t('manage_elections_subtitle'))
@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700">{{ __t('all_elections') }}</h3>
        <a href="{{ route('admin.elections.create') }}" class="btn bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
            <svg class="w-4 h-4 inline-block mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __t('create_election') }}
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr>
                    <th>{{ __t('election_title') }}</th>
                    <th>{{ __t('election_type') }}</th>
                    <th>{{ __t('status') }}</th>
                    <th>{{ __t('start_time') }}</th>
                    <th>{{ __t('end_time') }}</th>
                    <th>{{ __t('objections') }}</th>
                    <th>{{ __t('actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($elections as $election)
                <tr>
                    <td class="font-medium text-gray-900">{{ session('lang') == 'sw' ? $election->title_sw : $election->title_en }}</td>
                    <td>
                        @php $pos = \App\Models\Position::where('slug', $election->election_type)->first(); @endphp
                        <span class="badge bg-blue-50 text-blue-700">{{ session('lang') == 'sw' ? ($pos->name_sw ?? $election->election_type) : ($pos->name_en ?? $election->election_type) }}</span>
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'draft' => ['bg-gray-100', 'text-gray-700'],
                                'nomination_open' => ['bg-yellow-100', 'text-yellow-700'],
                                'published' => ['bg-blue-100', 'text-blue-700'],
                                'campaign_period' => ['bg-purple-100', 'text-purple-700'],
                                'active' => ['bg-green-100', 'text-green-700'],
                                'closed' => ['bg-red-100', 'text-red-700'],
                                'objection_period' => ['bg-orange-100', 'text-orange-700'],
                                'returned' => ['bg-pink-100', 'text-pink-700'],
                            ];
                            $color = $statusColors[$election->status] ?? ['bg-gray-100', 'text-gray-600'];
                            $pulse = $election->status === 'active' ? ' <span class="w-1.5 h-1.5 bg-green-500 rounded-full inline-block animate-pulse ml-1"></span>' : '';
                        @endphp
                        <span class="badge {{ $color[0] }} {{ $color[1] }}">{!! __t($election->status) . $pulse !!}</span>
                    </td>
                    <td class="text-gray-600 text-xs">{{ $election->start_time->format('Y-m-d H:i') }}</td>
                    <td class="text-gray-600 text-xs">{{ $election->end_time->format('Y-m-d H:i') }}</td>
                    <td class="text-xs">
                        @if($election->objection_deadline)
                            <span class="text-gray-700 font-medium">{{ $election->totalObjections() }}/{{ $election->totalVoters() }}</span>
                            @if($election->status === 'objection_period' || $election->status === 'closed')
                                <span class="block text-gray-400 text-10px">{{ __t('deadline') }}: {{ $election->objection_deadline->format('Y-m-d') }}</span>
                            @endif
                            @if($election->objection_triggered)
                                <span class="block text-pink-600 font-bold text-10px">{{ __t('threshold_reached') }}</span>
                            @endif
                        @else
                            <span class="text-gray-300">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex flex-wrap gap-1.5">
                            @if($election->status === 'draft')
                                <a href="{{ route('admin.elections.edit', $election->id) }}" class="btn bg-blue-600 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-700">{{ __t('edit_election') }}</a>
                                <a href="{{ route('admin.elections.transition', [$election->id, 'nomination_open']) }}" class="btn bg-yellow-600 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-yellow-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('open_nominations') }}</a>
                            @endif
                            @if($election->status === 'nomination_open')
                                <a href="{{ route('admin.elections.transition', [$election->id, 'published']) }}" class="btn bg-blue-600 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('publish_candidates') }}</a>
                            @endif
                            @if($election->status === 'published')
                                <a href="{{ route('admin.elections.transition', [$election->id, 'campaign_period']) }}" class="btn bg-purple-600 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-purple-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('start_campaign') }}</a>
                            @endif
                            @if($election->status === 'campaign_period')
                                <a href="{{ route('admin.elections.transition', [$election->id, 'active']) }}" class="btn bg-green-600 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-green-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('open_voting') }}</a>
                            @endif
                            @if($election->status === 'active')
                                <a href="{{ route('admin.elections.transition', [$election->id, 'closed']) }}" class="btn bg-red-600 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-red-700" onclick="return confirm('{{ __t('close_voting_confirm') }}')">{{ __t('close_voting') }}</a>
                            @endif
                            @if(in_array($election->status, ['active', 'closed', 'objection_period']))
                                <a href="{{ route('admin.elections.generate_results', $election->id) }}" class="btn bg-indigo-600 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-indigo-700" onclick="return confirm('{{ __t('generate_results_confirm') }}')">{{ __t('generate_results') }}</a>
                            @endif
                            @if(in_array($election->status, ['closed', 'objection_period']) && !$election->winner_declared)
                                <a href="{{ route('admin.elections.declare_winner', $election->id) }}" class="btn bg-yellow-600 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-yellow-700" onclick="return confirm('Declare the top candidate as winner?')">{{ __t('declare_winner') ?? 'Declare Winner' }}</a>
                            @endif
                            @if($election->winner_declared)
                                <span class="badge bg-yellow-100 text-yellow-800 font-semibold">
                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ __t('winner') }}
                                </span>
                                <a href="{{ route('admin.elections.revoke_winner', $election->id) }}" class="btn bg-red-100 text-red-700 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-red-200" onclick="return confirm('Revoke winner declaration?')">{{ __t('revoke') }}</a>
                            @endif
                            @if(in_array($election->status, ['closed', 'objection_period']))
                                <a href="{{ route('admin.elections.transition', [$election->id, 'objection_period']) }}" class="btn bg-orange-600 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-orange-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('open_objections') }}</a>
                            @endif
                            @if($election->status === 'returned')
                                <a href="{{ route('admin.elections.transition', [$election->id, 'draft']) }}" class="btn bg-purple-600 text-white px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-purple-700" onclick="return confirm('{{ __t('are_you_sure') }}')">{{ __t('reopen_election') }}</a>
                            @endif
                            @if(!in_array($election->status, ['active', 'objection_period']))
                                <a href="{{ route('admin.elections.delete', $election->id) }}" class="btn bg-red-50 text-red-600 px-2.5 py-1.5 rounded-lg text-xs font-medium hover:bg-red-100 border border-red-200" onclick="return confirm('{{ __t('are_you_sure_delete') }}')">{{ __t('delete') }}</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
