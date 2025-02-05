<?php

use function Livewire\Volt\{state, layout, mount, title, on, usesFileUploads};
use App\Models\Profile;
use Masmerise\Toaster\Toaster;

layout('layouts.app');
title('Struktur Organisasi');
usesFileUploads();
state(['structure', 'currentImage']);
mount(function () {
    $structure = Profile::where('id', 1)->first()->structure;
    $this->currentImage = $structure;
});

$post = function () {
    try {
        if ($this->structure) {
            if($this->currentImage){
                Storage::delete($this->currentImage);
            }
            $structure = $this->structure->store('structure');
            $this->structure = $structure;
            $this->currentImage = $structure;
        }else{
            $structure = $this->currentImage->store('structure');
            $this->structure = $structure;
            $this->currentImage = $structure;
        }
        Profile::updateOrCreate(['id' => 1], [
            'structure' => $this->structure
        ]);
        $this->dispatch('pond-reset');
    Toaster::success('Data berhasil disimpan!');
    }catch (Exception $e) {
        Toaster::error($e->getMessage());
    }

};

$deleteImage = function () {
    $structure = Profile::find(1);
    if ($structure) {
        Storage::delete($structure->structure);
        $structure->structure = null;
        $structure->save();
        $this->dispatch('pond-reset');
        $this->reset(['structure', 'currentImage']);
    }
}
?>

<div>
    <x-ui.breadcrumbs :crumbs="[
        ['href' => route('dashboard'), 'text' => 'Dashboard'],
        ['text' => 'Portal'],
        ['text' => 'Struktur Organisasi'],
    ]">
        <x-slot:actions>
            <x-ui.button color="blue" wire:click="post" size="xs">
                <x-slot:icon>
                    <svg height="20" width="20" class=" mr-1 text-gray-300" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h11.175q.4 0 .763.15t.637.425l2.85 2.85q.275.275.425.638t.15.762V19q0 .825-.587 1.413T19 21zm7-3q1.25 0 2.125-.875T15 15t-.875-2.125T12 12t-2.125.875T9 15t.875 2.125T12 18m-5-8h7q.425 0 .713-.288T15 9V7q0-.425-.288-.712T14 6H7q-.425 0-.712.288T6 7v2q0 .425.288.713T7 10"/>
                    </svg>
                </x-slot:icon>
                Simpan Perubahan
            </x-ui.button>
        </x-slot:actions>
    </x-ui.breadcrumbs>
    <div class="grid grid-cols-1 my-2">
        <div class="dark:bg-gray-800 bg-white p-5 border-gray-200 dark:border-gray-700 rounded-xl border ">
            <div class="my-3">
                <h3 class="font-bold text-2xl dark:text-gray-100 text-gray-900">
                    Struktur Organisasi Kelurahan Seringgu Jaya
                </h3>
                <p class="text-base text-gray-500 dark:text-gray-400">
                    Masukan Struktur Organisasi Kelurahan Seringgu Jaya.
                </p>
            </div>
            <div class="mt-4">
                <x-ui.filepond wire:model="structure" allowImagePreview />
                <div class="relative mt-3 rounded-xl overflow-hidden ">
                    @if($this->currentImage ?? false)
                        <img class="object-cover rounded-xl mx-auto" src="{{ asset('storage/' . $this->currentImage) }}" alt="Foto Utama" />
                        <div class="absolute top-0 right-0 p-1" data-popover-target="popover-left" data-popover-placement="left" wire:click="deleteImage" wire:confirm="Lanjutkan menghapus?">
                            <div data-popover id="popover-left" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-xs opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                                <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg dark:border-gray-600 dark:bg-gray-700">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Menghapus Gambar</h3>
                                </div>
                                <div class="px-3 py-2">
                                    <p class="">Tindakan ini akan menghapus gambar Struktur Organisasi.</p>
                                </div>
                                <div data-popper-arrow></div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 256 256">
                                <g fill="#e30000">
                                    <path d="M224 128a96 96 0 1 1-96-96a96 96 0 0 1 96 96" />
                                    <path fill="#fff" d="M165.66 101.66L139.31 128l26.35 26.34a8 8 0 0 1-11.32 11.32L128 139.31l-26.34 26.35a8 8 0 0 1-11.32-11.32L116.69 128l-26.35-26.34a8 8 0 0 1 11.32-11.32L128 116.69l26.34-26.35a8 8 0 0 1 11.32 11.32M232 128A104 104 0 1 1 128 24a104.11 104.11 0 0 1 104 104m-16 0a88 88 0 1 0-88 88a88.1 88.1 0 0 0 88-88" />
                                </g>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
