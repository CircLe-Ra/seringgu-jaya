@props(['label', 'id', 'size' => 'base', 'disabled' => false, 'placeholder', 'mainClass' => null, 'alert' => true, 'error' => null])
@php
    $size = match ($size) {
        'large' => ' p-4 text-base',
        'base' => ' p-2.5 text-sm',
        'small' => ' p-2 text-xs',
    };
@endphp

<div class="{{ $mainClass }}">
    @isset($label)
        <x-ui.input-label for="{{ $id }}" :value="$label ?? 'label'" />
    @endisset
    <textarea {{ $attributes->whereStartsWith('wire:model') }} id="{{ $id }}" {{ $attributes->merge(['class' => 'block w-full text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500' . $size]) }} @disabled($disabled) placeholder="{{ $placeholder ?? '' }}" row="3"></textarea>
    @if($alert)
        <x-ui.input-error class="mt-2" :messages="$errors->get($error ?? $attributes->get('wire:model'))" />
    @endif
</div>
