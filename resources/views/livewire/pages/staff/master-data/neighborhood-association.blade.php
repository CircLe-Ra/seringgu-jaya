<?php

use function Livewire\Volt\{computed, state, layout, usesPagination, on, mount,title};
use App\Models\CitizenAssociation;
use App\Models\NeighborhoodAssociation;
use Masmerise\Toaster\Toaster;
use App\Models\User;

layout('layouts.app');
title('Rukun Tetangga (RT)');
usesPagination();
state(['show' => 5, 'search' => ''])->url();
state(['position', 'name', 'address', 'phone', 'id', 'ca', 'user_id', 'email', 'password', 'password_confirmation']);
state(['citizen_associations' => [], 'editData' => true]);

mount(function () {
    $this->citizen_associations = CitizenAssociation::all();
});

$NAs = computed(function () {
    return NeighborhoodAssociation::where('name', 'like', '%' . $this->search . '%')
        ->orWhere('phone', 'like', '%' . $this->search . '%')
        ->orWhere('address', 'like', '%' . $this->search . '%')
        ->orWhere('position', 'like', '%' . $this->search . '%')
        ->paginate($this->show, pageName: 'citizen-association-page');
});

on(['close-modal-reset' => function ($wireModels) {
    $this->reset(['user_id', 'id']);
    $this->editData = true;
    $this->reset($wireModels);
    $this->resetErrorBag($wireModels);
}]);

$save = function () {
    $this->validate([
        'ca' => ['required'],
        'position' => ['required', 'string'],
        'name' => ['required', 'string'],
        'address' => ['required', 'string'],
        'phone' => ['required', 'numeric'],
        'email' => (!$this->editData ? ['nullable'] : ['required', 'email', 'unique:users,email']),
        'password' => (!$this->editData ? ['nullable'] : ['required', 'confirmed']),
    ]);

    try {
        if($this->editData) {
            $user = User::updateOrCreate([
               'id' => $this->user_id
            ], [
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'role' => 'staff',
                'status' => 'active',
                'email_verified_at' => now(),
                'remember_token' => \Illuminate\Support\Str::random(10),
            ])->assignRole('rt');
            $this->user_id = $user->id;
        }

        $na = NeighborhoodAssociation::updateOrCreate([
            'id' => $this->id
        ], [
            'user_id' => $this->user_id,
            'citizen_association_id' => $this->ca,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'position' => $this->position
        ]);
        unset($this->NAs);
        $this->dispatch('close-modal', id: 'neighborhood-association-modal');
        Toaster::success('Data berhasil disimpan!');
    } catch (Exception $e) {
        $this->dispatch('close-modal', id: 'neighborhood-association-modal');
        dd($e->getMessage());
        Toaster::error($e->getMessage());
    }
};

