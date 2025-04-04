<?php

use function Livewire\Volt\{state, layout, title, mount, computed, updated, on, usesFileUploads};
use App\Models\LetterType;
use App\Models\Letter;
use Masmerise\Toaster\Toaster;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Events\LetterProcessEvent;
use App\Notifications\NotificationLetterProcess;

layout('layouts.app');
title('Surat Masuk');
usesFileUploads();
state(['show' => 5, 'search' => ''])->url();
state(['id', 'response_letter_file']);
state(['name', 'gender', 'birth_place', 'birth_date', 'religion', 'employment','resident_identification_number', 'address', 'letter_number', 'description', 'letter_type', 'create_date', 'citizen', 'neighborhood']);
state(['edit' => false]);

mount(function () {
    if (auth()->user()->roles()->get()->first()->name != 'staff') {
        abort(404);
    }
});

on(['close-modal-reset' => function ($wireModels) {
    $this->reset(['id']);
    $this->reset($wireModels);
    $this->resetErrorBag($wireModels);
    $this->edit = false;
}, 'open-modal-loading' => function ($id) {
    $letter = Letter::find($id);
    if ($letter) {
        $this->letter_type = $letter->letter_type->name;
        $this->letter_number = $letter->letter_number;
        $this->description = $letter->description;
        $this->citizen = $letter->neighborhood->citizen;
        $this->neighborhood = $letter->neighborhood;
        $this->name =$letter->family_member->name;
        $this->gender = $letter->family_member->gender;
        $this->birth_place = $letter->family_member->birth_place;
        $this->birth_date = $letter->family_member->birth_date;
        $this->religion = $letter->family_member->religion->name;
        $this->employment = $letter->family_member->employment->name;
        $this->resident_identification_number = $letter->family_member->resident_identification_number;
        $this->address = $letter->family_member->family_card->address;
        $this->create_date = $letter->created_at;
        $this->dispatch('modal-loading-done', id: 'letter-modal');
    }else{
        Toaster::error('Surat tidak ditemukan');
    }
}]);

$letters = computed(function () {
    return Letter::where('submission_status', 1)->whereHas('letter_type', function ($query) {
        $query->where('name', 'like', '%' . $this->search . '%');
    })->latest()->paginate($this->show, pageName: 'staff-letters-page');
});

