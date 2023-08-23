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
                <table class="w-full border border-collapse table-auto">
                    <thead class="">
                        <tr class="text-base font-bold text-left bg-gray-50">
                            <th class="px-4 py-3 ">{{__('Name')}}</th>
                            <th class="px-4 py-3 ">{{__('Members')}}</th>
                            <th class="px-4 py-3 ">{{__('Projects')}}</th>
                            <th class="px-4 py-3 "></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700">
                        @forelse($teams as $team)

                            <tr class="py-10 border-b border-gray-200 hover:bg-gray-100">
                                <td class="px-4 py-4">
                                    
                                    <h4><b>{{$team->name}}</b>
                                    <smal class="rounded-md bg-gray-200 px-2 text-gray-800 font-sm ml-2">
                                        @if($team->user_id === auth()->id())
                                            Owner
                                        @else
                                            Member
                                        @endif        
                                    </small>
                                
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-md bg-gray-500 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-green-600/20">
                                    {{ $team->users_count }}
                                    </span>

                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-md bg-gray-500 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-green-600/20">
                                    {{ $team->projects_count }}
                                    </span>

                                </td>
                                <td class="px-4 py-4">
                                    @if(isTeamOwner($team->id))
                                        <a onclick="confirmBefore(event, this)" href="{{ route('teams.delete', $team->id) }}" class="inline-flex float-right items-center rounded-md bg-gray-500 hover:bg-gray-800 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-red-600/10">
                                            &#9759; {{ __('Delete') }}
                                        </a>
                                    @endif
                                    @if(isTeamOwner($team->id))
                                        <a @click="openEditModal('{{ $team }}')" href="#" class="inline-flex mr-2 float-right items-center rounded-md bg-gray-500 hover:bg-gray-800 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-red-600/10">
                                            &#9759; {{ __('Edit') }}
                                        </a>
                                    @endif
                                    <a href="{{ route('teams.members',$team->id) }}" class="inline-flex float-right mr-2 items-center rounded-md bg-gray-500 hover:bg-gray-800 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-green-600/20">
                                        &#9759; {{ __('View Members') }}
                                    </a>
                                    <a href="{{ route('teams.projects',$team->id) }}" class="inline-flex float-right mr-2 items-center rounded-md bg-gray-500 hover:bg-gray-800 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-green-600/20">
                                        &#9759; {{ __('View Projects') }}
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
                    </tbody>
                </table>
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
