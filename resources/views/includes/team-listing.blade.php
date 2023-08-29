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
                            <td class="px-4 py-2 text-right">
                                    <div class="relative inline-flex group">
                                        <a href="{{ route('teams.members',$team->id) }}" class="inline-flex float-right mr-2 items-center px-2 py-1">
                                            <x-bladewind.icon name="users" />
                                        </a>
                                        <div class="bg-gray-800 text-white p-2 rounded-lg absolute top-full left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            {{ __('Members')}}
                                        </div>
                                    </div>
                                    <div class="relative inline-flex group">
                                        <a href="{{ route('teams.projects',$team->id) }}" class="inline-flex float-right mr-2 items-center px-2 py-1">
                                            <x-bladewind.icon name="rectangle-stack" />
                                        </a>
                                        <div class="bg-gray-800 text-white p-2 rounded-lg absolute top-full left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            {{ __('Projects')}}
                                        </div>
                                    </div>
                                    @if(canDo($team->id, auth()->id(), 'can_edit_team'))
                                        <div class="relative inline-flex group">
                                            <a @click="openEditModal('{{ $team }}')" href="#" class="inline-flex mr-2 float-right items-center px-2 py-1 ">
                                                <x-bladewind.icon name="pencil-square" />
                                            </a>
                                            <div class="bg-gray-800 text-white p-2 rounded-lg absolute top-full left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                {{ __('Edit')}}
                                            </div>
                                        </div>
                                    @endif
                                    @if(canDo($team->id, auth()->id(), 'can_delete_team'))
                                    <div class="relative inline-flex group">
                                        <a onclick="confirmBefore(event, this)" href="{{ route('teams.delete', $team->id) }}" class="inline-flex float-right items-center px-2 py-1 ">
                                            <x-bladewind.icon name="trash" />
                                        </a>
                                        <div class="bg-gray-800 text-white p-2 rounded-lg absolute top-full left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            {{ __('Delete')}}
                                        </div>
                                    </div>
                                @endif
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
