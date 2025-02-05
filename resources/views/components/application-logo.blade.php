@props(['size' => 'sm', 'textSize' => 'sm'])
@php
    $size = match ($size) {
        'sm' => 'w-10',
        'md' => 'w-12',
        'lg' => 'w-16',
        'xl' => 'w-20',
        '2xl' => 'w-24',
        '3xl' => 'w-28',
    };

    $textPrimary = match ($textSize) {
        'sm' => 'text-xl',
        'md' => 'text-2xl',
        'lg' => 'text-3xl',
        'xl' => 'text-4xl',
    };
    $textSecondary = match ($textSize) {
        'sm' => 'text-base',
        'md' => 'text-lg',
        'lg' => 'text-xl',
        'xl' => 'text-2xl',
    };
@endphp
<div class="flex flex-row items-center">
    <img class="{{ $size }}" src="{{ asset('img/logo.png') }}" alt="Logo" />
    <div class="flex flex-col ms-3 ">
        <h2 class="{{ $textPrimary }} font-semibold text-gray-900 dark:text-gray-100 -mb-2 -mt-1 tracking-widest" >Kelurahan</h2>
        <h4 class="{{ $textSecondary }} font-semibold text-gray-900 dark:text-gray-100 tracking-widest">Seringgu Jaya</h4>
    </div>
</div>
