<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Error Details') }} ({{ $project->name }})
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
                        <a href="{{ route('projects.exceptions.index', $project->id) }}" class="text-black-700 hover:text-black-900 ml-1 md:ml-2 text-sm font-medium">{{ __("Errors")}}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-black-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-black-400 ml-1 md:ml-2 text-sm font-medium">{{ __('Error Detail') }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="alpine()">
            <div class="p-2 sm:p-4 bg-gray-50 shadow sm:rounded-lg">
                <div class="flex items-center content-center p-5 bg-red-50 rounded-lg">
                    <button class=" back-button py-6 px-4 rounded" onclick="history.back()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                        </svg> Back

                    </button>
                    <div class="detail-header rounded p-5 grow">
                        <label class=" font-bold text-2xlrelative text-[10px] uppercase px-[12px] leading-8 tracking-widest whitespace-nowrap inline-block rounded-md mb-3 bg-gray-200 text-gray-500 " style="font-size: 15px;">
                        {{ $log->name }}
                        </label>
                        <p class="tracking-wider text-1xl text-gray-800/90 mb-1 label font-bold">{{ $log->message }}</p>
                    </div>
                    <div>
                        <span class="flex">
                            @if(optional($log->detail->app)->php_version)
                                <img src="{{ asset('images/php-icon.svg') }}" alt="">
                                <span class="ml-1 text-sm">{{ $log->detail->app->php_version }}</span>
                            @endif
                            @if(optional($log->detail->app)->laravel_version)
                                <img src="{{ asset('images/laravel.svg') }}" class="ml-5" alt="" width="15">
                                <span class="ml-1 text-sm">{{ $log->detail->app->laravel_version }}</span>
                            @endif
                            @if(optional($log->detail->app)->app_environment)
                                <img src="{{ asset('images/settings.svg') }}" class="ml-5" alt="" width="15">
                                <span class="ml-1 text-sm">{{ $log->detail->app->app_environment }}</span>
                            @endif
                            @if(optional($log->detail->app)->laravel_locale)
                                <img src="{{ asset('images/globe.svg') }}" class="ml-5" alt="" width="15">
                                <span class="ml-1 text-sm">{{ $log->detail->app->laravel_locale }}</span>
                            @endif
                        </span>
                        <span class="flex">
                            
                        </span>
                    </div>
                </div>


                <div class=" grid grid-cols-4">
                    <div class=" detail-left-section rounded border-zinc-600 mt-2 p-5" style="height: 100vh; overflow-y: scroll;">
                        @forelse($log->traces as $trace)
                            <div class="cursor-pointer detail-left-section-card bg-[#eff0f3] p-2 mt-1 {{ $loop->iteration == 1 ? 'active-trace' : ''}}" onclick="drawCode(this, '{{ $loop->iteration -1}}', '{{ $trace->line }}')" style="overflow-wrap: break-word; min-height: 84px">
                                <p class="text-sm ">{{ $trace->file }}: {{ $trace->line }}</p>
                                <br>
                                <p class=" text-sm">{{ $trace->class ?? '' }}</p>
                            </div>

                        @empty
                            <div class=" bg-[#eff0f3] p-2 mt-1">
                                <p class=" text-[#2a2a2a] text-sm">No record found</p>
                            </div>
                        @endforelse
                    </div>
                    <div class=" detail-right-section col-span-3 rounded  mt-2 p-5">

                        <div class="relative w-full flex flex-col border overflow-y-scroll">
                            <div class="flex-none border-b border-slate-500/30">
                                <div class="flex items-center h-8 space-x-1.5 px-3">
                                    <div class="w-3.5 h-3.5 bg-red-500 rounded-full"></div>
                                    <div class="w-3.5 h-3.5 bg-orange-500 rounded-full"></div>
                                    <div class="w-3.5 h-3.5 bg-green-500 rounded-full"></div>
                                    <p class="tracking-wider text-sm text-gray-900/90 mb-1 label" id="file-name"></p>
                                </div>
                            </div>
                            <pre class="text-sm mt-10" id="codeArea" style="color: #292e3c; margin-left: -100px">
                            </pre>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        @if($log->detail->request && !empty(get_object_vars($log->detail->request)))
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mt-5">
                <div class="bg-white p-6 shadow rounded-lg">
                    <div class="font-bold text-xl mb-2">REQUEST</div>
                    <div class="text-blue-600 mb-4">{{ optional($log->detail->request)->url }} <span class="text-green-500">{{ optional($log->detail->request)->method }}</span></div>
                    @if(optional($log->detail->request)->headers)
                        <div class="group relative font-mono bg-gray-100 p-4 rounded mb-4" style="overflow-x: auto;">
                            <button onclick="copyToClipboard(this)" class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 bg-white p-1 rounded-full shadow">
                                <x-bladewind.icon name="document-duplicate" />
                            </button>
                            <code class="text-sm">
                                curl "{{ optional($log->detail->request)->url }}" \<br>
                                -X {{ optional($log->detail->request)->method }} \<br>
                                @forelse(json_decode(json_encode(json_decode(optional($log->detail->request)->headers)), true) as $key => $headerArr)
                                    -H "{{ $key }}: {{ $headerArr[0] }}" \<br>
                                @empty
                                @endforelse
                                @forelse(optional($log->detail->request)->body as $key => $value)
                                    -F "{{ $key }}={{ $value }}"
                                @empty
                                @endforelse
                            </code>
                        </div>
                    @endif
                    
                    @if(optional($log->detail->request)->headers)
                        <div class="mb-4">
                            <div class="font-bold text-lg mb-2 text-red-500">Browser</div>
                            <div class="group relative text-gray-700 font-mono bg-gray-100 p-4 rounded mb-4" style="overflow-x:auto">
                                <button onclick="copyToClipboard(this)" class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 bg-white p-1 rounded-full shadow">
                                    <x-bladewind.icon name="document-duplicate" />
                                </button>
                                <code class="text-sm">
                                    {{ json_decode(json_encode(json_decode(optional($log->detail->request)->headers)), true)['user-agent'][0] }}
                                </code>
                            </div>
                        </div>
                    @endif
                    
                    @if(optional($log->detail->request)->headers)
                        <div class="mb-4">
                            <div class="font-bold text-lg mb-2 text-red-500">Headers</div>
                            @forelse(json_decode(json_encode(json_decode(optional($log->detail->request)->headers)), true) as $key => $headerArr)
                                <div class="w-full flex">
                                    <div class="text-gray-700 flex items-center" style="width:20%">
                                        {{$key}}
                                    </div>
                                    <div class="group relative text-gray-700 font-mono bg-gray-100 p-4 rounded mb-4" style="width:80%; overflow-x:auto">
                                        <button onclick="copyToClipboard(this)" class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 bg-white p-1 rounded-full shadow">
                                            <x-bladewind.icon name="document-duplicate" />
                                        </button>
                                        <code class="text-sm">
                                            {{ $headerArr[0] }}
                                        </code>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    @endif

                    @if(optional($log->detail->request)->body)
                        <div class="mb-4">
                            <div class="font-bold text-lg mb-2 text-red-500">Body</div>
                            <div class="group relative text-gray-700 font-mono bg-gray-100 p-4 rounded mb-4" style="overflow-x:auto">
                                <button onclick="copyToClipboard(this)" class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 bg-white p-1 rounded-full shadow">
                                    <x-bladewind.icon name="document-duplicate" />
                                </button>
                                <pre id="request-body" class="whitespace-pre-wrap overflow-x-auto text-xs">
                                    
                                </pre>
                            </div>
                        </div>
                    @endif    
                </div>
            </div>
        @endif

        @if($log->detail->user && !empty(get_object_vars($log->detail->user)))
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 mt-5">
                <div class="bg-white p-6 shadow rounded-lg">
                    <div class="font-bold text-xl mb-2">User</div>
                    <div class="group relative text-gray-700 font-mono bg-gray-100 p-4 rounded mb-4" style="overflow-x:auto">
                        <button onclick="copyToClipboard(this)" class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 bg-white p-1 rounded-full shadow">
                            <x-bladewind.icon name="document-duplicate" />
                        </button>
                        <pre id="request-user" class="whitespace-pre-wrap overflow-x-auto text-xs">
                            
                        </pre>
                    </div>
                </div>
            </div>

        @endif
    </div>
    @include('exceptions.partials.detail-partial')
</x-app-layout>

<script src="{{asset('js/chartjs.min.js')}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js" defer></script>
<script>
    function alpine() {
        return {
        }

    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jsonRequestBody = @json(optional($log->detail->request)->body) ? @json(optional($log->detail->request)->body):{} ;
        document.getElementById('request-body').textContent = JSON.stringify(jsonRequestBody, null, 2);

        const jsonRequestUser = @json($log->detail->user) ? @json($log->detail->user):{} ;
        document.getElementById('request-user').textContent = JSON.stringify(jsonRequestUser, null, 2);
    });
    function copyToClipboard(element) {
        if (!navigator.clipboard) {
            new Notify ({
                title: "Error",
                text: "Copying to clipboard failed. Clipboard API is not available in this browser or context.",
                status: "error",
                autoclose: true,
            })
            return;
        }
        debugger;
        var text = element.nextElementSibling.textContent;
        const originalButtonHtml = element.innerHTML;
        element.disabled = true;

        navigator.clipboard.writeText(text.trim()).then(function() {
            element.innerHTML = '<span class="text-sm text-green-500">Copied</span>';
            setTimeout(function() {
                element.innerHTML = originalButtonHtml;
                element.disabled = false;
            }, 3000);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
