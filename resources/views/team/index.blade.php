<x-app-layout>
    <x-slot name="header">
        <div class="mb-4 text-sm text-gray-600">
            <nav class="flex bg-black-50 text-black-700 py-3 px-5 rounded-lg mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-black-700 hover:text-black-900 inline-flex items-center">
                            <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            {{ __('Dashboard')}}
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-black-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-black-400 ml-1 md:ml-2 text-sm font-medium">{{ __("Teams")}}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="alpine()">
            <div>
                <x-primary-button class="ml-3" @click="open = true">
                        {{ __('Create Team') }}
                </x-primary-button>

                <!-- Blurred background -->
                <div x-show="open" @click.away="open = false" class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur">
                    <!-- Modal content with custom width -->
                    <div class="bg-white p-8 rounded shadow-lg w-96"> <!-- Adjust the width here -->
                        <h2 class="text-xl font-bold mb-4">Create Team</h2>
                        <form method="POST" action="{{ route('teams.create') }}">
                            @csrf
                            <input type="hidden" name="id" x-model="id">
                            
                            <!-- Team Name -->
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" x-model="name" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ml-3">
                                    {{ __('Save') }}
                                </x-primary-button>
                            </div>
                        </form>
                        <button @click="close()" class="mt-4 bg-gray-500 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded modal-cancel-btn">X</button>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <x-bladewind.table>
                    <x-slot name="header">
                        <th>{{__('Name')}}</th>
                        <th>{{__('Members')}}</th>
                        <th>{{__('Projects')}}</th>
                        <th>{{__('Actions')}}</th>
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
                                @if(isTeamOwner($team->id))
                                    <a onclick="confirmBefore(event, this)" href="{{ route('teams.delete', $team->id) }}" class="inline-flex float-right items-center px-2 py-1 ">
                                        <x-bladewind.icon name="trash" />
                                    </a>
                                @endif
                                @if(isTeamOwner($team->id))
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

        </div>
    </div>
</x-app-layout>

<script>
    function alpine() {
        return {
            open: false, 
            name: '',
            id: null,
            
            close: function() { 
                this.open = false;
                this.id = null;
                this.name = '';
            },
            
            openEditModal: function(_team) {
                const team = JSON.parse(_team);
                this.id = team.id;
                this.name = team.name;
                this.open = true
            }
        }
    }
</script>
