<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <x-bladewind.table>
        <x-slot name="header">
            <th>{{__('Name')}}</th>
            <th>{{__('Environment')}}</th>
            <th></th>
        </x-slot>
        @forelse($projects as $project)
            <tr>
                <td>{{ $project->name }}</td>
                    
                <td>
                    <x-bladewind.tag label="{{ ucwords($project->environment) }}" color="gray" />
                </td>
                <td>
                    @if(canDo($team->id, auth()->id(), 'can_edit_project'))
                        <a onclick="confirmBefore(event, this)" href="{{ route('projects.delete', $project->id) }}" class="inline-flex float-right items-center">
                            <x-bladewind.icon name="trash" />
                        </a>
                    @endif
                    @if(canDo($team->id, auth()->id(), 'can_delete_project'))
                        <a @click="openEditModal('{{ $project }}')" href="#" class="inline-flex mr-2 float-right items-center">
                            <x-bladewind.icon name="pencil-square" />
                        </a>
                        <a @click="openEditModal('{{ $project }}')" href="#" class="inline-flex mr-2 float-right items-center">
                            <x-bladewind.icon name="document-magnifying-glass" />
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
