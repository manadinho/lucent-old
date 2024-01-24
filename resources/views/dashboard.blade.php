<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                        @forelse($projects as $project)
                            <div class="project-card p-4 bg-white   rounded-lg shadow-2xl shadow-gray-500/20 transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                                <div>
                                    <h2 class="card-title whitespace-nowrap overflow-hidden text-ellipsis tracking-wider text-2xl text-gray-900/90 mb-1 label">{{ $project->name }} <br><small class="text-gray-400">({{ $project->environment}})</small></h2><br>
                                    <x-bladewind.tag label="Total Exceptions: {{ $project->exceptions_count }}" color="red" />
                                    <x-bladewind.tag label="Resolved Exceptions: {{ $project->resolved_exceptions_count }}" color="green" />
                                    <hr class="border-t border-gray-300 mb-4">
                                    <a href="{{ route('projects.exceptions.index', $project->id) }}" class="inline-flex mr-2 float-right items-center">
                                        <x-bladewind.button type="secondary" size="tiny" class="ml-3 mt-1" >
                                            <span class="tooltiptext">{{ __('Errors')}}</span>
                                            <x-bladewind.icon name="bug-ant" />
                                        </x-bladewind.button>
                                    </a>
                                    <a href="{{ route('projects.configurations', $project->id) }}" class="inline-flex mr-2 float-right items-center">
                                        <x-bladewind.button type="secondary" size="tiny" class="ml-3 mt-1" >
                                            <span class="tooltiptext">{{ __('Configurations')}}</span>
                                            <x-bladewind.icon name="cog" />
                                        </x-bladewind.button>
                                    </a>
                                </div>
                            </div>
                        @empty

                        @endforelse
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
