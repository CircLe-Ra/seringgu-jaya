<?php

use function Livewire\Volt\{state, layout, mount, title, on};
use App\Models\Profile;
use Masmerise\Toaster\Toaster;

layout('layouts.app');
title('Geografis');
state(['value']);
mount(function () {
    $this->value = Profile::where('id', 1)->first()->geography ?? 'Geografis';
});

on(['trix_value_updated' => function ($value) {
    $this->value = $value;
}]);

$post = function () {
    try {
        Profile::updateOrCreate(['id' => 1], [
            'geography' => $this->value
        ]);
    Toaster::success('Data berhasil disimpan!');
    }catch (Exception $e) {
        Toaster::error($e->getMessage());
    }

}
?>

<div>
    <x-ui.breadcrumbs :crumbs="[
       ['href' => '/', 'text' => 'Dashboard'],
        ['text' => 'Portal'],
        ['text' => 'Geografis'],
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
                    Geografis Seringgu Jaya
                </h3>
                <p class="text-base text-gray-500 dark:text-gray-400">
                    Masukan Geografis Kelurahan Seringgu Jaya.
                </p>
            </div>
            <livewire:trix :value="$this->value"/>
        </div>
    </div>
</div>
