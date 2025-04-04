<?php

use function Livewire\Volt\{state, layout, title, computed, on};
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Masmerise\Toaster\Toaster;

layout('layouts.app');
title(__('Role Management'));

state(['role_name' => '', 'idData']);
state(['showing' => 5])->url();
state(['search' => null])->url();

$roles = computed(function () {
    return Role::where('name', 'like', '%' . $this->search . '%')
        ->latest()->paginate($this->showing, pageName: 'role-page');
});

$store = function () {
    $this->validate([
        'role_name' => 'required|unique:roles,name',
    ]);

    try {
        if ($this->idData) {
            $role = Role::find($this->idData);
            $role->update(['name' => $this->role_name]);
            unset($this->roles);
            $this->reset(['role_name', 'idData']);
            $this->dispatch('refresh');
            Toaster::success(__('Role has been updated'));
        } else {
            $role = Role::create(['name' => $this->role_name]);
            unset($this->roles);
            $this->reset(['role_name', 'idData']);
            $this->dispatch('refresh');
            Toaster::success(__('Role has been created'));
        }
    } catch (\Throwable $th) {
        $this->dispatch('toast', message: __('Role could not be created'), data: ['position' => 'top-center', 'type' => 'error']);
        Toaster::error(__('Role could not be created'));
    }
};

$edit = function ($id) {
    $role = Role::find($id);
    $this->idData = $id;
    $this->role_name = $role->name;
};

$destroy = function ($id) {
    try {
        Role::destroy($id);
        unset($this->roles);
        $this->dispatch('refresh');
        Toaster::success(__('Role has been deleted'));
    } catch (\Throwable $th) {
        Toaster::error(__('Role could not be deleted'));
    }
};

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
                ['href' => '/', 'text' => 'Dashboard'],
                ['text' => 'Master Data'],
                ['text' => 'Peran Pengguna']
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Form Input Role -->
        <div class="w-full lg:col-span-1">
            <x-ui.card class="mt-2">
                <x-slot name="header">
                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Tambah Role</h5>
                </x-slot>
                <form wire:submit="store" class="mx-auto">
                    <x-ui.input id="role_name" name="role_name" wire:model="role_name" main-class="mb-2" label="Nama Role" placeholder="Masukan Nama Role"/>
                    <div class="flex justify-end space-x-2">
                        <x-ui.button type="reset" color="light">Batal</x-ui.button>
                        <x-ui.button submit color="blue" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" wire:target="store">Simpan</x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>

        <!-- Table Role Data -->
        <div class="w-full lg:col-span-2">
            <x-ui.card class="mt-2 w-full">
                <x-slot name="header">
                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Daftar Role</h5>
                </x-slot>

                <x-slot name="sideHeader">
                    <x-ui.input-select id="show" name="show" wire:model.live="show" size="xs">
                        <option value="">Semua</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </x-ui.input-select>
                </x-slot>

                <x-ui.table thead="#, Nama Role, Dibuat Pada">
                    @if($this->roles->count() > 0)
                        @foreach($this->roles as $role)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">{{ $role->name }}</td>
                                <td class="px-6 py-4">{{ $role->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4">
                                    <x-ui.button color="yellow" size="xs" wire:click="edit({{ $role->id }})">
                                        <span class="iconify carbon--edit w-3 h-3 me-1"></span>
                                        Edit
                                    </x-ui.button>
                                    <x-ui.button color="red" size="xs" wire:click="destroy({{$role->id}})" wire:confirm="Anda yakin akan menghapus data ini?">
                                        <span class="iconify carbon--delete w-3 h-3 me-1"></span>
                                        Hapus
                                    </x-ui.button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td colspan="4" class="px-6 py-4 text-center">Tidak ada data</td>
                        </tr>
                    @endif
                </x-ui.table>
                {{ $this->roles->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
</div>

