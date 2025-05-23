<?php

use function Livewire\Volt\{state, layout, computed, on, usesPagination,title};
use App\Models\BloodGroup;
use Masmerise\Toaster\Toaster;

layout('layouts.app');
title('Golongan Darah');
usesPagination();
state(['name', 'id']);
state(['show' => 5, 'search' => null])->url();

$bloods = computed(function () {
    return BloodGroup::where('name', 'like', '%' . $this->search . '%')
        ->latest()->paginate($this->show, pageName: 'bloods-page');
});

on(['refresh' => function () {
    $this->bloods = BloodGroup::where('name', 'like', '%' . $this->search . '%')
        ->latest()->paginate($this->show, pageName: 'bloods-page');
}]);

$store = function () {
    $this->validate([
        'name' => 'required|unique:blood_groups,name' . ($this->id ? ',' . $this->id : '')
    ]);

    try {
        BloodGroup::updateOrCreate(['id' => $this->id], [
            'name' => $this->name
        ]);
        unset($this->bloods);
        $this->reset(['name', 'id']);
        $this->dispatch('refresh');
        Toaster::success('Golongan Darah berhasil disimpan');
    } catch (\Exception $e) {
        Toaster::error('Golongan Darah gagal disimpan');
        Toaster::error($e->getMessage());
    }
};

$destroy = function ($id) {
    try {
        $blood = BloodGroup::find($id);
        $blood->delete();
        unset($this->bloods);
        $this->dispatch('refresh');
        Toaster::success('Berhasil menghapus data');
    } catch (\Exception $e) {
        Toaster::error('Gagal menghapus data');
    }
};

$edit = function ($id){
    $blood = BloodGroup::find($id);
    $this->id = $blood->id;
    $this->name = $blood->name;
};

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
        ['href' => '/', 'text' => 'Dashboard'],
        ['text' => 'Master Data'],
        ['text' => 'Golongan Darah']
    ]">
        <x-slot name="actions">
            <x-ui.input-icon id="search" wire:model.live="search" placeholder="Cari..." size="small">
                <x-slot name="icon">
                    <svg class="text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24"
                         height="24" viewBox="0 0 24 24">
                        <g fill="none">
                            <path fill="currentColor" fill-opacity="0.25" fill-rule="evenodd"
                                  d="M12 19a7 7 0 1 0 0-14a7 7 0 0 0 0 14M10.087 7.38A5 5 0 0 1 12 7a.5.5 0 0 0 0-1a6 6 0 0 0-6 6a.5.5 0 0 0 1 0a5 5 0 0 1 3.087-4.62"
                                  clip-rule="evenodd"/>
                            <path stroke="currentColor" stroke-linecap="round" d="M20.5 20.5L17 17"/>
                            <circle cx="11" cy="11" r="8.5" stroke="currentColor"/>
                        </g>
                    </svg>
                </x-slot>
            </x-ui.input-icon>
        </x-slot>
    </x-ui.breadcrumbs>

    <div class="grid-cols-1 lg:grid-cols-3 grid gap-2 ">
        <div class="w-full col-span-3 lg:col-span-1">
            <x-ui.card class="mt-2">
                <x-slot name="header">
                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Tambah Golongan Darah</h5>
                </x-slot>
                <form wire:submit="store" class="mx-auto">
                    <x-ui.input-select id="name" name="name" wire:model="name" main-class="mb-5" label="Golongan Darah">
                        <option value="">Masukan Nama</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                        <option value="N/A">TIDAK TAHU</option>
                    </x-ui.input-select>

                    <div class="flex justify-end space-x-2">
                        <x-ui.button type="reset" color="light">
                            Batal
                        </x-ui.button>
                        <x-ui.button submit color="blue" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" wire:target="store">
                            Simpan
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
        <div class="col-span-2">
            <x-ui.card class="mt-2 w-full">
                <x-slot name="header">
                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Daftar Golongan Darah</h5>
                </x-slot>
                <x-slot name="sideHeader">
                    <x-ui.input-select id="show" name="show" wire:model.live="show" size="xs" class="w-full">
                        <option value="">Semua</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </x-ui.input-select>
                </x-slot>

                <x-ui.table thead="#, Nama, Dibuat">
                    @if($this->bloods->count() > 0)
                        @foreach($this->bloods as $blood)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $blood->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $blood->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4">
                                    <x-ui.button color="yellow" size="xs" wire:click="edit({{  $blood->id }})">
                                        <span class="iconify carbon--edit w-3 h-3 me-1"></span>
                                        Edit
                                    </x-ui.button>
                                    <x-ui.button color="red" size="xs" wire:click="destroy({{ $blood->id}})" wire:confirm="Anda yakin akan menghapus data ini?">
                                        <span class="iconify carbon--delete w-3 h-3 me-1"></span>
                                        Hapus
                                    </x-ui.button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-4 text-center" colspan="4">
                                Tidak ada data
                            </td>
                        </tr>
                    @endif
                </x-ui.table>
                {{ $this->bloods->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
</div>
