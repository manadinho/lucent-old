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
                            <span class="text-black-400 ml-1 md:ml-2 text-sm font-medium">{{ $teamName }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="{ open: false, email: '', role: '', close: function() { this.open = false; this.email = ''; this.role = ''; } }">
            <div>
                @if($isTeamOwner)
                    <x-primary-button class="ml-3" @click=" open = true">
                            {{ __('Add Member') }}
                    </x-primary-button>
                @endif
            
                <!-- Blurred background -->
                <div  x-show="open" @click.away="open = false" class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur">
                    <!-- Modal content with custom width -->
                    <div class="bg-white p-8 rounded shadow-lg w-96"> <!-- Adjust the width here -->
                    <h2 class="text-xl font-bold mb-4">Add Member</h2>
                    
                    <form method="POST" action="{{ route('members.add') }}">
                        @csrf

                        <!-- MEMBER EMAIL -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" x-model="email" type="email" name="email" value='' required autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="Select a Role" :value="__('Select a Role')" />
                            <x-select-tag x-model="role" name="role" label="Select a Role">
                                <option >Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </x-select-tag>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-3">
                                {{ __('Create') }}
                            </x-primary-button>
                        </div>
                        <input type="hidden" name='teamId' value={{$members[0]->pivot->team_id}} >
                    </form>
                    <button @click="close()" class="mt-4 bg-gray-500 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded modal-cancel-btn">X</button>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <x-bladewind.table>
                <x-slot name="header">
                    <th>{{__('User')}}</th>
                    <th>{{__('Role')}}</th>
                    <th></th>
                </x-slot>
                    @forelse($members as $member)
                        <tr>
                            <td>
                            <x-bladewind.list-item>
                                <x-bladewind.avatar image="{{ asset('images/avatar.jpg') }}" size="small" />
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">
                                            {{$member->name ?? 'N/A'}} {{ $member->name ? '' : '(User Not Joined Yet)' }}
                                        </div>
                                        <div class="text-sm text-slate-500 truncate">
                                        {{ $member->email }}
                                        </div>
                                    </div>
                                </x-bladewind.list-item>
                            </td>
                            <td>
                                <x-bladewind.tag label="{{ ucwords($member->pivot->role) }}" color="gray" />

                            </td>
                            <td>
                                @if (($isTeamOwner && $member->pivot->user_id != auth()->id()) || (!$isTeamOwner && $member->pivot->user_id == auth()->id()))  
                                    <a onclick="confirmBefore(event, this)" href="{{ route('members.remove',['user_id'=> $member->pivot->user_id,'team_id'=> $member->pivot->team_id]) }}" class="inline-flex float-right items-center  px-2 py-1">
                                        <x-bladewind.icon name="trash" />
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
            </x-bladewind.table>
            </div>

        </div>
    </div>
</x-app-layout>
