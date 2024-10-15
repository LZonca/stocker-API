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
                            <div class="text-lg font-semibold">{{ $group['name'] }}</div>
                            <div class="text-sm text-gray-600">{{ $group['description'] }}</div>
                        </li>
                    @empty
                        <li>No groups found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
</div>
