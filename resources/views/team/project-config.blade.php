<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configurations') }} ({{ $project->name }})
        </h2>
    </x-slot>

    <div class="mb-4 text-sm text-gray-600 max-w-7xl mx-auto sm:px-6 lg:px-4">
        <nav class="flex bg-gray-800 text-white py-3 px-5 rounded-lg mt-4" aria-label="Breadcrumb">
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
                        <span class="text-black-400 ml-1 md:ml-2 text-sm font-medium">{{ __('Configurations') }}</span>
                    </div>
                </li>
                </ol>
            </nav>
        </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="alpine()">
            <div>
                <x-bladewind.tab-group name="staff-loans">

                    <x-slot name="headings">

                        <x-bladewind.tab-heading name="configurations" active="true" label="{{ __('Configurations')}}" />
                        <x-bladewind.tab-heading name="openai_key" label="{{ __('Open AI Key')}}" />
                        <x-bladewind.tab-heading name="gemini_key" label="{{ __('Gemini Key')}}" />
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
                                        @if(canDo($project->team_id, auth()->id(), 'can_generate_key'))
                                            <a onclick="confirmBefore(event, this, 'Are you sure to regenrate key of {{$project->name}}', 'Generate')" href="{{ route('projects.key.generate', $project->id) }}">
                                                <x-bladewind.icon name="arrow-path" />
                                            </a>
                                        @endif    
                                    </div>
                                </div>
                            </x-bladewind.card>
                        </x-bladewind.tab-content>
                    </x-bladewind.tab-body>

                    <x-bladewind.tab-body>
                        <x-bladewind.tab-content name="openai_key">
                            <x-bladewind.card reduce_padding="true">
                                <div class="flex items-center">
                                    <div>
                                        <x-bladewind.icon name="key" />
                                        <b>{{ __('OPEN AI KEY')}}</b>
                                    </div>
                                    <div class="grow pl-4 pt-1">
                                        <x-text-input class="block" type="text" id="openai_key_field"  required autofocus placeholder="KEY" value="{{ $configurations->where('key', 'openai_key')->first()->values['key'] ?? '' }}" style="width: 100%;" />
                                    </div>
                                </div>
                                <x-primary-button @click.prevent="saveOpeaiKey()">
                                    {{ __('Submit') }}
                                </x-primary-button>
                            </x-bladewind.card>
                        </x-bladewind.tab-content>
                    </x-bladewind.tab-body>

                    <x-bladewind.tab-body>
                        <x-bladewind.tab-content name="gemini_key">
                            <x-bladewind.card reduce_padding="true">
                                <div class="flex items-center">
                                    <div>
                                        <x-bladewind.icon name="key" />
                                        <b>{{ __('GEMINI KEY')}}</b>
                                    </div>
                                    <div class="grow pl-4 pt-1">
                                        <x-text-input class="block" type="text" id="gemini_key_field"  required autofocus placeholder="KEY" value="{{ $configurations->where('key', 'gemini_key')->first()->values['key'] ?? '' }}" style="width: 100%;" />
                                    </div>
                                </div>
                                <x-primary-button @click.prevent="saveGeminiKey()">
                                    {{ __('Submit') }}
                                </x-primary-button>
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

            },

            saveOpeaiKey: function() {
                const key = document.getElementById('openai_key_field').value;
                
                fetch('{{route("projects.store.openaikey")}}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: '{{$project->id}}',
                            key,
                            _token: '{{ csrf_token() }}'
                        }),
                    }
                ).then(async (_res) => {
                    const res = await _res.json();
                    if(res.success) {
                        new Notify ({
                            title: "Success",
                            text: "OpenAI key updated",
                            status: "success",
                            autoclose: true,
                        })
                    }
                }).catch((error) => {
                    new Notify ({
                        title: "Error",
                        text: error.message,
                        status: "error",
                        autoclose: true,
                    })
                });
            },

            saveGeminiKey: function() {
                const key = document.getElementById('gemini_key_field').value;

                fetch('{{route("projects.store.geminikey")}}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: '{{$project->id}}',
                            key,
                            _token: '{{ csrf_token() }}'
                        }),
                    }
                ).then(async (_res) => {
                    const res = await _res.json();
                    if(res.success) {
                        new Notify ({
                            title: "Success",
                            text: "Gemini key updated",
                            status: "success",
                            autoclose: true,
                        })
                    }
                }).catch((error) => {
                    new Notify ({
                        title: "Error",
                        text: error.message,
                        status: "error",
                        autoclose: true,
                    })
                });
            }
        }
    }
</script>
