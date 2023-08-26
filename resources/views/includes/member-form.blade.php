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
