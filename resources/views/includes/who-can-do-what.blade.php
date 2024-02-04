<div x-show="who_can_do" @click.away="who_can_do = false" class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur">
    <!-- Modal content with custom width -->
    <div class="bg-white p-8 rounded shadow-lg"> <!-- Adjust the width here -->
        <x-bladewind.table>
            <x-slot name="header">
                <th></th>
                <th>{{ __('USER') }}</th>
                <th>{{ __('ADMIN') }}</th>
                <th>{{ __('OWNER') }}</th>
            </x-slot>
            <tr>
                <td>{{__('CAN EDIT TEAM')}}</td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
            </tr>
            <tr>
                <td>{{__('CAN DELETE TEAM')}}</td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
            </tr>
            <tr>
                <td>{{__('CAN CREATE PROJECT')}}</td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
            </tr>
            <tr>
                <td>{{__('CAN EDIT PROJECT')}}</td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
            </tr>
            <tr>
                <td>{{__('CAN DELETE PROJECT')}}</td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
            </tr>
            <tr>
                <td>{{__('CAN ADD MEMBER')}}</td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
            </tr>
            <tr>
                <td>{{__('CAN REMOVE MEMBER')}}</td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
            </tr>
            <tr>
                <td>{{__('CAN GENERATE LUCENT KEY')}}</td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-red-400" name="x-circle"/>
                </td>
                <td>
                    <x-bladewind.icon class="text-green-400" name="check-circle"/>
                </td>
            </tr>
        </x-bladewind.table>
        <button @click="who_can_do = false" class="mt-4 bg-red-500 hover:bg-red-800 text-white font-bold py-2 px-4 rounded modal-cancel-btn">X</button>
    </div>
</div>
