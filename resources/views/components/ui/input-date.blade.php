@props(['label', 'id', 'size' => 'base', 'disabled' => false, 'placeholder', 'mainClass' => null, 'alert' => true, 'error' => null])
@php
    $size = match ($size) {
        'large' => ' p-4 text-base',
        'base' => ' p-2.5',
        'small' => ' p-2 text-xs',
    };
@endphp

<div class="{{ $mainClass }}">
    @isset($label)
        <x-ui.input-label for="{{ $id }}" :value="$label ?? 'label'" />
    @endisset
    <input {{ $attributes->whereStartsWith('wire:model') }} type="text" {{ $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 hidden' . $size]) }} placeholder="{{ $placeholder ?? '' }}"  @disabled($disabled)>
        <div id="{{ $id }}" class="flex justify-center "></div>
    @if($alert)
        <x-ui.input-error class="mt-2" :messages="$errors->get($error ?? $attributes->get('wire:model'))" />
    @endif
</div>

@pushonce('scripts')
    @script
    <script>
        window.addEventListener('livewire:navigated' , () => {
            const options = {
                defaultDatepickerId: null,
                autohide: false,
                format: 'mm/dd/yyyy',
                maxDate: null,
                minDate: null,
                orientation: 'bottom',
                buttons: false,
                autoSelectToday: false,
                title: null,
                rangePicker: false,
                onShow: () => {},
                onHide: () => {},
            };
            const instanceOptions = {
                id: @js($id) + '-example',
                override: true,
            };
            const $datepickerEl = document.getElementById(@js($id));
            const datepicker = new Datepicker($datepickerEl, options, instanceOptions);
        }, { once: true });
    </script>
    @endscript
@endpushonce
