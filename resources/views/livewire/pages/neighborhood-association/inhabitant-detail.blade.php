<?php

use function Livewire\Volt\{computed, state, layout, usesPagination, on, mount, updated, title};
use App\Models\CitizenAssociation;
use App\Models\NeighborhoodAssociation;
use Masmerise\Toaster\Toaster;
use App\Models\User;
use App\Models\FamilyCard;
use App\Models\FamilyMember;
use App\Models\Religion;
use App\Models\Education;
use App\Models\Employment;
use App\Models\BloodGroup;

layout('layouts.app');
title('Anggota Keluarga');
usesPagination();
state(['show' => 5, 'search' => ''])->url();
state(['family_card_id' => fn($id) => $id])->locked();
state(['id','family_card_number','head_of_family','province','regency','district','sub_district','citizen_association','neighborhood_association','address','postal_code']);
state(['resident_identification_number', 'name', 'gender', 'birth_place', 'birth_date', 'religion_id', 'education_id', 'employment_id', 'blood_group_id','user_id','email','password','password_confirmation']);
state(['position'=> 'familiar']);

mount(function () {
    if(auth()->user()->roles()->get()->first()->name != 'rt'){
        abort(404);
    }
    $family_card = FamilyCard::find($this->family_card_id);
    $this->family_card_number = $family_card->family_card_number;
    $this->head_of_family = $family_card->head_of_family;
    $this->province = $family_card->province->name;
    $this->regency = $family_card->regency->name;
    $this->district = $family_card->district->name;
    $this->sub_district = $family_card->sub_district->name;
    $this->citizen_association = $family_card->citizen->position;
    $this->neighborhood_association = $family_card->neighborhood->position;
    $this->address = $family_card->address;
    $this->postal_code = $family_card->postal_code;
});
$religions = computed(function () {
    return Religion::all();
});
$educations = computed(function () {
    return Education::all();
});
$employments = computed(function () {
    return Employment::all();
});
$blood_groups = computed(function () {
    return BloodGroup::all();
});

on(['close-modal-reset' => function ($wireModels) {
    $this->reset(['id','user_id']);
    $this->reset($wireModels);
    $this->resetErrorBag($wireModels);
    $this->position = 'familiar';
}]);

$familyMembers = computed(function () {
    return FamilyMember::where('resident_identification_number', 'like', '%' . $this->search . '%')
        ->orWhere('name', 'like', '%' . $this->search . '%')
        ->paginate($this->show, pageName: 'family-member-page');
});

$save = function () {
    $validate = $this->validate([
        'resident_identification_number' => ['required', 'string', 'min:16', 'max:16', 'unique:family_members,resident_identification_number' . ($this->id ? ',' . $this->id : '')],
        'position' => ['required', 'string'],
        'name' => ['required', 'string'],
        'gender' => ['required', 'string'],
        'birth_place' => ['required', 'string'],
        'birth_date' => ['required', 'date'],
        'religion_id' => ['required'],
        'education_id' => ['required'],
        'employment_id' => ['required'],
        'blood_group_id' => ['required']
    ]);
    $validate['family_card_id'] = $this->family_card_id;
    try {
        FamilyMember::updateOrCreate(['id' => $this->id],$validate);
        unset($this->familyMembers);
        $this->dispatch('close-modal', id: 'family-member-modal');
        Toaster::success('Data berhasil disimpan!');
    } catch (Exception $e) {
        $this->dispatch('close-modal', id: 'family-member-modal');
        Toaster::error($e->getMessage());
//        dd($e->getMessage());
    }
};

$edit = function ($id) {
    $familyMember = FamilyMember::find($id);
    $this->id = $familyMember->id;
    $this->resident_identification_number = $familyMember->resident_identification_number;
    $this->position = $familyMember->position;
    $this->name = $familyMember->name;
    $this->gender = $familyMember->gender;
    $this->birth_place = $familyMember->birth_place;
    $this->birth_date = $familyMember->birth_date;
    $this->religion_id = $familyMember->religion_id;
    $this->education_id = $familyMember->education_id;
    $this->employment_id = $familyMember->employment_id;
    $this->blood_group_id = $familyMember->blood_group_id;
    $this->dispatch('open-modal', id: 'family-member-modal');
};

