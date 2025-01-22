<?php

use function Livewire\Volt\{computed, state, layout, usesPagination, on, mount};
use App\Models\CitizenAssociation;
use Masmerise\Toaster\Toaster;

layout('layouts.app');
usesPagination();
state(['show' => 5, 'search' => ''])->url();
state(['position', 'name', 'address', 'phone', 'id']);

//mount(function () {

//});

$CAs = computed(function () {
    return CitizenAssociation::where('name', 'like', '%' . $this->search . '%')
        ->orWhere('phone', 'like', '%' . $this->search . '%')
        ->orWhere('address', 'like', '%' . $this->search . '%')
        ->orWhere('position', 'like', '%' . $this->search . '%')
        ->paginate($this->show, pageName: 'citizen-association-page');
});

on(['close-modal-reset' => function ($wireModels) {
    $this->reset($wireModels);
    $this->resetErrorBag($wireModels);
}]);

$save = function () {
    $this->validate([
        'position' => ['required', 'string'],
        'name' => ['required', 'string'],
        'address' => ['required', 'string'],
        'phone' => ['required', 'numeric'],
    ]);
    try {
        CitizenAssociation::updateOrCreate([
            'id' => $this->id
        ], [
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'position' => $this->position]);
        unset($this->CAs);
        $this->dispatch('close-modal', id: 'citizen-association-modal');
        Toaster::success('Data berhasil disimpan!');
    } catch (Exception $e) {
        $this->dispatch('close-modal', id: 'citizen-association-modal');
        Toaster::error($e->getMessage());
    }
};

$edit = function ($id) {
    $CA = CitizenAssociation::find($id);
    $this->id = $CA->id;
    $this->position = $CA->position;
    $this->name = $CA->name;
    $this->address = $CA->address;
    $this->phone = $CA->phone;
    $this->dispatch('open-modal', id: 'citizen-association-modal');
};

$destroy = function ($id) {
    try {
        $CA = CitizenAssociation::find($id);
        $CA->delete();
        unset($this->CAs);
        Toaster::success('Data berhasil dihapus!');
    }catch (Exception $e) {
        Toaster::error($e->getMessage());
    }
}

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
            [
                'href' => route('dashboard'),
                'text' => 'Dashboard'
            ],
            [
                'text' => 'Master Data'
            ],[
                'text' => 'Rukun Warga (RW)',
                'href' => route('master-data.citizen-association')
            ]
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
    <x-ui.modal id="citizen-association-modal">
        <x-slot name="header">
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Rukun Warga (RW)</h5>
        </x-slot>
        <x-slot name="content">
            <div class="grid-cols-1 sm:grid-cols-2 grid gap-2">
                <x-ui.input type="text" label="Jabatan (Ketua)" wire:model="position" id="position" name="position"/>
                <x-ui.input type="text" label="Nama" wire:model="name" id="name"/>
            </div>
            <x-ui.input type="tel" label="Nomor Telepon" wire:model="phone" id="phone"/>
            <x-ui.input type="text" label="Alamat" wire:model="address" id="address"/>
        </x-slot>
        <x-slot name="footer">
            <x-ui.button size="sm" reset color="light" class="mr-2" wire:click="$dispatch('close-modal', { id: 'citizen-association-modal' })">
                Batal
            </x-ui.button>
            <x-ui.button size="sm" loading-only title="Simpan" submit color="blue" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" wire:target="save" wire:click="save"/>
        </x-slot>
    </x-ui.modal>

    <div class="grid-cols-1 lg:grid-cols-3 grid gap-2 ">
        <div class="col-span-3 ">
            <x-ui.card class="mt-2 w-full ">
                <x-slot name="header">
                    <div>
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Rukun Warga (RW)</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar ketua rukun warga</p>
                    </div>
                </x-slot>
                <x-slot name="sideHeader">
                    <div class="flex items-center gap-2">
                        <x-ui.input-select id="show" name="show" wire:model.live="show" size="xs" class="w-full">
                            <option value="">Semua</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </x-ui.input-select>
                        <x-ui.button wire:click="$dispatch('open-modal', { id :'citizen-association-modal'})" size="xs"
                                     color="blue">
                            <span class="iconify duo-icons--add-circle w-4 h-4 me-1"></span>
                            Tambah
                        </x-ui.button>
                    </div>
                </x-slot>
                <x-ui.table thead="#, Jabatan, Nama, Nomor Telepon, Alamat" :action="true">
                    @if($this->CAs->count() > 0)
                        @foreach($this->CAs as $key => $CA)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $CA->position }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $CA->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $CA->phone }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $CA->address }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    <x-ui.button size="xs" color="blue" wire:click="edit({{ $CA->id }})">
                                        <span class="iconify carbon--edit w-3 h-3 me-1"></span>
                                        Ubah
                                    </x-ui.button>
                                    <x-ui.button size="xs" color="red" wire:click="destroy({{ $CA->id }})"
                                                 wire:confirm="Anda yakin ingin menghapus data ini?">
                                        <span class="iconify carbon--delete w-3 h-3 me-1"></span>
                                        Hapus
                                    </x-ui.button>
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-4 text-center" colspan="8">
                                Tidak ada data
                            </td>
                        </tr>
                    @endif
                </x-ui.table>
                {{ $this->CAs->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
</div>
