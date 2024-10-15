<div>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
        <div class="flex justify-center text-center">
            <x-application-logo class="block h-12 w-auto" />
        </div>
        <div class="mt-8 text-2xl">
            Your Groups
        </div>
        <div class="mt-6 text-gray-500">
            <ul>
                @forelse ($groups as $group)
                    <li class="py-2">
                        <div class="text-lg font-semibold">{{ $group->nom }}</div>
                        <div class="text-sm text-gray-600">{{ $group->description }}</div>
                    </li>
                @empty
                    <li>No groups found.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <x-mary-button icon="o-plus" wire:click="$toggle('seeCreateModal')" spinner class="btn-sm btn-circle fixed bottom-10 right-10 bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-105"/>
    <x-mary-modal wire:model="seeCreateModal" title="{{ __('Create a new group') }}" class="text-gray-950 dark:text-gray-200" persistent>
        <x-mary-form wire:submit="createGroup">
            <x-mary-input wire:model="newGroupName" label="Name" inline/>
            <x-slot:actions>
                <x-mary-button wire:click="$toggle('seeCreateModal')" class="" label="Cancel"/>
                <x-mary-button wire:click="createGroup" class="btn-primary" label="Create"/>
            </x-slot:actions>
        </x-mary-form>
    </x-mary-modal>
</div>
