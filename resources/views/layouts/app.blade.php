<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scrollbar-thumb-gray-700 scrollbar-track-white scrollbar-thin dark:scrollbar-track-gray-800 dark:scrollbar-thumb-gray-500">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('img/site.webmanifest') }}">

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
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 ">
        <x-bg-gradient />
        <livewire:layout.navigation />
        <div class="pt-4  sm:ml-64">
            <div class="mt-[55px] rounded-lg dark:border-gray-700 px-2">
                {{ $slot }}
            </div>
        </div>
        <x-toaster-hub />
        @stack('scripts-bottom')
    </body>
</html>