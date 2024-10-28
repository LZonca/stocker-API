<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
            <x-mary-header title="{{ $group->nom }}" >
                <x-slot:actions>
                    <x-mary-button icon="o-plus" wire:click="$toggle('seeCreateModal')" spinner class="btn-circle"/>
                </x-slot:actions>
            </x-mary-header>
            <p class="mt-4 text-gray-500 dark:text-gray-400">{{ $group->description }}</p>
            <p class="mt-4 text-gray-500 dark:text-gray-400">Owner: {{ $group->proprietaire->name }}</p>

            <div class="mt-6 text-gray-500">
                <ul>
                    @forelse ($groupStocks as $stock)
                        <x-mary-list-item :item="$stock" link='' no-separator class="hover:accent-gray-700 hover:text-blue-50">
                            <x-slot:avatar>
                                <img src="{{$stock->image != null ? $stock->image : asset('stocker.png')  }}" alt="" class="btn-circle" />
                            </x-slot:avatar>
                            <x-slot:value>
                                {{$stock->nom}}
                            </x-slot:value>
                            <x-slot:sub-value>

                            </x-slot:sub-value>
                            {{--<x-slot:actions>
                                <x-mary-button icon="o-trash" class="text-red-500" wire:click="delete(1)" spinner />
                            </x-slot:actions>--}}
                        </x-mary-list-item>
                    @empty
                        <li>No stocks found.</li>
                    @endforelse
                </ul>
            </div>
            <x-mary-modal wire:model="seeCreateModal" title="{{ __('Create a new group') }}" class="text-gray-950 dark:text-gray-200" persistent>
                <x-mary-form wire:submit="createGroup">
                    <x-mary-input wire:model="newGroupStockName" label="Name" inline/>
                    <x-slot:actions>
                        <x-mary-button wire:click="$toggle('seeCreateModal')" class="" label="Cancel"/>
                        <x-mary-button wire:click="createStock" class="btn-primary" label="Create"/>
                    </x-slot:actions>
                </x-mary-form>
            </x-mary-modal>
        </div>
    </div>
</div>
