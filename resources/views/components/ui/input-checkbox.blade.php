@props(['label','value' => null, 'name', 'id', 'size' => 'base', 'disabled' => false, 'mainClass' => null, 'alert' => true, 'error' => null])
@php
    $size = match ($size) {
        'large' => ' p-4 text-base',
        'base' => ' p-2.5 text-sm',
        'small' => ' p-2 text-xs',
    };
@endphp

<div class="{{ $mainClass }} flex items-center">
    <input id="{{ $id }}" name="{{  $name }}" type="checkbox" value="{{ $value }}" {{ $attributes->merge(['class' => 'w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600']) }} @disabled($disabled)>
    <label for="{{ $id }}" class="ms-2 text-base font-medium text-gray-900 dark:text-gray-300">{{  $label ?? 'label' }}</label>
    @if($alert)
        <x-ui.input-error class="mt-2" :messages="$errors->get($error ?? $attributes->get('wire:model'))" />
    @endif
</div>