$store = function () {
    $validate = $this->validate([
        'response_letter_file' => ($this->id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:2048',
    ]);
    try {
        $letter = Letter::find($this->id);
        if ($letter) {
            if ($this->response_letter_file) {
                if ($letter->response_letter_file) {
                    \Storage::delete($letter->response_letter_file);
                }
                $response_letter_file = $this->response_letter_file->store('letters');
                $validate['response_letter_file'] = $response_letter_file;
            } else {
                $validate['response_letter_file'] = $letter->response_letter_file;
            }
        }
        $validate['status'] = 'reply';
        $letter->update($validate);
        $users = User::whereHas('neighborhoodAssociation', function ($query) use ($letter) {
            $query->where('id', $letter->neighborhood_association_id);
        })->orWhereHas('family_member', function ($query) use ($letter) {
            $query->where('id', $letter->family_member_id);
        })->get();
        foreach ($users as $user) {
            event(new LetterProcessEvent($user->id, $letter->letter_type->name, 'Staff Kelurahan', auth()->user()->name, auth()->user()->profile_path, 'Balasan Surat'));
        }
        Notification::send($users, new NotificationLetterProcess($letter->letter_type->name, 'Staff Kelurahan', auth()->user()->name, auth()->user()->profile_path, 'Balasan Surat'));
        $this->dispatch('pond-reset');
        $this->dispatch('close-modal', id: 'upload-letter-modal');
        Toaster::success('Surat berhasil ditambahkan');
    } catch (Exception $e) {
        $this->dispatch('close-modal', id: 'upload-letter-modal');
        Toaster::error('Surat gagal ditambahkan');
        Toaster::error($e->getMessage());
    }
};

$process = function ($id, $neighborhood_association_id, $family_member_id, $letter_type) {
    $users = User::whereHas('neighborhoodAssociation', function ($query) use ($neighborhood_association_id) {
        $query->where('id', $neighborhood_association_id);
    })->orWhereHas('family_member', function ($query) use ($family_member_id) {
        $query->where('id', $family_member_id);
    })->get();
    foreach ($users as $user) {
        event(new LetterProcessEvent($user->id, $letter_type, 'Staff Kelurahan', auth()->user()->name, auth()->user()->profile_path, 'Surat diproses'));
    }
    Notification::send($users, new NotificationLetterProcess($letter_type, 'Staff Kelurahan', auth()->user()->name, auth()->user()->profile_path, 'Surat diproses'));
    $letter = Letter::find($id)->update(['status' => 'process']);
    Toaster::success('Surat diproses');
};

$reply = function ($id) {
    $letter = Letter::find($id);
    $this->id = $letter->id;
    $this->edit = true;
    $this->dispatch('open-modal', id: 'upload-letter-modal');
}

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
        ['href' => '/', 'text' => 'Dashboard'],
        ['text' => 'Surat Masuk'],
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
    <x-ui.modal id="upload-letter-modal">
        <x-slot name="header">
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Unggah Surat Balasan</h5>
        </x-slot>
        <x-slot name="content">
            <div x-data="{ show : @entangle('edit') }" class="mt-3">
                <x-ui.alert x-show="show"
                            x-cloak
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            color="warning"
                            value="Sebelum mengunggah file, diharapkan untuk memeriksa kembali file yang akan diunggah guna meminimalkan kemungkinan kesalahan. Perlu diketahui, file yang sudah diunggah tidak dapat diubah."/>
            </div>
            <x-ui.filepond id="response_letter_file" wire:model="response_letter_file" maxFileSize="2048" label="Surat Balasan"/>
        </x-slot>
        <x-slot name="footer">
            <x-ui.button size="sm" reset color="light" class="mr-2"
                         wire:click="$dispatch('close-modal', { id: 'upload-letter-modal' })">
                Batal
            </x-ui.button>
            <x-ui.button size="sm" loading-only title="Simpan" submit color="blue" wire:loading.attr="disabled"
                         wire:loading.class="cursor-not-allowed" wire:target="store" wire:click="store"/>
        </x-slot>
    </x-ui.modal>
    <x-ui.modal id="letter-modal" size="xl">
        <x-slot name="header">
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Surat yang diajukan</h5>
        </x-slot>
        <x-slot name="content">
            <div id="print-area">
                <div class="bg-white p-5">
                    <table class="border-collapse w-full">
                        <tbody>
                        <tr>
                            <th class="flex justify-end items-center">
                                <img class="w-20 mt-1" src="{{ asset('img/logo.png') }}" alt="Surat">
                            </th>
                            <th class="">
                                <h5 class="text-2xl font-bold text-gray-900 tracking-[.09em]">PEMERINTAH DISTRIK MERAUKE</h5>
                                <h5 class="text-2xl font-bold text-gray-900 tracking-[.09em]">KELURAHAN SERINGGU JAYA</h5>
                                <h5 class="text-2xl font-bold text-gray-900 tracking-[.09em]">{{ $this->neighborhood->position ?? '' }}/{{ $this->citizen->position ?? '' }}</h5>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                    <hr class="mt-3 border-gray-200 dark:border-gray-700" />
                    <hr class="mt-1 mb-6 border-gray-200 border-2 dark:border-gray-700" />
                    <table class="border-collapse w-full mb-5">
                        <tbody>
                        <tr>
                            <td class="text-start">
                                <h5 class="text-xl font-bold text-gray-900 tracking-[.09em] underline">SURAT PENGANTAR</h5>
                            </td>
                            <td class="text-center">
                                <h5 class="text-lg font-medium text-gray-900 tracking-[.09em] ">Yth. Lurah Seringgu Jaya</h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start">
                                <h5 class="text-lg font-medium text-gray-900 tracking-[.09em] ">Nomor : {{ $this->letter_number }}</h5>
                            </td>
                            <td class="">
                                <h5 class="text-lg font-medium text-gray-900 tracking-[.09em] ml-32">di -</h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start">
                                &nbsp;
                            </td>
                            <td class="text-center">
                                <h5 class="text-lg font-medium text-gray-900 tracking-[.09em] underline">Merauke</h5>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="mb-5">
                        <p class="text-start text-lg text-gray-900">
                            Dengan hormat,
                        </p>
                        <p class="indent-8 text-lg text-gray-900">
                            Kami yang bertanda tangan di bawah ini menerangkan bahwa :
                        </p>
                    </div>
                    <table class="w-full border-collapse text-lg text-gray-900 mb-5">
                        <tr>
                            <td class="py-2 w-1/3">Nama Lengkap</td>
                            <td class="w-8 text-center">:</td>
                            <td class=" w-2/3"> {{ $this->name }}</td>
                        </tr>
                        <tr>
                            <td class="py-2">Jenis Kelamin</td>
                            <td class="text-center">:</td>
                            <td class=""> {{ $this->gender == 'M' ? 'Laki-Laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <td class="py-2">Tempat Tanggal Lahir</td>
                            <td class="text-center">:</td>
                            <td class=""> {{ $this->birth_place }},{{ Carbon::parse($this->birth_date)->locale('id')->isoFormat('D MMMM Y') }}</td>
                        </tr>
                        <tr>
                            <td class="py-2">Agama</td>
                            <td class="text-center">:</td>
                            <td class=""> {{ $this->religion }}</td>
                        </tr>
                        <tr>
                            <td class="py-2">Pekerjaan</td>
                            <td class="text-center">:</td>
                            <td class=""> {{ $this->employment }}</td>
                        </tr>
                        <tr>
                            <td class="py-2">Nomor KTP</td>
                            <td class="text-center">:</td>
                            <td class=""> {{  $this->resident_identification_number }}</td>
                        </tr>
                        <tr>
                            <td class="py-2">Alamat</td>
                            <td class="text-center">:</td>
                            <td class=""> {{ $this->address }}</td>
                        </tr>
                    </table>
                    <div class="mb-8">
                        <p class="indent-8 text-lg text-gray-900">
                            Yang bersangkutan adalah benar-benar warga kami yang berdomisili sesuai alamat diatas, dan bermaksud mengurus <strong>{{ $this->letter_type }}</strong> {{ $this->description }}
                        </p>
                        <p class="indent-8 text-lg text-gray-900 mt-2">
                            Demikian atas kerja samanya kami ucapkan terima kasih.
                        </p>
                    </div>
                    <table class="border-collapse w-full mb-5">
                        <tbody>
                        <tr>
                            <td class="text-start">
                                <h5 class="text-xl font-bold text-gray-900 tracking-[.09em]">&nbsp;</h5>
                            </td>
                            <td class="text-center">
                                <h5 class="text-lg font-medium text-gray-900">Merauke, {{ Carbon::parse($this->create_date)->locale('id')->isoFormat('D MMMM Y') }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <h5 class="text-lg font-medium text-gray-900">Mengatahui,</h5>
                            </td>
                            <td class="text-center">
                                <h5 class="text-lg font-medium text-gray-900">&nbsp;</h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <h5 class="text-lg font-medium text-gray-900">KETUA {{ $this->citizen->position ?? '' }}</h5>
                            </td>
                            <td class="text-center">
                                <h5 class="text-lg font-medium text-gray-900">KETUA {{  $this->neighborhood->position ?? '' }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                &nbsp;
                                <br />
                                &nbsp;
                            </td>
                            <td class="text-center">
                                &nbsp;
                                <br />
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <h5 class="text-lg font-medium text-gray-900 underline tracking-[.09em]">{{ $this->citizen->name ?? '' }}</h5>
                            </td>
                            <td class="text-center">
                                <h5 class="text-lg font-medium text-gray-900 tracking-[.09em] underline">{{  $this->neighborhood->name ?? '' }}</h5>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-ui.button size="sm" reset color="light" class="mr-2" wire:click="$dispatch('close-modal', { id: 'letter-modal' })" title="Tutup"/>
            <x-ui.button size="sm" title="Cetak" color="blue" wire:click="dispatch('print')" />
        </x-slot>
    </x-ui.modal>

    <div class="grid-cols-1 lg:grid-cols-3 grid gap-2 ">
        <div class="col-span-3 ">
            <x-ui.card class="mt-2 w-full ">
                <x-slot name="header" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                    <div>
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Surat Masuk</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar surat pengajuan warga.</p>
                    </div>
                </x-slot>
                <x-slot name="sideHeader">
                    <div class="flex gap-2 justify-end items-center">
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
                <x-ui.table thead="#, Jenis Surat, Surat, Kartu Keluarga, Kartu Tanda Penduduk, Surat Balasan"
                            :action="true" wire:poll.keep-alive>
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
                                    <svg wire:click="$dispatch('open-modal-loading', { id : {{  $letter->id }} })" class="w-10 h-10 object-cover" xmlns="http://www.w3.org/2000/svg"
                                         width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor" fill-rule="evenodd"
                                              d="M14 22h-4c-3.771 0-5.657 0-6.828-1.172S2 17.771 2 14v-4c0-3.771 0-5.657 1.172-6.828S6.239 2 10.03 2c.606 0 1.091 0 1.5.017q-.02.12-.02.244l-.01 2.834c0 1.097 0 2.067.105 2.848c.114.847.375 1.694 1.067 2.386c.69.69 1.538.952 2.385 1.066c.781.105 1.751.105 2.848.105h4.052c.043.534.043 1.19.043 2.063V14c0 3.771 0 5.657-1.172 6.828S17.771 22 14 22"
                                              clip-rule="evenodd" opacity="0.45"/>
                                        <path fill="currentColor"
                                              d="m11.51 2.26l-.01 2.835c0 1.097 0 2.066.105 2.848c.114.847.375 1.694 1.067 2.385c.69.691 1.538.953 2.385 1.067c.781.105 1.751.105 2.848.105h4.052q.02.232.028.5H22c0-.268 0-.402-.01-.56a5.3 5.3 0 0 0-.958-2.641c-.094-.128-.158-.204-.285-.357C19.954 7.494 18.91 6.312 18 5.5c-.81-.724-1.921-1.515-2.89-2.161c-.832-.556-1.248-.834-1.819-1.04a6 6 0 0 0-.506-.154c-.384-.095-.758-.128-1.285-.14z"/>
                                    </svg>
                                </td>
                                <td class="px-6 py-4">
                                    @if($letter->family_card_file != null)
                                        <a href="/storage/{{ $letter->family_card_file }}" target="_blank"
                                           class="underline text-grey-200 dark:hover:text-white hover:text-gray-300">
                                            <svg class="w-10 h-10 object-cover" xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                      d="M19.999 4h-16c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2m-13.5 3a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m5.5 10h-7l4-5l1.5 2l3-4l5.5 7z"/>
                                            </svg>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($letter->resident_identification_card_file != null)
                                        <a href="/storage/{{ $letter->resident_identification_card_file }}"
                                           target="_blank" class="underline text-grey-200 dark:hover:text-white hover:text-gray-300">
                                            <svg class="w-10 h-10 object-cover" xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                      d="M19.999 4h-16c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2m-13.5 3a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m5.5 10h-7l4-5l1.5 2l3-4l5.5 7z"/>
                                            </svg>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($letter->response_letter_file != null)
                                        <a href="/storage/{{ $letter->response_letter_file }}" target="_blank"
                                           class="underline text-grey-200 dark:hover:text-white hover:text-gray-300">
                                            <svg class="w-10 h-10 object-cover" xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                      d="M19.999 4h-16c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2m-13.5 3a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m5.5 10h-7l4-5l1.5 2l3-4l5.5 7z"/>
                                            </svg>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    @if($letter->status == 'apply')
                                        <x-ui.button size="xs" color="blue" wire:click="process({{ $letter->id }}, {{ $letter->neighborhood_association_id }}, {{ $letter->family_member_id }}, '{{ $letter->letter_type->name }}')">
                                            <svg class="w-3 h-3 me-1" xmlns="http://www.w3.org/2000/svg" width="24"
                                                 height="24" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                      d="M8.625 8.5h-4.5a1 1 0 0 1-1-1V3a1 1 0 0 1 2 0v3.5h3.5a1 1 0 0 1 0 2"/>
                                                <path fill="currentColor"
                                                      d="M21 13a1 1 0 0 1-1-1A7.995 7.995 0 0 0 5.08 8.001a1 1 0 0 1-1.731-1.002A9.995 9.995 0 0 1 22 12a1 1 0 0 1-1 1m-1.125 9a1 1 0 0 1-1-1v-3.5h-3.5a1 1 0 0 1 0-2h4.5a1 1 0 0 1 1 1V21a1 1 0 0 1-1 1"/>
                                                <path fill="currentColor"
                                                      d="M12 22A10.01 10.01 0 0 1 2 12a1 1 0 0 1 2 0a7.995 7.995 0 0 0 14.92 3.999a1 1 0 0 1 1.731 1.002A10.03 10.03 0 0 1 12 22"/>
                                            </svg>
                                            Proses
                                        </x-ui.button>
                                    @elseif($letter->status == 'process')
                                        <x-ui.button size="xs" color="yellow" wire:click="reply({{ $letter->id }})">
                                            <span class="iconify duo-icons--add-circle w-3 h-3 me-1"></span>
                                            Unggah Surat
                                        </x-ui.button>
                                    @endif
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
    @pushonce('scripts-bottom')
        <script>
            document.addEventListener('livewire:navigated', () => {
                Livewire.on('print', () => {
                    let printContents = document.getElementById('print-area').innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    window.location.reload();
                });
            }, { once: true });
        </script>
    @endpushonce
</div>


