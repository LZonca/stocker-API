<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white dark:bg-gray-900 dark:text-gray-100 border-b border-gray-200">
                <div class="flex justify-center text-center ">
                    <x-application-logo class="block h-12 w-auto" />
                </div>
                <div class="mt-8 text-2xl">
                    Your Groups
                </div>
                <div class="mt-6 text-gray-500">
                    <ul>

                        @forelse ($groups as $group)
                            <x-mary-list-item :item="$group" link='' no-separator class="bg-white dark:bg-gray-900 text-sm">
                                <x-slot:avatar>
                                    <img src="{{$group->image }}" alt="Group image" class="btn-circle" />
                                </x-slot:avatar>
                                <x-slot:value>
                                    {{$group->nom}}
                                </x-slot:value>
                                <x-slot:sub-value>
                                    <x-mary-popover class="dark:bg-gray-900 text-sm">
                                        <x-slot:trigger>
                                            {{__('Owner: ') . $group->proprietaire->name}}
                                        </x-slot:trigger>
                                        <x-slot:content>
                                            <x-mary-avatar :image="$group->proprietaire->profile_photo_url" />
                                            {{__('Owner: ') . $group->proprietaire->name}} <br>
                                            {{ __('Email: ') . $group->proprietaire->email }}
                                        </x-slot:content>
                                    </x-mary-popover>
                                </x-slot:sub-value>
                                {{--<x-slot:actions>
                                    <x-mary-button icon="o-trash" class="text-red-500" --}}{{--wire:click="delete(1)" --}}{{--spinner />
                                </x-slot:actions>--}}
                            </x-mary-list-item>
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
    </div>
</div>
