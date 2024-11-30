<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white dark:bg-gray-900 border-b border-gray-200">
                <div class="flex justify-center text-center">
                    <x-application-logo class="block h-12 w-auto"/>
                </div>
                <x-mary-header title="{{__('Your stocks')}}">
                    <x-slot:actions>
                        <x-mary-button icon="o-plus" wire:click="$toggle('seeCreateModal')" spinner class="btn-circle"/>
                    </x-slot:actions>
                </x-mary-header>
                <div class="mt-6 text-gray-500">
                    <ul>
                        @forelse ($groupStocks as $stock)
                            <x-mary-list-item :item="$stock" link='/stocks/{{$stock->id }}' no-separator
                                              class="hover:accent-gray-700 hover:text-blue-50 bg-white dark:bg-gray-900 text-sm">
                                <x-slot:avatar>
                                    <img src="{{$stock->image != null ? $stock->image : asset('stocker.png')  }}" alt=""
                                         class="btn-circle"/>
                                </x-slot:avatar>
                                <x-slot:value>
                                    {{$stock->nom}}
                                </x-slot:value>
                                <x-slot:sub-value>

                                </x-slot:sub-value>
                                <x-slot:actions>
                                    <x-mary-dropdown>
                                        <x-mary-menu-item title="Edit" icon="fas.edit"
                                                          wire:click="confirmEdit({{ $stock->id }})"/>
                                        <x-mary-menu-item title="Remove" icon="o-trash"
                                                          wire:click="confirmDelete({{ $stock->id }})"/>
                                    </x-mary-dropdown>
                                </x-slot:actions>
                            </x-mary-list-item>
                        @empty
                            <li>{{__('No stocks found.')}}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <x-mary-modal wire:model="seeCreateModal" title="{{ __('Create a new stock') }}" class="text-gray-950 dark:text-gray-200" persistent>
                <x-mary-form wire:submit.prevent="createStock">
                    <x-mary-input wire:model="newGroupStockName" label="{{__('Name')}}" inline/>
                    <x-slot:actions>
                        <x-mary-button wire:click="$toggle('seeCreateModal')" class="" label="{{__('Cancel')}}"/>
                        <x-mary-button wire:click="createStock" class="btn-primary" label="{{__('Create')}}"/>
                    </x-slot:actions>
                </x-mary-form>
            </x-mary-modal>
            <x-mary-modal wire:model="seeEditModal" title="{{ __('Edit Stock') }}"
                          class="text-gray-950 dark:text-gray-200" persistent>
                <x-mary-form wire:submit.prevent="editStock">
                    <x-mary-input wire:model="editGroupStockName" label="{{__('Name')}}" inline/>
                    <x-slot:actions>
                        <x-mary-button wire:click="$toggle('seeEditModal')" class="" label="{{__('Cancel')}}"/>
                        <x-mary-button wire:click="editStock" class="btn-primary" label="{{__('Edit')}}"/>
                    </x-slot:actions>
                </x-mary-form>
            </x-mary-modal>
            <x-mary-modal wire:model="seeDeleteModal" title="{{ __('Are you sure you want to delete this stock ?') }}"
                          class="text-gray-950 dark:text-gray-200" persistent>
                <x-mary-form wire:submit.prevent="deleteStock">
                    <x-slot:actions>
                        <x-mary-button wire:click="$toggle('seeDeleteModal')" class="" label="{{__('Cancel')}}"/>
                        <x-mary-button wire:click="deleteStock" class="btn-error" label="{{__('Delete')}}"/>
                    </x-slot:actions>
                </x-mary-form>
            </x-mary-modal>
        </div>
    </div>
</div>
