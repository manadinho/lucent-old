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
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-black-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('teams.index') }}" class="text-black-700 hover:text-black-900 ml-1 md:ml-2 text-sm font-medium">{{ __("Teams")}}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-black-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-black-400 ml-1 md:ml-2 text-sm font-medium">{{ $team->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="alpine()">
            <div>
                @if($isTeamOwner)
                    <x-primary-button class="ml-3" @click=" open = true">
                            {{ __('Create Project') }}
                    </x-primary-button>
                @endif
            
                <!-- Blurred background -->
                <div  x-show="open" @click.away="open = false" class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur">
                    <!-- Modal content with custom width -->
                    <div class="bg-white p-8 rounded shadow-lg w-96"> <!-- Adjust the width here -->
                    <h2 class="text-xl font-bold mb-4">{{__('Create Project')}}</h2>
                    
                    <form method="POST" action="{{ route('projects.create') }}">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $team->id }}">
                        <input type="hidden" name="id" x-model="id">

                        <!-- MEMBER EMAIL -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" x-model="name" type="text" name="name" value='' required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="Select Environment" :value="__('Select Environment')" />
                            <x-select-tag x-model="environment" name="environment" label="Select Environment">
                                <option >{{__('Select Environment')}}</option>
                                <option value="production">{{__('Production')}}</option>
                                <option value="staging">{{__('Staging')}}</option>
                                <option value="local">{{__('Local')}}</option>
                            </x-select-tag>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
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
                        <th class="px-4 py-3 ">{{__('Environment')}}</th>
                        <th class="px-4 py-3 "></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700">
                        @forelse($projects as $project)
                            <tr class="py-10 border-b border-gray-200 hover:bg-gray-100">
                                <td class="px-4 py-4">{{ $project->name }}</td>
                                    
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-md bg-gray-500 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-green-600/20">
                                        {{ $project->environment }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if($isTeamOwner)
                                        <a onclick="confirmBefore(event, this)" href="{{ route('projects.delete', $project->id) }}" class="inline-flex float-right items-center rounded-md bg-gray-500 hover:bg-gray-800 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-red-600/10">
                                            &#9759; {{ __('Delete') }}
                                        </a>
                                    @endif
                                    @if(isTeamOwner($team->id))
                                        <a @click="openEditModal('{{ $project }}')" href="#" class="inline-flex mr-2 float-right items-center rounded-md bg-gray-500 hover:bg-gray-800 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-red-600/10">
                                            &#9759; {{ __('Edit') }}
                                        </a>
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
            id: null,
            name: '', 
            environment: '', 
            
            close: function() { 
                this.open = false; 
                this.id = null; 
                this.name = ''; 
                this.environment = '';
            },
            openEditModal: function(_project) {
                const project = JSON.parse(_project);
                this.id = project.id;
                this.name = project.name;
                this.environment = project.environment;
                this.open = true
            }
        }
    }
</script>
