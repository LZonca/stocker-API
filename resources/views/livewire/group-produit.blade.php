<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <x-mary-avatar image="{{ $product->image ?? $product->custom_image }}" alt="Product image" class="!w-24 rounded-full" />
                    <div class="ml-4">
                        <x-mary-header title="{{ $product->custom_name ?? $product->nom }}" subtitle="{{ $product->custom_description ?? $product->description }}" />
                    </div>
                </div>
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{__('Details')}}</h3>
                    <ul class="mt-2 text-gray-500 dark:text-gray-400">
                        <li>
                            <x-mary-popover>
                                <x-slot:trigger>
                                    <x-mary-icon name="fas.barcode" label="{{ $product->custom_code ?? $product->code }}" />
                                </x-slot:trigger>
                                <x-slot:content>
                                    <p>{{__('Product code')}}</p>
                                </x-slot:content>
                            </x-mary-popover>
                        </li>
                        @if($product->custom_price != null && $product->prix != null)
                            <li>
                                <x-mary-popover>
                                    <x-slot:trigger>
                                        <x-mary-icon name="hugeicons.money-bag-02" label="{{ $product->custom_price !== null ? $product->custom_price . ' $' : ($product->prix !== null ? $product->prix . ' $' : 'N/A') }}" />
                                    </x-slot:trigger>
                                    <x-slot:content>
                                        <p>{{__('Product price')}}</p>
                                    </x-slot:content>
                                </x-mary-popover>
                            </li>
                        @endif

                        @if($product->custom_expiry_date != null && $product->expiry_date != null)
                            <li>
                                <x-mary-popover>
                                    <x-slot:trigger>
                                        <x-mary-icon name="gmdi.no-food-o" label="{{ $product->custom_expiry_date ?? $product->expiry_date ?? 'N/A' }}" />
                                    </x-slot:trigger>
                                    <x-slot:content>
                                        <p>{{__('Product expiry date')}}</p>
                                    </x-slot:content>
                                </x-mary-popover>
                            </li>
                        @endif

                        <li>
                            <x-mary-popover>
                                <x-slot:trigger>
                                    <x-mary-icon name="fas.box" label="{{ $product->getQuantite() }}" />
                                </x-slot:trigger>
                                <x-slot:content>
                                    <p>{{__('Product quantity')}}</p>
                                </x-slot:content>
                            </x-mary-popover>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
