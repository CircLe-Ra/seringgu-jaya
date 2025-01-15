@props(['label' => 'Belum Diatur!'])
<div class="inline-flex items-center justify-center w-full">
    <hr {{ $attributes->merge(['class' => 'h-px bg-gray-200 border-0 dark:bg-gray-500']) }}>
    <span class="absolute px-3 font-medium text-gray-900 -translate-x-1/2 bg-white left-1/2 dark:text-white dark:bg-gray-700">{{ $label ?? $slot }}</span>
</div>
