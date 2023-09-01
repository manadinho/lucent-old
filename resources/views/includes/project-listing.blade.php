<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <x-bladewind.table>
        <x-slot name="header">
            <th>{{__('Name')}}</th>
            <th>{{__('Environment')}}</th>
            <th>{{__('Total Exceptions')}}</th>
            <th></th>
        </x-slot>
        @forelse($projects as $project)
            <tr>
                <td>{{ $project->name }}</td>
                    
                <td>
                    <x-bladewind.tag label="{{ ucwords($project->environment) }}" color="gray" />
                </td>
                <td>
                    <x-bladewind.tag label="{{ $project->exceptions_count }}" color="gray" />
                </td>
                <td class="px-4 py-2 text-right">
                    <div class="relative inline-flex group">
                        <a href="{{ route('projects.exceptions.index', $project->id) }}" class="inline-flex mr-2 float-right items-center">
                            <x-bladewind.icon name="bug-ant" />
                        </a>
                        <div class="bg-gray-800 text-white p-2 rounded-lg absolute top-full left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                            {{ __('Errors')}}
                        </div>
                    </div>
                    <div class="relative inline-flex group">
                        <a href="{{ route('projects.configurations', $project->id) }}" class="inline-flex mr-2 float-right items-center">
                            <x-bladewind.icon name="cog" />
                        </a>
                        <div class="bg-gray-800 text-white p-2 rounded-lg absolute top-full left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                            {{ __('Configurations')}}
                        </div>
                    </div>
                    @if(canDo($team->id, auth()->id(), 'can_edit_project'))
                        <div class="relative inline-flex group">
                            <a @click="openEditModal('{{ $project }}')" href="#" class="inline-flex mr-2 float-right items-center">
                                <x-bladewind.icon name="pencil-square" />
                            </a>
                            <div class="bg-gray-800 text-white p-2 rounded-lg absolute top-full left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                {{ __('Edit')}}
                            </div>
                        </div>
                    @endif
                    @if(canDo($team->id, auth()->id(), 'can_delete_project'))
                        <div class="relative inline-flex group">
                            <a onclick="confirmBefore(event, this)" href="{{ route('projects.delete', $project->id) }}" class="inline-flex float-right items-center">
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
