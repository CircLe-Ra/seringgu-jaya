<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scrollbar-thumb-gray-700 scrollbar-track-white  overflow-y-scroll scrollbar-thin dark:scrollbar-track-gray-800 dark:scrollbar-thumb-gray-500">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
        <script>
            window.userId = '{{ auth()->user()->id }}';
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('scripts')
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 ">
        <livewire:layout.navigation />
        <div class="pt-4  sm:ml-64">
            <div class="mt-12 rounded-lg dark:border-gray-700 px-2">
                {{ $slot }}
            </div>
        </div>
        <x-toaster-hub />
        @stack('scripts-bottom')
    </body>
</html>
