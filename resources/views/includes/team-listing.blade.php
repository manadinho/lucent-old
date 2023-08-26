<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-bladewind.table>
                    <x-slot name="header">
                        <th>{{__('Name')}}</th>
                        <th>{{__('Members')}}</th>
                        <th>{{__('Projects')}}</th>
                        <th></th>
                    </x-slot>
                    @forelse($teams as $team)
                        <tr>
                            <td>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-slate-900">
                                    {{$team->name}}
                                </div>
                                <div class="text-sm text-slate-500 truncate">
                                    @if($team->user_id === auth()->id())
                                        {{ __('Owner')}}
                                    @else
                                        {{ __('Member')}}
                                    @endif 
                                </div>
                            </div>
                            </td>
                            <td>
                                <x-bladewind.tag label="{{ $team->users_count }}" color="gray" />
                            </td>
                            <td>
                                <x-bladewind.tag label="{{ $team->projects_count }}" color="gray" />

                            </td>
                            <td>
                                @if(canDo($team->id, auth()->id(), 'can_delete_team'))
                                    <a onclick="confirmBefore(event, this)" href="{{ route('teams.delete', $team->id) }}" class="inline-flex float-right items-center px-2 py-1 ">
                                        <x-bladewind.icon name="trash" />
                                    </a>
                                @endif
                                @if(canDo($team->id, auth()->id(), 'can_edit_team'))
                                    <a @click="openEditModal('{{ $team }}')" href="#" class="inline-flex mr-2 float-right items-center px-2 py-1 ">
                                        <x-bladewind.icon name="pencil-square" />
                                    </a>
                                @endif
                                <a href="{{ route('teams.members',$team->id) }}" class="inline-flex float-right mr-2 items-center px-2 py-1">
                                    <x-bladewind.icon name="users" />
                                </a>
                                <a href="{{ route('teams.projects',$team->id) }}" class="inline-flex float-right mr-2 items-center px-2 py-1">
                                <x-bladewind.icon name="rectangle-stack" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <h3 class="text-center">
                                    No Data Found!
                                </h3>
                            </td>
                        </tr>

                    @endforelse
                </x-bladewind.table>
            </div>
