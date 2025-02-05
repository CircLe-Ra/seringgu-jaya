<div wire:ignore class="trix-container">
    <input id="{{ $trixId }}" type="hidden" name="content" value="{{ $value }}">
    <trix-editor input="{{ $trixId }}"></trix-editor>

    @pushonce('scripts-bottom')
        @script
        <script>
            window.addEventListener('livewire:navigated', () => {
                addEventListener('trix-change', function(event) {
                    console.log(event);
                    const content = event.target.value;

                    @this.set('value', content);
                });
                // document.addEventListener('trix-file-accept', function(event) {
                //     event.preventDefault();
                // });
            }, { once: true });
        </script>
        @endscript
    @endpushonce
</div>
