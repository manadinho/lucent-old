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
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-black-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('teams.projects', $project->team_id) }}" class="text-black-700 hover:text-black-900 ml-1 md:ml-2 text-sm font-medium">{{ __("Projects")}}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-black-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-black-400 ml-1 md:ml-2 text-sm font-medium">{{ $project->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="alpine()">
            <div>
                <x-bladewind.tab-group name="staff-loans">

                    <x-slot name="headings">

                        <x-bladewind.tab-heading name="configurations" active="true" label="{{ __('Configurations')}}" />
                        <x-bladewind.tab-heading name="notifications" label="{{ __('Notifications')}}" />
                        <x-bladewind.tab-heading name="visitor_access" label="{{ __('Visitor Acess')}}" />
                        <x-bladewind.tab-heading name="github" label="{{ __('GitHub')}}" />

                    </x-slot>

                    <x-bladewind.tab-body>
                        <x-bladewind.tab-content name="configurations" active="true">
                            <x-bladewind.card reduce_padding="true">
                                <div class="flex items-center">
                                    <div>
                                        <x-bladewind.icon name="key" />
                                    </div>
                                    <div class="grow pl-2 pt-1">
                                        <b>{{ __('KEY')}}</b>
                                        <div class="text-sm" id="lucent-key">LUCENT_KEY={{ $configurations->where('key', 'lucent_key')->first()->values['key'] }} <span class="cursor-pointer" @click='copy()'><x-bladewind.icon name="document-duplicate" /></span></div>
                                    </div>
                                    <div>
                                        <a onclick="confirmBefore(event, this, 'Are you sure to regenrate key of {{$project->name}}')" href="{{ route('projects.key.generate', $project->id) }}">
                                            <x-bladewind.icon name="arrow-path" />
                                        </a>
                                    </div>
                                </div>
                            </x-bladewind.card>
                        </x-bladewind.tab-content>
                    </x-bladewind.tab-body>

                    <x-bladewind.tab-body>
                        <x-bladewind.tab-content name="notifications">
                            <x-bladewind.card reduce_padding="true">
                                <div class="flex items-center">
                                    <!-- <div>
                                        <x-bladewind.icon name="envelope" />
                                    </div>
                                    <div class="grow pl-2 pt-1">
                                        <b>{{ __('EMAIL')}}</b>
                                        <x-text-input class="block" type="text" name="email" :value="$configurations->where('key', 'notifications')->first()->values['email']" required autofocus placeholder="Email" />
                                    </div> -->
                                    <h3>{{ __('Coming soon')}}</h3>
                                </div>
                            </x-bladewind.card>
                        </x-bladewind.tab-content>
                    </x-bladewind.tab-body>

                    <x-bladewind.tab-body>
                        <x-bladewind.tab-content name="visitor_access">
                            <x-bladewind.card reduce_padding="true">
                                <div class="flex items-center">
                                    <h3>{{ __('Coming soon')}}</h3>
                                </div>
                            </x-bladewind.card>
                        </x-bladewind.tab-content>
                    </x-bladewind.tab-body>

                    <x-bladewind.tab-body>
                        <x-bladewind.tab-content name="github">
                            <x-bladewind.card reduce_padding="true">
                                <div class="flex items-center">
                                    <h3>{{ __('Coming soon')}}</h3>
                                </div>
                            </x-bladewind.card>
                        </x-bladewind.tab-content>
                    </x-bladewind.tab-body>

                </x-bladewind.tab-group>
            </div>

        </div>
    </div>
</x-app-layout>

<script>
    function alpine() {
        return {
            copy:  function() {
                const textToCopy = document.getElementById('lucent-key').textContent;

                // Create a temporary textarea to hold the text
                const tempTextarea = document.createElement('textarea');
                tempTextarea.value = textToCopy.trim();
                document.body.appendChild(tempTextarea);

                // Select and copy the text from the textarea
                tempTextarea.select();
                document.execCommand('copy');

                // Remove the temporary textarea
                document.body.removeChild(tempTextarea);
                new Notify ({
                    title: "Copied",
                    text: "Lucent key copied",
                    status: "success",
                    autoclose: true,
                })

            }
        }
    }
</script>
