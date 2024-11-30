<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white dark:bg-gray-900 dark:text-gray-100 border-b border-gray-200">
                <div class="flex justify-center text-center ">
                    <x-application-logo class="block h-12 w-auto" />
                </div>
                <x-mary-header title="{{__('Your groups')}}" >
                    <x-slot:actions>
                        <x-mary-button icon="o-plus" wire:click="$toggle('seeCreateModal')" spinner class="btn-circle"/>
                    </x-slot:actions>
                </x-mary-header>

                <div class="mt-6 text-gray-500">
                    <ul>
                        @forelse ($groups as $group)
                            <x-mary-list-item :item="$group" link='/groups/{{$group->id}}' no-separator class="bg-white dark:bg-gray-900 text-sm hover:accent-gray-700 hover:text-blue-50">
                                <x-slot:avatar>
                                    <img src="{{$group->image }}" alt="Group image" class="btn-circle" />
                                </x-slot:avatar>
                                <x-slot:value>
                                    {{$group->nom}}
                                </x-slot:value>
                                <x-slot:sub-value>
                                    <x-mary-popover class="dark:bg-gray-900 text-sm">
                                        <x-slot:trigger>
                                            {{__('Owner: ') . ($group->proprietaire->id == Auth::user()->id ? 'You' : $group->proprietaire->name)}}
                                        </x-slot:trigger>
                                        <x-slot:content>
                                            <x-mary-avatar :image="$group->proprietaire->profile_photo_url" />
                                            {{__('Owner: ') . $group->proprietaire->name }} <br>
                                            {{ __('Email: ') . $group->proprietaire->email }}
                                        </x-slot:content>
                                    </x-mary-popover>
                                </x-slot:sub-value>
                                <x-slot:actions>
                                    <x-mary-dropdown>
                                        <x-mary-menu-item title="{{__('Group members')}}" icon="eos.group"
                                                          wire:click="showMembers({{ $group->id }})"/>
                                        <x-mary-menu-item title="{{__('Edit')}}" icon="fas.edit"
                                                          wire:click="confirmEdit({{ $group->id }})"/>
                                        <x-mary-menu-item title="{{__('Remove')}}" icon="o-trash"
                                                          wire:click="confirmDelete({{ $group->id }})"/>
                                    </x-mary-dropdown>
                                </x-slot:actions>
                            </x-mary-list-item>
                        @empty
                            <li>{{__('No groups found.')}}</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <x-mary-modal wire:model="seeCreateModal" title="{{ __('Create a new group') }}" class="text-gray-950 dark:text-gray-200" persistent>
                <x-mary-form wire:submit.prevent="createGroup">
                    <x-mary-input wire:model="newGroupName" label="{{__('Name')}}" inline/>
                    <x-slot:actions>
                        <x-mary-button wire:click="$toggle('seeCreateModal')" class="" label="{{__('Cancel')}}"/>
                        <x-mary-button wire:click="createGroup" class="btn-primary" label="{{__('Create')}}"/>
                    </x-slot:actions>
                </x-mary-form>
            </x-mary-modal>

            <x-mary-modal wire:model="seeEditModal" title="{{ __('Edit Group') }}"
                          class="text-gray-950 dark:text-gray-200" persistent>
                <x-mary-form wire:submit.prevent="editGroup">
                    <x-mary-input wire:model="editGroupName" label="{{__('Name')}}" inline/>
                    <x-slot:actions>
                        <x-mary-button wire:click="$toggle('seeEditModal')" class="" label="{{__('Cancel')}}"/>
                        <x-mary-button wire:click="editGroup" class="btn-primary" label="{{__('Edit')}}"/>
                    </x-slot:actions>
                </x-mary-form>
            </x-mary-modal>

            <x-mary-modal wire:model="seeDeleteModal" title="{{ __('Are you sure you want to delete this group?') }}"
                          class="text-gray-950 dark:text-gray-200" persistent>
                <x-mary-form wire:submit.prevent="deleteGroup">
                    <x-slot:actions>
                        <x-mary-button wire:click="$toggle('seeDeleteModal')" class="" label="{{__('Cancel')}}"/>
                        <x-mary-button wire:click="deleteGroup" class="btn-error" label="{{__('Delete')}}"/>
                    </x-slot:actions>
                </x-mary-form>
            </x-mary-modal>

            <x-mary-modal wire:model="seeMembersModal" title="{{ __('Group Members') }}"
                          class="text-gray-950 dark:text-gray-200" persistent>
                <div class="overflow-y-auto max-h-96">
                    @foreach($groupMembers as $member)
                        <x-mary-list-item :item="$member" no-separator no-hover>
                            <x-slot:avatar>
                                @if($member->id == $group->proprietaire->id)
                                    <x-mary-icon name="fas.crown"/>
                                @else
                                    <x-mary-icon name="o-user"/>
                                @endif
                            </x-slot:avatar>
                            <x-slot:value>
                                {{  $member->name }}
                            </x-slot:value>
                            <x-slot:sub-value>
                                {{ $member->email }}
                            </x-slot:sub-value>
                        </x-mary-list-item>
                        <x-slot:actions>
                            <x-mary-button icon="o-user-minus" class="text-red-500" spinner/>
                        </x-slot:actions>
                    @endforeach
                </div>
                <x-slot:actions>
                    <x-mary-button wire:click="$toggle('seeMembersModal')" class="" label="{{__('Close')}}"/>
                </x-slot:actions>
            </x-mary-modal>
        </div>
    </div>
</div>