$destroy = function ($id) {
    try {
        $familyMember = FamilyMember::find($id);
        $familyMember->delete();
        unset($this->familyMembers);
        Toaster::success('Data berhasil dihapus!');
    } catch (Exception $e) {
        Toaster::error($e->getMessage());
    }
};

$updateAccount = function () {
    $this->validate([
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'confirmed'],
    ]);
    try {
        $user = User::updateOrCreate([
            'id' => $this->user_id,
        ], [
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'email_verified_at' => now(),
            'remember_token' => \Illuminate\Support\Str::random(10),
        ])->assignRole('warga');
        $familyMember = FamilyMember::find($this->id);
        $familyMember->update([
            'user_id' => $user->id,
        ]);
        $this->dispatch('close-modal', id: 'family-member-account-modal');
        Toaster::success('Data berhasil disimpan!');
    } catch (Exception $e) {
        $this->dispatch('close-modal', id: 'family-member-account-modal');
        Toaster::error($e->getMessage());
//        dd($e->getMessage());
    }
};

$resetAccount = function ($id) {
    $familyMember = FamilyMember::find($id);
    $this->id = $familyMember->id;
    if ($familyMember->user_id) {
        $this->user_id = $familyMember->user_id;
        $this->email = $familyMember->user->email;
    }
    $this->dispatch('open-modal', id: 'family-member-account-modal');
};

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
            [
                'href' => route('dashboard'),
                'text' => 'Dashboard'
            ],
            [
                'text' => 'Warga'
            ],[
                'text' => 'Kartu Keluarga',
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
    <x-ui.modal id="family-member-modal" size="lg">
        <x-slot name="header">
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Anggota Keluarga {{ $this->head_of_family }}</h5>
        </x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mx-auto">
                <x-ui.input type="text" label="Nama Lengkap" wire:model="name" id="name" />
                <x-ui.input type="text" label="Nomor Induk Kependudukan" wire:model="resident_identification_number" id="resident_identification_number" />
                <x-ui.input-select id="gender" name="gender" wire:model="gender" class="w-full" label="Jenis Kelamin">
                    <option value="">Pilih?</option>
                    <option value="M">Laki-Laki</option>
                    <option value="F">Perempuan</option>
                </x-ui.input-select>
                <x-ui.input-select id="position" name="position" wire:model="position" class="w-full" label="Status">
                    <option value="familiar">Anggota Keluarga</option>
                    <option value="patriarch">Kepala Keluarga</option>
                </x-ui.input-select>
                <x-ui.input type="text" label="Tempat Lahir" wire:model="birth_place" id="birth_place" />
                <x-ui.input type="date" label="Tanggal Lahir" wire:model="birth_date" id="birth_date" />
                <x-ui.input-select id="religion_id" name="religion_id" wire:model="religion_id" class="w-full" label="Agama" server :data="$this->religions" />
                <x-ui.input-select id="education_id" name="education_id" wire:model="education_id" class="w-full" label="Pendidikan" server :data="$this->educations" />
                <x-ui.input-select id="employment_id" name="employment_id" wire:model="employment_id" class="w-full" label="Pekerjaan" server :data="$this->employments" />
                <x-ui.input-select id="blood_group_id" name="blood_group_id" wire:model="blood_group_id" class="w-full" label="Golongan Darah" server :data="$this->blood_groups" />

            </div>

        </x-slot>
        <x-slot name="footer">
            <x-ui.button size="sm" reset color="light" class="mr-2" wire:click="$dispatch('close-modal', { id: 'family-member-modal' })">
                Batal
            </x-ui.button>
            <x-ui.button size="sm" title="Simpan" submit color="blue" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" wire:target="save" wire:click="save" loading-only />
        </x-slot>
    </x-ui.modal>
    <x-ui.modal id="family-member-account-modal">
        <x-slot name="header">
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Buat/Reset Akun</h5>
        </x-slot>
        <x-slot name="content">
            <x-ui.input type="email" label="Email" wire:model="email" id="email"/>
            <div class="grid-cols-1 sm:grid-cols-2 grid gap-2 my-2">
                <x-ui.input type="password" label="Password" wire:model="password" id="password"/>
                <x-ui.input type="password" label="Konfirmasi Password" wire:model="password_confirmation" id="password_confirmation"/>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-ui.button size="sm" reset color="light" class="mr-2" wire:click="$dispatch('close-modal', { id: 'family-member-account-modal' })">
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
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Anggota Keluarga</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar anggota keluarga {{ $this->head_of_family }}</p>
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
                        <x-ui.button wire:click="$dispatch('open-modal', { id :'family-member-modal'})" size="xs" color="blue">
                            <span class="iconify duo-icons--add-circle w-4 h-4 me-1"></span>
                            Tambah Anggota
                        </x-ui.button>
                    </div>
                </x-slot>

                <div class="border dark:border-gray-600 rounded-lg p-3 border-gray-200 mx-auto">
                    <h3 class="text-center font-bold text-lg dark:text-blue-100 text-gray-900">No. {{ $this->family_card_number }}</h3>
                </div>
                <div class="border dark:border-gray-600 rounded-lg p-3 border-gray-200 mx-auto">
                    <table class="w-full dark:text-gray-100 text-gray-900" >
                        <tr>
                            <td class="font-bold">Nama Kepala Keluarga</td>
                            <td>: {{ $this->head_of_family }}</td>
                            <td class="font-bold">Desa/Kelurahan</td>
                            <td>: {{ $this->sub_district }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Alamat</td>
                            <td>: {{ $this->address }}</td>
                            <td class="font-bold">Kecamatan</td>
                            <td>: {{ $this->district }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">RT/RW</td>
                            <td>: {{ $this->citizen_association }}/{{ $this->neighborhood_association }}</td>
                            <td class="font-bold">Kabupaten/Kota</td>
                            <td>: {{ $this->regency }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Kode Pos</td>
                            <td>: {{ $this->postal_code }}</td>
                            <td class="font-bold">Provinsi</td>
                            <td>: {{ $this->province }}</td>
                        </tr>
                    </table>
                </div>
                <x-ui.table thead="#, Status, Nama, Nomor Induk Kependudukan, Jenis Kelamin, Tempat Lahir, Tanggal Lahir, Agama, Pendidikan, Jenis Pekerjaan, Golongan Darah" :action="true">

                    @if($this->familyMembers->count() > 0)
                        @foreach($this->familyMembers as $key => $familyMember)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->position == 'patriarch' ? 'Kepala Keluarga' : 'Anggota Keluarga' }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyMember->resident_identification_number }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->gender == 'M' ? 'Laki-Laki' : 'Perempuan' }}
                                </td>
                                <td class="px-6 py-4 ">
                                    {{ $familyMember->birth_place }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->birth_date }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyMember->religion->name }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->education->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyMember->employment->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyMember->blood_group->name }}
                                </td>
                                <td class="px-6 py-4 ">
                                    <x-ui.button class="w-24 mb-1" size="xs" color="yellow" wire:click="resetAccount({{ $familyMember->id }})" >
                                        <span class="iconify carbon--user w-3 h-3 me-1"></span>
                                        Buat Akun
                                    </x-ui.button>
                                    <x-ui.button class="w-24 mb-1" size="xs" color="blue" wire:click="edit({{ $familyMember->id }})" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" wire:target="edit({{ $familyMember->id }})">
                                        <span class="iconify carbon--edit w-3 h-3 me-1"></span>
                                        Ubah
                                    </x-ui.button>
                                    <x-ui.button class="w-24 mb-1" size="xs" color="red" wire:click="destroy({{ $familyMember->id }})"
                                                 wire:confirm="Anda yakin ingin menghapus data ini?">
                                        <span class="iconify carbon--delete w-3 h-3 me-1"></span>
                                        Hapus
                                    </x-ui.button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-4 text-center" colspan="11">
                                Tidak ada data
                            </td>
                        </tr>
                    @endif
                </x-ui.table>

                {{ $this->familyMembers->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
</div>
