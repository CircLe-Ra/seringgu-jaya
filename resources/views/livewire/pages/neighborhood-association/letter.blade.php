<?php

use function Livewire\Volt\{state, layout, title, mount, computed, updated, on, usesFileUploads};
use App\Models\LetterType;
use App\Models\Letter;
use Masmerise\Toaster\Toaster;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotificationLetterApply;
use App\Events\LetterApplyEvent;

layout('layouts.app');
title('Surat');
usesFileUploads();
state(['show' => 5, 'search' => ''])->url();
state(['id', 'letter_type_id', 'letter_file', 'family_card_file', 'resident_identification_card_file', 'family_member_id']);
state(['letter_file_current', 'family_card_file_current', 'resident_identification_card_file_current']);
state(['edit' => false]);

mount(function () {
    if (auth()->user()->roles()->get()->first()->name != 'rt') {
        abort(404);
    }
});

on(['close-modal-reset' => function ($wireModels) {
    $this->reset(['id']);
    $this->reset($wireModels);
    $this->resetErrorBag($wireModels);
    $this->edit = false;
}]);

$letter_types = computed(function () {
    return LetterType::all();
});

$letters = computed(function () {
    return Letter::where('neighborhood_association_id', auth()->user()->neighborhoodAssociation->id)
        ->whereHas('letter_type', function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->latest()->paginate($this->show, pageName: 'letters-page');
});

$store = function () {
    $validate = $this->validate([
        'letter_type_id' => 'required',
        'letter_file' => ($this->id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx|max:2048',
        'family_card_file' => ($this->id ? 'nullable' : 'required') . '|mimes:pdf,png,jpg,jpeg|max:2048',
        'resident_identification_card_file' => ($this->id ? 'nullable' : 'required') . '|mimes:pdf,png,jpg,jpeg|max:2048',
    ]);
    try {
        if (!empty($this->id)) {
            $data = Letter::find($this->id);
            if ($data) {
                if ($this->letter_file) {
                    if ($data->letter_file) {
                        \Storage::delete($data->letter_file);
                    }
                    $letter_file = $this->letter_file->store('letters');
                    $validate['letter_file'] = $letter_file;
                } else {
                    $validate['letter_file'] = $data->letter_file;
                }

                if ($this->family_card_file) {
                    if ($data->family_card_file) {
                        \Storage::delete($data->family_card_file);
                    }
                    $family_card_file = $this->family_card_file->store('letters');
                    $validate['family_card_file'] = $family_card_file;
                } else {
                    $validate['family_card_file'] = $data->family_card_file;
                }

                if ($this->resident_identification_card_file) {
                    if ($data->resident_identification_card_file) {
                        \Storage::delete($data->resident_identification_card_file);
                    }
                    $resident_identification_card_file = $this->resident_identification_card_file->store('letters');
                    $validate['resident_identification_card_file'] = $resident_identification_card_file;
                } else {
                    $validate['resident_identification_card_file'] = $data->resident_identification_card_file;
                }
            }
        } else {
            $letter_file = $this->letter_file->store('letters');
            $family_card_file = $this->family_card_file->store('letters');
            $resident_identification_card_file = $this->resident_identification_card_file->store('letters');
            $validate['letter_file'] = $letter_file;
            $validate['family_card_file'] = $family_card_file;
            $validate['resident_identification_card_file'] = $resident_identification_card_file;
        }

        $validate['neighborhood_association_id'] = auth()->user()->neighborhoodAssociation->id;
        $letter = Letter::updateOrCreate(['id' => $this->id], $validate);
        $this->dispatch('close-modal', id: 'upload-letter-modal');
        Toaster::success('Surat berhasil ditambahkan');
    } catch (Exception $e) {
        $this->dispatch('close-modal', id: 'upload-letter-modal');
        Toaster::error('Surat gagal ditambahkan');
        Toaster::error($e->getMessage());
    }
};

$destroy = function ($id) {
    try {
        Letter::find($id)->delete();
        Toaster::success('Surat berhasil dihapus');
    } catch (\Throwable $th) {
        Toaster::error('Surat gagal dihapus');
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

$apply = function ($id) {
    try {
        $users = User::role('staff')->get();
        $letter = Letter::find($id);
        Notification::send($users, new NotificationLetterApply($letter->letter_type->name, auth()->user()->neighborhoodAssociation->position, auth()->user()->neighborhoodAssociation->name, auth()->user()->profile_path, 'Permohonan Surat'));
        event(new LetterApplyEvent($letter->letter_type->name, auth()->user()->neighborhoodAssociation->position, auth()->user()->neighborhoodAssociation->name, auth()->user()->profile_path, 'Permohonan Surat'));
        $letter->submission_status = 1;
        $letter->status = 'apply';
        $letter->save();
        Toaster::success('Surat berhasil diajukan');
    } catch (Exception $e) {
        Toaster::error('Surat gagal diajukan');
        dd($e->getMessage());
    }
}
?>

<div>
    <x-ui.breadcrumbs :crumbs="[
        ['href' => route('dashboard'), 'text' => 'Dashboard'],
        ['text' => 'Surat'],
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
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Pengajuan Surat</h5>
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
                            value="Data sudah diunggah sebelumnya. Jika Anda ingin melakukan perubahan, Anda dapat langsung mengunggah file yang baru. File lama akan ditimpa dengan file yang baru."/>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <x-ui.input-select server :data="$this->letter_types" label="Jenis Surat" wire:model="letter_type_id"
                                   id="letter_type_id"/>
            </div>
            <x-ui.filepond id="letter_file" wire:model="letter_file" label="Surat"/>
            <x-ui.filepond id="family_card_file" wire:model="family_card_file" label="Kartu Keluarga"/>
            <x-ui.filepond id="resident_identification_card_file" wire:model="resident_identification_card_file"
                           label="Kartu Tanda Penduduk"/>
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

    <div class="grid-cols-1 lg:grid-cols-3 grid gap-2 ">
        <div class="col-span-3 ">
            <x-ui.card class="mt-2 w-full ">
                <x-slot name="header" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                    <div>
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Surat</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar surat yang telah diajukan.</p>
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
                        <x-ui.button wire:navigate tag="link"
                                     href="{{ route('neighborhood-association.trash-letter') }}" size="xs" color="red"
                                     class="sm:flex-auto xl:flex-none">
                            <svg class="w-4 h-4 me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                      d="M3 6.524c0-.395.327-.714.73-.714h4.788c.006-.842.098-1.995.932-2.793A3.68 3.68 0 0 1 12 2a3.68 3.68 0 0 1 2.55 1.017c.834.798.926 1.951.932 2.793h4.788c.403 0 .73.32.73.714a.72.72 0 0 1-.73.714H3.73A.72.72 0 0 1 3 6.524"/>
                                <path fill="currentColor"
                                      d="M11.596 22h.808c2.783 0 4.174 0 5.08-.886c.904-.886.996-2.339 1.181-5.245l.267-4.188c.1-1.577.15-2.366-.303-2.865c-.454-.5-1.22-.5-2.753-.5H8.124c-1.533 0-2.3 0-2.753.5s-.404 1.288-.303 2.865l.267 4.188c.185 2.906.277 4.36 1.182 5.245c.905.886 2.296.886 5.079.886"
                                      opacity="0.5"/>
                                <path fill="currentColor" fill-rule="evenodd"
                                      d="M9.425 11.482c.413-.044.78.273.821.707l.5 5.263c.041.433-.26.82-.671.864c-.412.043-.78-.273-.821-.707l-.5-5.263c-.041-.434.26-.821.671-.864m5.15 0c.412.043.713.43.671.864l-.5 5.263c-.04.434-.408.75-.82.707c-.413-.044-.713-.43-.672-.864l.5-5.264c.041-.433.409-.75.82-.707"
                                      clip-rule="evenodd"/>
                            </svg>
                            Tempat Sampah
                        </x-ui.button>
                    </div>
                </x-slot>
                <x-ui.table thead="#, Jenis Surat, Surat, Kartu Keluarga, Kartu Tanda Penduduk, Surat Balasan, Status"
                            :action="true">
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
                                        <a href="/storage/{{ $letter->letter_file }}" target="_blank"
                                           class="underline text-grey-200 hover:text-white">
                                            <svg class="w-10 h-10 object-cover" xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24" viewBox="0 0 24 24">
                                                <path fill="currentColor" fill-rule="evenodd"
                                                      d="M14 22h-4c-3.771 0-5.657 0-6.828-1.172S2 17.771 2 14v-4c0-3.771 0-5.657 1.172-6.828S6.239 2 10.03 2c.606 0 1.091 0 1.5.017q-.02.12-.02.244l-.01 2.834c0 1.097 0 2.067.105 2.848c.114.847.375 1.694 1.067 2.386c.69.69 1.538.952 2.385 1.066c.781.105 1.751.105 2.848.105h4.052c.043.534.043 1.19.043 2.063V14c0 3.771 0 5.657-1.172 6.828S17.771 22 14 22"
                                                      clip-rule="evenodd" opacity="0.45"/>
                                                <path fill="currentColor"
                                                      d="m11.51 2.26l-.01 2.835c0 1.097 0 2.066.105 2.848c.114.847.375 1.694 1.067 2.385c.69.691 1.538.953 2.385 1.067c.781.105 1.751.105 2.848.105h4.052q.02.232.028.5H22c0-.268 0-.402-.01-.56a5.3 5.3 0 0 0-.958-2.641c-.094-.128-.158-.204-.285-.357C19.954 7.494 18.91 6.312 18 5.5c-.81-.724-1.921-1.515-2.89-2.161c-.832-.556-1.248-.834-1.819-1.04a6 6 0 0 0-.506-.154c-.384-.095-.758-.128-1.285-.14z"/>
                                            </svg>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($letter->family_card_file != null)
                                        <a href="/storage/{{ $letter->family_card_file }}" target="_blank"
                                           class="underline text-grey-200 hover:text-white">
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
                                           target="_blank" class="underline text-grey-200 hover:text-white">
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
                                           class="underline text-grey-200 hover:text-white">
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
                                    @if($letter->status == 'process')
                                        <x-ui.button size="xs" color="blue">
                                            <svg class="w-3 h-3 me-1" xmlns="http://www.w3.org/2000/svg" width="24"
                                                 height="24" viewBox="0 0 24 24">
                                                <path fill="currentColor"
                                                      d="M8.625 8.5h-4.5a1 1 0 0 1-1-1V3a1 1 0 0 1 2 0v3.5h3.5a1 1 0 0 1 0 2"/>
                                                <path fill="currentColor"
                                                      d="M21 13a1 1 0 0 1-1-1A7.995 7.995 0 0 0 5.08 8.001a1 1 0 0 1-1.731-1.002A9.995 9.995 0 0 1 22 12a1 1 0 0 1-1 1m-1.125 9a1 1 0 0 1-1-1v-3.5h-3.5a1 1 0 0 1 0-2h4.5a1 1 0 0 1 1 1V21a1 1 0 0 1-1 1"/>
                                                <path fill="currentColor"
                                                      d="M12 22A10.01 10.01 0 0 1 2 12a1 1 0 0 1 2 0a7.995 7.995 0 0 0 14.92 3.999a1 1 0 0 1 1.731 1.002A10.03 10.03 0 0 1 12 22"/>
                                            </svg>
                                            Sedang Diproses
                                        </x-ui.button>
                                    @elseif($letter->status == 'reply')
                                        <x-ui.button size="xs" color="green">
                                            <svg class="w-3 h-3 me-1" xmlns="http://www.w3.org/2000/svg" width="24"
                                                 height="24" viewBox="0 0 24 24">
                                                <g fill="none" stroke="currentColor" stroke-width="1.1">
                                                    <path stroke-linecap="round"
                                                          d="m9.2 12l1.859 1.859a.2.2 0 0 0 .282 0L14.7 10.5"/>
                                                    <rect width="14" height="14" x="5" y="5" rx="4"/>
                                                </g>
                                            </svg>
                                            Surat Disetujui
                                        </x-ui.button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    <x-ui.button size="xs" color="blue"
                                                 wire:loading.class="opacity-50 cursor-not-allowed"
                                                 wire:click="apply({{ $letter->id }})"
                                                 wire:confirm="Ajukan surat sekarang? Setelah tindakan ini disetujui, anda tidak akan bisa melakukan perubahan atau menghapus surat ini!"
                                                 :disabled="$letter->submission_status">
                                        <slot:icon>
                                            <svg class="w-3 h-3 me-1" xmlns="http://www.w3.org/2000/svg" width="24"
                                                 height="24" viewBox="0 0 16 16">
                                                <g fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                          d="M7 3.207V5h1V3.207l2.647 2.647l.707-.708l-3.5-3.5h-.707l-3.5 3.5l.707.708z"
                                                          clip-rule="evenodd"/>
                                                    <path
                                                        d="m1.5 9l-.5.5v5l.5.5h12l.5-.5v-5l-.5-.5H9.95a2.5 2.5 0 0 1-4.9 0zm9.163 1H13v4H2v-4h2.337a3.5 3.5 0 0 0 6.326 0M7 6h1v1H7zm0 2h1v1H7z"/>
                                                </g>
                                            </svg>
                                        </slot:icon>
                                        Ajukan
                                    </x-ui.button>
                                    <x-ui.button size="xs" color="yellow" wire:click="editData({{ $letter->id }})"
                                                 :disabled="$letter->submission_status">
                                        <span class="iconify carbon--edit w-3 h-3 me-1"></span>
                                        Ubah
                                    </x-ui.button>
                                    <x-ui.button size="xs" color="red" wire:click="destroy({{ $letter->id }})"
                                                 wire:confirm="Anda yakin ingin menghapus data ini?"
                                                 :disabled="$letter->submission_status">
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
                {{ $this->letters->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
</div>
