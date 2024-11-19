<div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
    <x-application-logo class="block h-12 w-auto" />

    <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
        {{ __('Welcome to :app!', ['app' => config('app.name')]) }}
    </h1>

    <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">
        {{__('Stocker is a simple application that helps you manage your inventories, to reduce waste and save money ! Get started by downloading Stocker on mobile.')}}
    </p>
</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
    <div>
        <div class="flex items-center">
            <x-mary-icon name="fab.apple" class="w-6 h-6" />
            <h2 class="ms-3 text-xl font-semibold text-gray-900 dark:text-white">
                <a href="{{ route('mobile-app') }}">{{__('Mobile web app')}}</a>
            </h2>
        </div>

        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            {{__('Stocker is not available on IOS for now, but you can use the web version on your mobile.')}}
        </p>

        <p class="mt-4 text-sm">
            <a href="{{ route('mobile-app') }}" class="inline-flex items-center font-semibold text-indigo-700 dark:text-indigo-300">
                {{__('Access the web app')}}

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 w-5 h-5 fill-indigo-500 dark:fill-indigo-200">
                    <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                </svg>
            </a>
        </p>
    </div>

    <div>
        <div class="flex items-center">
            <x-mary-icon name="fab.android" class="w-6 h-6" />
            <h2 class="ms-3 text-xl font-semibold text-gray-900 dark:text-white">
                <a href="{{ route("download-mobile") }}">Stocker mobile</a>
            </h2>
        </div>

        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            {{__('Begin using stocker on your mobile device. Stocker is available for download on android only as of now. Download the app
            and start managing your inventories today.')}}
        </p>

        <p class="mt-4 text-sm">
            <a href="{{ route("download-mobile") }}" class="inline-flex items-center font-semibold text-indigo-700 dark:text-indigo-300">
                {{__('Download for android')}}

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 w-5 h-5 fill-indigo-500 dark:fill-indigo-200">
                    <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                </svg>
            </a>
        </p>
    </div>
    <div>
        <div class="flex items-center">
            <x-mary-icon name="o-shopping-cart" class="w-6 h-6" />
            <h2 class="ms-3 text-xl font-semibold text-gray-900 dark:text-white">
                <a href="{{route("lists.index")}}">{{__('Shopping lists')}}</a>
            </h2>
        </div>

        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            {{__('Create and access your shopping lists for your stocks.')}}
        </p>

        <p class="mt-4 text-sm">
            <a href="{{route("lists.index")}}" class="inline-flex items-center font-semibold text-indigo-700 dark:text-indigo-300">
                {{__('Go to the shopping lists')}}

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 w-5 h-5 fill-indigo-500 dark:fill-indigo-200">
                    <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                </svg>
            </a>
        </p>
    </div>
    <div>
        <div class="flex items-center">
            <x-mary-icon name="eos.history-edu" class="w-6 h-6" />
            <h2 class="ms-3 text-xl font-semibold text-gray-900 dark:text-white">
                <a href="{{route('logs.index')}}">{{__('Logs')}}</a>
            </h2>
        </div>

        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            {{__('See the logs of your activities on Stocker.')}}
        </p>

        <p class="mt-4 text-sm">
            <a href="{{route('logs.index')}}" class="inline-flex items-center font-semibold text-indigo-700 dark:text-indigo-300">
                {{__('Go to the logs')}}

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 w-5 h-5 fill-indigo-500 dark:fill-indigo-200">
                    <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                </svg>
            </a>
        </p>
    </div>
</div>
