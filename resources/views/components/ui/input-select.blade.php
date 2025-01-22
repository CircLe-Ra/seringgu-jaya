@props([
    'id' => null,
    'data' => [],
    'value' => 'id',
    'display_name' => 'name',
    'disabled' => false,
    'label' => null,
    'size' => 'md',
    'display_name_first' => 'Pilih?',
    'selected_first' => true,
    'selected' => null,
    'mainClass' => null,
    'alert' => true,
])

@php
    $dn = explode(',', $display_name);
    $size = match ($size){
        'xs' => 'block w-full p-1.5 text-xs text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
        'sm' => 'block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
        'md' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
        'lg' => 'block w-full px-4 py-3 text-base text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'
    };
@endphp


<div class="{{ $mainClass }}">
    @isset($label)
        <x-ui.input-label for="{{ $id }}" :value="$label ?? 'label'" />
    @endisset
    <select {{ $attributes->whereStartsWith('wire:model') }} id="{{ $id }}" {{ $attributes->merge(['class' => $size]) }} @disabled($disabled)>
        @if ($attributes->has('server'))
            <option @selected($selected_first) value="">{{ $display_name_first }}</option>
            @if (count($data))
                @foreach ($data as $dt)
                    <option @selected($selected == $dt->$value) value="{{ $dt->$value }}">&nbsp;
                        @foreach ($dn as $d)
                            {{ $dt->$d }}
                            @if($d != end($dn)) || @endif
                        @endforeach
                    </option>
                @endforeach
            @else
                <option value="" disabled>Tidak ada data</option>
            @endif
        @else
            {{ $slot }}
        @endif
    </select>
    @if($alert)
        <x-ui.input-error class="mt-2" :messages="$errors->get($error ?? $attributes->get('wire:model') ?? $attributes->get('wire:model.live'))" />
    @endif
</div>

