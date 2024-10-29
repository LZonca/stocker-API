<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-mary-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus label="{{ __('Email') }}" icon="o-envelope" />
            </div>

            <div class="mt-4">
                <x-mary-password id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" label="{{ __('Password') }}" right icon="o-lock-closed" />
            </div>

            <div class="mt-4">
                <x-mary-password id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" right label="{{ __('Confirm Password') }}" icon="o-lock-closed" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-mary-button>
                    {{ __('Reset Password') }}
                </x-mary-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
