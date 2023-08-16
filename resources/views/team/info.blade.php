<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teams') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div x-data="{ open: false, email: '', role: '', close: function() { this.open = false; this.email = ''; this.role = ''; } }">
                <div class="mb-4 text-sm text-gray-600">
                    <x-back-button></x-back-button>
                    <b>Home/Teams/{{$teamName}}</b>
                </div>
                <x-primary-button class="ml-3" @click=" open = true">
                        {{ __('Add Member') }}
                </x-primary-button>
            
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
                        <input type="hidden" name='teamId' value={{$members[0]->pivot->team_id}}>
                    </form>
                    <button @click="close()" class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Close</button>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <table class="w-full border border-collapse table-auto">
                    <thead class="">
                        <tr class="text-base font-bold text-left bg-gray-50">
                        <th class="px-4 py-3 border-b-2 border-blue-500">User</th>
                        <th class="px-4 py-3 border-b-2 border-blue-500">User</th>
                        <th class="px-4 py-3 border-b-2 border-green-500">Role</th>
                        <th class="px-4 py-3 border-b-2 border-red-500"></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700">
                        @forelse($members as $member)

                            <tr class="py-10 border-b border-gray-200 hover:bg-gray-100">
                                <td class="flex flex-row items-center px-4 py-4">
                                    <div class="flex w-10 h-10 mr-4">
                                        <a href="#" class="relative block">
                                        <img alt="profil" src="https://images.unsplash.com/photo-1560329072-17f59dcd30a4?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8d29tZW4lMjBmYWNlfGVufDB8MnwwfHw%3D&auto=format&fit=crop&w=500&q=60" class="object-cover w-10 h-10 mx-auto rounded-md" />
                                        </a>
                                    </div>
                                    <div class="flex-1 pl-1">
                                        <div class="font-medium">{{$member->email}} ({{ $member->name ?? 'N/A' }})</div>
                                        <div class="text-sm text-red-600">
                                            {{ $member->name ? '' : 'User Not Joined Yet' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    
                                    {{$member->email}} ({{$member->name ?? 'N/A'}})
                                
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                    {{ ucwords($member->pivot->role) }}
                                    </span>

                                </td>
                                <td class="px-4 py-4" >
                                    @if ($member->pivot->user_id == auth()->id() && $member->pivot->role === 'owner')  
                                        <a href="{{ route('members.remove',['user_id'=> $member->pivot->user_id,'team_id'=> $member->pivot->team_id]) }}" class="inline-flex float-right items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M17 5V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V5H4C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H5V18C5 19.6569 6.34315 21 8 21H16C17.6569 21 19 19.6569 19 18V7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H17ZM15 4H9V5H15V4ZM17 7H7V18C7 18.5523 7.44772 19 8 19H16C16.5523 19 17 18.5523 17 18V7Z" fill="currentColor" /><path d="M9 9H11V17H9V9Z" fill="currentColor" /><path d="M13 9H15V17H13V9Z" fill="currentColor" /></svg>
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
                <!-- <table class="w-full border border-collapse table-auto">
                    <thead class="">
                        <tr class="text-base font-bold text-left bg-gray-50">
                        <th class="px-4 py-3 border-b-2 border-blue-500">Customer</th>
                        <th class="px-4 py-3 border-b-2 border-green-500">Contact</th>
                        <th class="px-4 py-3 border-b-2 border-red-500">Order No</th>
                        <th class="px-4 py-3 text-center border-b-2 border-yellow-500 sm:text-left">Purchased On</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700">
                        <tr class="py-10 border-b border-gray-200 hover:bg-gray-100">
                        <td class="flex flex-row items-center px-4 py-4">
                            <div class="flex w-10 h-10 mr-4">
                                <a href="#" class="relative block">
                                <img alt="profil" src="https://images.unsplash.com/photo-1560329072-17f59dcd30a4?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8d29tZW4lMjBmYWNlfGVufDB8MnwwfHw%3D&auto=format&fit=crop&w=500&q=60" class="object-cover w-10 h-10 mx-auto rounded-md" />
                                </a>
                            </div>
                            <div class="flex-1 pl-1">
                                <div class="font-medium dark:text-white">Barbara Curtis</div>
                                <div class="text-sm text-blue-600 dark:text-gray-200">
                                Account Deactivated
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            480-570-3413
                        </td>
                        <td class="px-4 py-4">
                            MX-8523537435
                        </td>
                        <td class="px-4 py-4">
                            Just Now
                        </td>
                        </tr>
                        <tr class="py-10 border-b border-gray-200 hover:bg-gray-100">
                        <td class="flex flex-row items-center px-4 py-4">
                            <div class="flex w-10 h-10 mr-4">
                                <a href="#" class="relative block">
                                <img alt="profil" src="https://images.unsplash.com/photo-1571395443367-8fbb3962e48f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MjF8fG1lbiUyMGZhY2V8ZW58MHwwfDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60" class="object-cover w-10 h-10 mx-auto rounded-md" />
                                </a>
                            </div>
                            <div class="flex-1 pl-1">
                                <div class="font-medium dark:text-white">Charlie Hawkins</div>
                                <div class="text-sm text-green-600 dark:text-gray-200">
                                Email Verified
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            440-476-4873
                        </td>
                        <td class="px-4 py-4">
                            MX-9537537436
                        </td>
                        <td class="px-4 py-4">
                            Mar 04, 2018 11:37am
                        </td>
                        </tr>
                        <tr class="py-10 border-b border-gray-200 hover:bg-gray-100">
                        <td class="flex flex-row items-center px-4 py-4">
                            <div class="flex w-10 h-10 mr-4">
                                <a href="#" class="relative block">
                                <img alt="profil" src="https://images.unsplash.com/photo-1532170579297-281918c8ae72?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mjl8fGZlbWFsZXxlbnwwfDB8MHx8&auto=format&fit=crop&w=500&q=60" class="object-cover w-10 h-10 mx-auto rounded-md" />
                                </a>
                            </div>
                            <div class="flex-1 pl-1">
                                <div class="font-medium dark:text-white">Nina Bates</div>
                                <div class="text-sm text-yellow-600 dark:text-gray-200">
                                Payment On Hold
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            206-783-1890
                        </td>
                        <td class="px-4 py-4">
                            MX-7533567437
                        </td>
                        <td class="px-4 py-4">
                            Mar 13, 2018 9:41am
                        </td>
                        </tr>
                        <tr class="py-10 border-b border-gray-200 hover:bg-gray-100">
                        <td class="flex flex-row items-center px-4 py-4">
                            <div class="flex w-10 h-10 mr-4">
                                <a href="#" class="relative block">
                                <img alt="profil" src="https://images.unsplash.com/photo-1474176857210-7287d38d27c6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTB8fG1lbiUyMGZhY2V8ZW58MHwwfDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60" class="object-cover w-10 h-10 mx-auto rounded-md" />
                                </a>
                            </div>
                            <div class="flex-1 pl-1">
                                <div class="font-medium dark:text-white">Hester Richards</div>
                                <div class="text-sm text-green-600 dark:text-gray-200">
                                Email Verified
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            931-499-6252
                        </td>
                        <td class="px-4 py-4">
                            MX-5673467743
                        </td>
                        <td class="px-4 py-4">
                            Feb 21, 2018 8:34am
                        </td>
                        </tr>
                    </tbody>
                </table> -->
            </div>

        </div>
    </div>
</x-app-layout>
