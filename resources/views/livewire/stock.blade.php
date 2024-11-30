<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="mt-6 text-gray-500">
                <x-mary-header title="{{ $stock->nom }}" subtitle="{{ $stock->description ?? __('No description') }}" >
                    <x-slot:actions>
                        <x-mary-button icon="o-plus" wire:click="$toggle('seeCreateModal')" spinner class="btn-circle"/>
                    </x-slot:actions>
                </x-mary-header>
                <ul>
                    @forelse ($products as $product)
                        <x-mary-list-item :item="$product" link="/stocks/{{$stock->id}}/products/{{$product->id}}" class="bg-white dark:bg-gray-900 text-sm hover:accent-gray-700 hover:text-blue-50">
                            <x-slot:avatar>
                                <x-mary-avatar image="{{ $product->image }}" alt="Product image" />
                            </x-slot:avatar>
                            <x-slot:value>
                                <p class="text-gray-900 dark:text-white">{{ $product->nom }}</p>
                            </x-slot:value>
                            <x-slot:sub-value>
                                <p class="text-gray-500 dark:text-gray-400">{{ $product->code }}</p>
                            </x-slot:sub-value>
                            <x-slot:actions>
                                <x-mary-dropdown>
                                    <x-mary-menu-item title="{{__('Archive')}}" icon="o-archive-box"
                                                      wire:click="toggleArchive({{$product->id}})"/>
                                    <x-mary-menu-item title="{{__('Edit')}}" icon="fas.edit"
                                                      wire:click="confirmEdit({{ $product->id }})"/>
                                    <x-mary-menu-item title="{{__('Remove')}}" icon="o-trash"
                                                      wire:click="confirmDelete({{ $product->id }})"/>
                                </x-mary-dropdown>
                            </x-slot:actions>
                        </x-mary-list-item>
                    @empty
                        <li>{{__('No products found.')}}</li>
                    @endforelse
                </ul>
            </div>
            <x-mary-modal wire:model="seeCreateModal" title="{{ __('Create a new product') }}" class="text-gray-950 dark:text-gray-200" persistent>
                <x-mary-form wire:submit="createProduct">
                    <x-mary-input wire:model="newProductName" label="{{__('Name')}}" inline/>
                    <x-mary-input wire:model="newProductDescription" label="{{__('Description')}}" inline/>
                    <x-mary-input wire:model="newProductCode" label="{{__('Code')}}" inline/>
                    <x-mary-input wire:model="newProductPrice" label="{{__('Price')}}" type="number" step="0.01"
                                  inline/>
                    <x-mary-input wire:model="newProductQuantity" label="{{__('Quantity')}}" type="number" inline/>
                    <x-mary-input wire:model="newProductExpiryDate" label="{{__('Expiry Date')}}" type="date" inline/>
                    <x-slot:actions>
                        <x-mary-button wire:click="$toggle('seeCreateModal')" class="" label="{{__('Cancel')}}"/>
                        <x-mary-button wire:click="createProduct" class="btn-primary" label="{{__('Create')}}"/>
                    </x-slot:actions>
                </x-mary-form>
            </x-mary-modal>
        </div>
    </div>
</div>
