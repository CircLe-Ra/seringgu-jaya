<?php
use function Livewire\Volt\{state, layout, title, mount, computed, updated, on, usesFileUploads};
use App\Models\LetterType;
use App\Models\Letter;
use Masmerise\Toaster\Toaster;
use Carbon\Carbon;

layout('layouts.app');
title('Surat');
usesFileUploads();
state(['show' => 5, 'search' => ''])->url();
state(['id', 'letter_type_id','letter_file', 'family_card_file', 'resident_identification_card_file']);
state(['letter_file_current', 'family_card_file_current', 'resident_identification_card_file_current']);

mount(function () {
    if(auth()->user()->roles()->get()->first()->name != 'rt'){
        abort(404);
    }
});

$letters = computed(function () {
    return Letter::onlyTrashed()->where('neighborhood_association_id', auth()->user()->neighborhoodAssociation->id)
        ->where('letter_type_id', 'like', '%' . $this->search . '%')
        ->latest()->paginate($this->show, pageName: 'letters-page');
});

$destroy = function ($id) {
    try {
        Letter::find($id)->forceDelete();
        Toaster::success('Surat berhasil dihapus');
    } catch (\Throwable $th) {
        Toaster::error('Surat gagal dihapus');
    }
};

$restore = function ($id) {
    try {
        Letter::withTrashed()->find($id)->restore();
        Toaster::success('Surat berhasil dikembalikan');
    } catch (\Throwable $th) {
        Toaster::error('Surat gagal dikembalikan');
    }
};

$editData = function ($id) {
    $letter = Letter::find($id);
    $this->id = $letter->id;
    $this->letter_type_id = $letter->letter_type_id;
    $this->letter_file_current = $letter->letter_file;
    $this->family_card_file_current = $letter->family_card_file;
    $this->resident_identification_card_file_current = $letter->resident_identification_card_file;
    $this->edit = true;
    $this->dispatch('open-modal', id: 'upload-letter-modal');
};

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
        ['href' => route('dashboard'), 'text' => 'Dashboard'],
        ['text' => 'Surat', 'href' => route('neighborhood-association.letter')],
        ['text' => 'Tempat Sampah'],
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
        <div class="col-span-3 ">
            <x-ui.card class="mt-2 w-full ">
                <x-slot name="header">
                    <div>
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Surat Terhapus</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar surat yang telah dihapus.</p>
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
                    </div>
                </x-slot>
                <x-ui.table thead="#, Jenis Surat, Surat, Kartu Keluarga, Kartu Tanda Penduduk" :action="true">
                    @if($this->letters->count() > 0)
                        @foreach($this->letters as $key => $letter)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $letter->letter_type->name }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($letter->letter_file != null)
                                        <a href="/storage/{{ $letter->letter_file }}" target="_blank" class="underline text-blue-700 hover:text-blue-500">Lihat</a>
                                    @else
                                        <svg class="w-10 h-10 object-cover"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19.999 4h-16c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2m-13.5 3a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m5.5 10h-7l4-5l1.5 2l3-4l5.5 7z" />
                                        </svg>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($letter->family_card_file != null)
                                        <a href="/storage/{{ $letter->family_card_file }}" target="_blank" class="underline text-blue-700 hover:text-blue-500">Lihat</a>
                                    @else
                                        <svg class="w-10 h-10 object-cover"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19.999 4h-16c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2m-13.5 3a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m5.5 10h-7l4-5l1.5 2l3-4l5.5 7z" />
                                        </svg>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($letter->resident_identification_card_file != null)
                                        <a href="/storage/{{ $letter->resident_identification_card_file }}" target="_blank" class="underline text-blue-700 hover:text-blue-500">Lihat</a>
                                    @else
                                        <svg class="w-10 h-10 object-cover"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19.999 4h-16c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2m-13.5 3a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m5.5 10h-7l4-5l1.5 2l3-4l5.5 7z" />
                                        </svg>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    <x-ui.button size="xs" color="light" wire:click="restore({{ $letter->id }})">
                                        <svg class="w-4 h-4 me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M13 3a9 9 0 0 0-9 9H1l4 3.99L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.25 2.52l.77-1.28l-3.52-2.09V8z" />
                                        </svg>
                                        Kembalikan
                                    </x-ui.button>
                                    <x-ui.button size="xs" color="red" wire:click="destroy({{ $letter->id }})" wire:confirm="Anda yakin ingin menghapus data ini? Tindakan ini akan menghapus data secara permanen!">
                                        <span class="iconify carbon--delete w-4 h-4 me-1"></span>
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
                {{ $this->letters->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
</div>
