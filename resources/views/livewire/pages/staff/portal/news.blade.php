<?php

use function Livewire\Volt\{state, layout, title, mount, computed, updated, on, usesFileUploads};
use App\Models\News;
use Masmerise\Toaster\Toaster;
use Carbon\Carbon;

layout('layouts.app');
title('Berita');
state(['show' => 5, 'search' => ''])->url();

mount(function () {
});

$news = computed(function () {
    return News::where('title', 'like', '%' . $this->search . '%')
        ->latest()->paginate($this->show, pageName: 'news-page');
});

$destroy = function ($id) {
    try {
        News::find($id)->delete();
        Toaster::success('Berita berhasil dihapus');
    } catch (\Throwable $th) {
        Toaster::error('Berita gagal dihapus');
    }
}

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
        ['href' => route('dashboard'), 'text' => 'Dashboard'],
        ['text' => 'Portal'],
        ['text' => 'Informasi/Berita'],
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
                <x-slot name="header" class="grid grid-cols-1 xl:grid-cols-2 gap-2">
                    <div>
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Informasi/Berita</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar Informasi/Berita Kelurahan.</p>
                    </div>
                </x-slot>
                <x-slot name="sideHeader">
                    <div class="flex gap-2 lg:justify-end items-center justify-center">
                        <x-ui.input-select id="show" name="show" wire:model.live="show" size="xs" class="w-full">
                            <option value="">Semua</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </x-ui.input-select>
                        <x-ui.button wire:navigate tag="link" href="{{ route('admin.portal.trash-news') }}" size="xs" color="red">
                            <svg class="w-4 h-4 me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M3 6.524c0-.395.327-.714.73-.714h4.788c.006-.842.098-1.995.932-2.793A3.68 3.68 0 0 1 12 2a3.68 3.68 0 0 1 2.55 1.017c.834.798.926 1.951.932 2.793h4.788c.403 0 .73.32.73.714a.72.72 0 0 1-.73.714H3.73A.72.72 0 0 1 3 6.524" />
                                <path fill="currentColor" d="M11.596 22h.808c2.783 0 4.174 0 5.08-.886c.904-.886.996-2.339 1.181-5.245l.267-4.188c.1-1.577.15-2.366-.303-2.865c-.454-.5-1.22-.5-2.753-.5H8.124c-1.533 0-2.3 0-2.753.5s-.404 1.288-.303 2.865l.267 4.188c.185 2.906.277 4.36 1.182 5.245c.905.886 2.296.886 5.079.886" opacity="0.5" />
                                <path fill="currentColor" fill-rule="evenodd" d="M9.425 11.482c.413-.044.78.273.821.707l.5 5.263c.041.433-.26.82-.671.864c-.412.043-.78-.273-.821-.707l-.5-5.263c-.041-.434.26-.821.671-.864m5.15 0c.412.043.713.43.671.864l-.5 5.263c-.04.434-.408.75-.82.707c-.413-.044-.713-.43-.672-.864l.5-5.264c.041-.433.409-.75.82-.707" clip-rule="evenodd" />
                            </svg>
                            Tempat Sampah
                        </x-ui.button>
                        <x-ui.button wire:navigate tag="link" href="{{ route('admin.portal.add-news') }}" size="xs" color="blue">
                            <span class="iconify duo-icons--add-circle w-4 h-4 me-1"></span>
                            Tambah
                        </x-ui.button>
                    </div>
                </x-slot>
                <x-ui.table thead="#, Gambar, Judul, Slug, Konten, Status, Tanggal Terbit" :action="true">
                    @if($this->news->count() > 0)
                        @foreach($this->news as $key => $news)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($news->image != null)
                                        <img class="w-10 h-10 object-cover" src="{{ asset('storage/'.$news->image) }}" alt="{{ $news->title }}">
                                    @else
                                        <svg class="w-10 h-10 object-cover"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19.999 4h-16c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2m-13.5 3a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m5.5 10h-7l4-5l1.5 2l3-4l5.5 7z" />
                                        </svg>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ str()->limit($news->title,20,'...') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ str()->limit($news->slug,20,'...') }}
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
                                    <x-ui.button size="xs" color="yellow" tag="link" wire:navigate href="{{ route('admin.portal.update-news', $news->id) }}">
                                        <span class="iconify carbon--edit w-3 h-3 me-1"></span>
                                        Ubah
                                    </x-ui.button>
                                    <x-ui.button size="xs" color="red" wire:click="destroy({{ $news->id }})" wire:confirm="Anda yakin ingin menghapus data ini?">
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
                {{ $this->news->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
</div>
