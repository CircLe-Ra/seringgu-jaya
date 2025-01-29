<?php

use function Livewire\Volt\{state, layout, title, mount, computed, updated, on, usesFileUploads};
use App\Models\News;
use Masmerise\Toaster\Toaster;
use Carbon\Carbon;

layout('layouts.app');
title('Berita Terhapus');
state(['show' => 5, 'search' => ''])->url();

mount(function () {
});

$news = computed(function () {
    return News::onlyTrashed()->latest()->paginate(5, pageName: 'trash-news-page');
});

$destroy = function ($id) {
    try {
        News::find($id)->forceDelete();
        Toaster::success('Berita berhasil dihapus');
    } catch (\Throwable $th) {
        Toaster::error('Berita gagal dihapus');
    }
};

$restore = function ($id) {
    try {
        News::withTrashed()->find($id)->restore();
        Toaster::success('Berita berhasil dikembalikan');
    } catch (\Throwable $th) {
        Toaster::error('Berita gagal dikembalikan');
    }
};

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
        ['href' => route('dashboard'), 'text' => 'Dashboard'],
        ['text' => 'Portal'],
        ['text' => 'Informasi/Berita', 'href' => route('admin.portal.news')],
        ['text' => 'Tempat Sampah']
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
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Informasi/Berita Terhapus</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar Informasi/Berita Kelurahan yang telah dihapus.</p>
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
                <x-ui.table thead="#, Judul, Slug, Konten, Status, Tanggal Terbit" :action="true">
                    @if($this->news->count() > 0)
                        @foreach($this->news as $key => $news)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $news->title }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $news->slug }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ str()->limit(strip_tags($news->content),40,'...') }}
                                </td>
                                <td class="px-6 py-4">
                                   {{ $news->published == 'private' ? 'Privat' : 'Diterbitkan' }}
                                </td>
                                <td class="px-6 py-4">
                                   {{ $news->published_at == null ? '-' : Carbon::parse($news->published_at)->translatedFormat('d F Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    <x-ui.button size="xs" color="light" wire:click="restore({{ $news->id }})">
                                        <svg class="w-4 h-4 me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M13 3a9 9 0 0 0-9 9H1l4 3.99L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.25 2.52l.77-1.28l-3.52-2.09V8z" />
                                        </svg>
                                        Kembalikan
                                    </x-ui.button>
                                    <x-ui.button size="xs" color="red" wire:click="destroy({{ $news->id }})" wire:confirm="Anda yakin ingin menghapus data ini? Tindakan ini akan menghapus data secara permanen!">
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
                {{ $this->news->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
</div>
