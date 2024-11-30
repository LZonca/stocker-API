<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="mt-6 text-gray-500">
                <x-mary-header title="{{__('Logs')}}"
                               subtitle="{{ __('Woah, that is a lot of info, use the search bar to find what you are looking for !') }}">
                    <x-slot:middle class="!justify-end">
                        <x-mary-input wire:model.live="searchTerm" icon="zondicon.search" placeholder="Search..." />
                    </x-slot:middle>
                    <x-slot:actions>
                        <x-mary-button icon="eos.refresh" class="btn-primary" wire:click="refreshLogs" spinner="refreshLogs"/>
                    </x-slot:actions>
                </x-mary-header>
                <x-mary-table wire:model="selected" :headers="$headers" :rows="$logs" :sort-by="$sortBy" with-pagination class="text-gray-200 dark:text-gray-950 bg-transparent">
                    @scope('cell_log_name', $log)
                    <span class="text-gray-900 dark:text-gray-200">{{__(ucfirst($log->log_name)) }}</span>
                    @endscope
                    @scope('cell_description', $log)
                    @switch($log->description)

                        @case('created')
                            <x-mary-icon name="fas.plus-circle"
                                         class="text-green-600" {{--label="{{ __(ucfirst($log->description)) }}"--}} />
                            @break
                        @case('deleted')
                            <x-mary-icon name="o-trash"
                                         class="text-red-600" {{--label="{{ __(ucfirst($log->description)) }}"--}} />
                            @break
                        @case('updated')
                            <x-mary-icon name="fas.edit" {{--label="{{ __(ucfirst($log->description)) }}"--}} />
                            @break
                        @default
                            <x-mary-icon label="{{ __(ucfirst($log->description)) }}"/>
                    @endswitch
                    @endscope
                    @scope('cell_subject_type', $log)
                    @php
                        $subjectType = str_contains($log->subject_type, 'App\Models') ? str_replace('App\Models\\', '',
                        $log->subject_type) : $log->subject_type;
                    @endphp

                    @switch($subjectType)

                        @case('User')
                            <x-mary-icon name="s-user" label="{{ __($subjectType) }}"/>
                            @break
                        @case('Produit')
                            <x-mary-icon name="carbon.product" label="{{ __($subjectType) }}"/>
                            @break
                        @case('Groupe')
                            <x-mary-icon name="gmdi.group-s" label="{{ __($subjectType) }}"/>
                            @break
                        @case('Stock')
                            <x-mary-icon name="vaadin.stock" label="{{ __($subjectType) }}"/>
                            @break
                        @default
                            <x-mary-icon name="fas.question" label="Unknown" />
                    @endswitch
                    @endscope

                    @scope('cell_causer_id', $log)
                    @if($log->causer_type == 'App\Models\User')
                        @php
                            $user = \App\Models\User::find($log->causer_id);
                        @endphp
                        @php
                            $user = \App\Models\User::find($log->causer_id);
                        @endphp
                        @if($user)
                            <x-mary-badge value="{{ $user->email }}" class="badge-success" />
                        @else
                            <x-mary-badge value="Unknown User" class="badge-warn" />
                        @endif
                    @else
                        <x-mary-badge value="Système" class="badge-info" />
                    @endif
                    @endscope
                    @scope('actions', $log)
                    <x-mary-button icon="o-eye" wire:click="showLogDetails({{ $log->id }})" spinner class="btn-sm" />
                    @endscope
                    <x-slot:empty>
                        <x-mary-icon name="o-cube" label="It is empty." />
                    </x-slot:empty>
                </x-mary-table>
                @if($seeModal)
                    <x-mary-modal wire:model="seeModal" title="{{ $selectedLog->log_name }}" class="text-gray-950 dark:text-gray-200">
                        <pre class="text-left">{{ json_encode($selectedLog->properties, JSON_PRETTY_PRINT) }}</pre>
                    </x-mary-modal>
                @endif
            </div>
        </div>
    </div>
</div>