$updateAccount = function () {
    $this->validate([
        'email' => ['required', 'email', 'unique:users,email' . ($this->user_id ? ',' . $this->user_id : '')],
        'password' => ['required', 'confirmed'],
    ]);
    try {
        $user = User::find($this->user_id);
        $user->update([
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);
        $user->assignRole('rt');
        $this->dispatch('close-modal', id: 'neighborhood-association-account-modal');
        Toaster::success('Data berhasil disimpan!');
    } catch (Exception $e) {
        $this->dispatch('close-modal', id: 'neighborhood-association-account-modal');
        Toaster::error($e->getMessage());
    }
};

$resetAccount = function ($id) {
    $user = User::find($id);
    $this->user_id = $user->id;
    $this->email = $user->email;
    $this->dispatch('open-modal', id: 'neighborhood-association-account-modal');
};

$edit = function ($id) {
    $NA = NeighborhoodAssociation::find($id);
    $this->id = $NA->id;
    $this->user_id = $NA->user_id;
    $this->ca = $NA->citizen_association_id;
    $this->position = $NA->position;
    $this->name = $NA->name;
    $this->address = $NA->address;
    $this->phone = $NA->phone;
    $this->editData = false;
    $this->dispatch('open-modal', id: 'neighborhood-association-modal');
};

$destroy = function ($id) {
    try {
        $NA = NeighborhoodAssociation::find($id);
        $NA->delete();
        unset($this->NAs);
        Toaster::success('Data berhasil dihapus!');
    } catch (Exception $e) {
        Toaster::error($e->getMessage());
    }
}

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
            ['href' => '/', 'text' => 'Dashboard'],[
                'text' => 'Rukun Tetangga (RT)',
                'href' => route('neighborhood-association')
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
    <x-ui.modal id="neighborhood-association-modal">
        <x-slot name="header">
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Rukun Tetangga (RT)</h5>
        </x-slot>
        <x-slot name="content">
            <div class="grid-cols-2 sm:grid-cols-2 grid gap-2 mt-3">
                <x-ui.input-select label="Rukun Warga (RW)" wire:model="ca" id="ca" display_name="position" server :data="$this->citizen_associations"/>
                <x-ui.input type="text" name="position" label="Jabatan (Ketua)" wire:model="position" id="position"/>
            </div>
            <div class="grid-cols-1 sm:grid-cols-2 grid gap-2">
                <x-ui.input type="text" label="Nama" wire:model="name" id="name"/>
                <x-ui.input type="tel" label="Nomor Telepon" wire:model="phone" id="phone"/>
            </div>
            <x-ui.input type="text" label="Alamat" wire:model="address" id="address"/>
            <div x-data="{show: $wire.entangle('editData').live }">
                <div x-show="show" x-cloak>
                    <x-ui.devider class="w-full my-3 " label="Akun"/>
                    <x-ui.input type="email" label="Email" wire:model="email" id="email"/>
                    <div class="grid-cols-1 sm:grid-cols-2 grid gap-2 my-2">
                        <x-ui.input type="password" label="Password" wire:model="password" id="password"/>
                        <x-ui.input type="password" label="Konfirmasi Password" wire:model="password_confirmation" id="password_confirmation"/>
                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-ui.button size="sm" reset color="light" class="mr-2" wire:click="$dispatch('close-modal', { id: 'neighborhood-association-modal' })">
                Batal
            </x-ui.button>
            <x-ui.button size="sm" loading-only title="Simpan" submit color="blue" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" wire:target="save" wire:click="save" />
        </x-slot>
    </x-ui.modal>
    <x-ui.modal id="neighborhood-association-account-modal">
        <x-slot name="header">
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Reset Akun Ketua (RT)</h5>
        </x-slot>
        <x-slot name="content">
            <x-ui.input type="email" label="Email" wire:model="email" id="email-neighbor"/>
            <div class="grid-cols-1 sm:grid-cols-2 grid gap-2 my-2">
                <x-ui.input type="password" label="Password" wire:model="password" id="password-neighbor"/>
                <x-ui.input type="password" label="Konfirmasi Password" wire:model="password_confirmation" id="password_confirmation-neighbor"/>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-ui.button size="sm" reset color="light" class="mr-2" wire:click="$dispatch('close-modal', { id: 'neighborhood-association-account-modal' })">
                Batal
            </x-ui.button>
            <x-ui.button size="sm" loading-only title="Simpan" submit color="blue" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" wire:target="save" wire:click="updateAccount" />
        </x-slot>
    </x-ui.modal>

    <div class="grid-cols-1 lg:grid-cols-3 grid gap-2 ">
        <div class="col-span-3 ">
            <x-ui.card class="mt-2 w-full ">
                <x-slot name="header">
                    <div>
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Rukun Tetangga (RT)</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar ketua rukun tetangga</p>
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
                        <x-ui.button wire:click="$dispatch('open-modal', { id :'neighborhood-association-modal'})"
                                     size="xs" color="blue">
                            <span class="iconify duo-icons--add-circle w-4 h-4 me-1"></span>
                            Tambah
                        </x-ui.button>
                    </div>
                </x-slot>
                <x-ui.table thead="#, Rukun Warga, Jabatan, Nama, Nomor Telepon, Alamat" :action="true">
                    @if($this->NAs->count() > 0)
                        @foreach($this->NAs as $key => $NA)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $NA->citizen->position }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $NA->position }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $NA->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $NA->phone }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $NA->address }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    <x-ui.button size="xs" color="yellow" wire:click="resetAccount({{ $NA->user_id }})" >
                                        <span class="iconify carbon--user w-3 h-3 me-1"></span>
                                        Akun
                                    </x-ui.button>
                                    <x-ui.button size="xs" color="blue" wire:click="edit({{ $NA->id }})" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" wire:target="edit({{ $NA->id }})">
                                        <span class="iconify carbon--edit w-3 h-3 me-1"></span>
                                        Ubah
                                    </x-ui.button>
                                    <x-ui.button size="xs" color="red" wire:click="destroy({{ $NA->id }})"
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
                {{ $this->NAs->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
</div>
