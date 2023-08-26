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
                        @if ((canDo($team->id, auth()->id(), 'can_remove_member')) || ($member->pivot->user_id == auth()->id()))  
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
