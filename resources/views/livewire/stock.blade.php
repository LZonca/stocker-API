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
                                <p class="text-gray-900 dark:text-white">{{ $product->custom_name ?? $product->nom }}</p>
                            </x-slot:value>
                            <x-slot:sub-value>
                                <p class="text-gray-500 dark:text-gray-400">{{ $product->custom_code ?? $product->code }}</p>
                            </x-slot:sub-value>
                        </x-mary-list-item>
                    @empty
                        <li>{{__('No products found.')}}</li>
                    @endforelse
                </ul>
            </div>
            <x-mary-modal wire:model="seeCreateModal" title="{{ __('Create a new product') }}" class="text-gray-950 dark:text-gray-200" persistent>
                <x-mary-form wire:submit="createProduct">
                    <x-mary-input wire:model="newProductName" label="Name" inline/>
                    <x-mary-input wire:model="newProductDescription" label="Description" inline/>
                    <x-mary-input wire:model="newProductCode" label="Code" inline/>
                    <x-slot:actions>
                        <x-mary-button wire:click="$toggle('seeCreateModal')" class="" label="{{__('Cancel')}}"/>
                        <x-mary-button wire:click="createProduct" class="btn-primary" label="{{__('Create')}}"/>
                    </x-slot:actions>
                </x-mary-form>
            </x-mary-modal>
        </div>
    </div>
</div>
