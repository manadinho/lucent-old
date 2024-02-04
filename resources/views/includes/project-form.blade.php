<div  x-show="open" @click.away="open = false" class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur">
    <!-- Modal content with custom width -->
    <div class="bg-white p-8 rounded shadow-lg w-96"> <!-- Adjust the width here -->
    <h2 class=" card-title text-xl font-bold mb-4">{{__('Create Project')}}</h2>
    
    <form method="POST" action="{{ route('projects.create') }}">
        @csrf
        <input type="hidden" name="team_id" value="{{ $team->id }}">
        <input type="hidden" name="id" x-model="id">

        <!-- MEMBER EMAIL -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" x-model="name" type="text" name="name" value='' required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="Select Environment" :value="__('Select Environment')" />
            <x-select-tag x-model="environment" name="environment" label="Select Environment">
                <option >{{__('Select Environment')}}</option>
                <option value="production">{{__('Production')}}</option>
                <option value="staging">{{__('Staging')}}</option>
                <option value="development">{{__('Development')}}</option>
                <option value="local">{{__('Local')}}</option>
            </x-select-tag>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-3">
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </form>
    <button @click="close()" class="mt-4 bg-red-500 hover:bg-red-800 text-white font-bold py-2 px-4 rounded modal-cancel-btn">X</button>
    </div>
</div>
