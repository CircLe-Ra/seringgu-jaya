@props([
    'crumbs' => [],
    'actions' => null
])

{{-- <x.breadcrumbs :crumbs="[
    [
        'href' => '/blog',
        'text' => 'Blog'
    ],
    [
        'text' => 'Title Of Post'
    ]
]" /> --}}

<!-- Breadcrumb -->
<nav class="{{ $actions != null ? 'py-3' : 'py-[17px]' }} mt-16 sm:mt-12 flex justify-between px-5 text-gray-700 border border-gray-200 rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
        @foreach ($crumbs as $crumb)
            @if (isset($crumb['href']))
                <li class="inline-flex items-center">
                    <a wire:navigate href="{{ $crumb['href'] }}" class="inline-flex items-center text-base font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        @if($crumb['text'] == 'Dashboard')
                            <svg class="{{ $actions != null ? '-mt-[2px]' : '' }}  w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                        @else
                            <svg class="block -mt-[1px] w-3 h-3 mr-2 text-gray-400 rtl:rotate-180 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                        @endif
                        {{ $crumb['text'] }}
                    </a>
                </li>
            @else
                <li>
                    <div class="flex items-center">
                        <svg class="block -mt-[1px] w-3 h-3 text-gray-400 rtl:rotate-180 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="#" class="text-base font-medium text-gray-700 ms-1 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">{{ $crumb['text'] }}</a>
                    </div>
                </li>
            @endif
        @endforeach
    </ol>
    @isset($actions)
        <div {{ $actions->attributes->merge(['class' => '']) }}>
            {{ $actions }}
        </div>
    @endisset
</nav>
